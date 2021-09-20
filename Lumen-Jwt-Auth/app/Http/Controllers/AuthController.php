<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Transformers\RegistrationTransformers;
use App\Transformers\LoginTransformers;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;
    private $fractal;

    public function __construct(JWTAuth $jwt, Manager $fractal)
    {
        $this->jwt = $jwt;
        $this->fractal = $fractal;
    }

    protected function failedValidation() {
        $message = Validator::errors()->all();
        throw new HttpResponseException(response()->json(['status' => 0,'messages' => $message]));
    }

    /*
        @param array $request,
        @param string $action
        @default $action = "login"
        @return validator
    */
    protected function validation(array $data, $action = "register")
    {
        if($action == "register")
        {
            $rules = [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => [
                    'required', 'string', 'min:8', 'confirmed',
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
                    "required_with:password_confirmation", "same:password_confirmation", "confirmed"
                ],
                "password_confirmation" => ["required", "min:8"],
            ];
        } else if($action == "login") {
            $rules = [
                "email" => ['required', "email"],
                "password" => ['required', "min:8"],
            ];
        }

        return Validator::make($data, $rules, $this->validationMessage());
    }

    private function validationMessage()
    {
        return [
            "name.required" => "Name is required",
            "name.max" => "Name max length 100 characters",
            "email.required" => "Email is required",
            "email.email" => "Email is must be correct",
            "email.unique" => "Email has already active.",
            "password.min" => "Password at least 8 characters",
            "password_confirmation.required" => "Password Confirmation is required",
        ];
    }

    private function create(array $data)
    {
        return User::create($data);
    }

    public function registration(Request $request)
    {
        $data = $request->all();
        $validation = $this->validation($data, "register");

        if($validation->fails())
        {
            return response()->json([
                'error' => true,
                "code" => 422,
                'message' => $validation->errors(),
            ], 422);
        }

        unset($data['password_confirmation']);
        $data['password'] = Hash::make($data['password']);

        // create new user
        $user = $this->create($data);

        if(!$user)
        {
            return response()->json($this->errorMessage(), (int) $this->errorMessage()["code"]);
        }

        $resource = new Item($user, new RegistrationTransformers);
        return response()->json(array_merge(
            ["error" => false, "code" => 200],
            $this->fractal->createData($resource)->toArray()), 200);
    }

    public function login(Request $request)
    {
        $data = $request->only('email', 'password');
        $validation = $this->validation($data, "login");

        if($validation->fails())
        {
            return response()->json([
                'error' => true,
                "code" => 422,
                'message' => $validation->errors(),
            ], 422);
        }

        try{
            if (! $token = $this->jwt->attempt($data)) {
                return response()->json([
                    "error" => true,
                    "code" => 404,
                    "message" => "User not found.",
                ], 404);
            }

            // jika lolos pengecekan, simpan api_token
            $user = User::where('email', $data['email'])->first();
            $user->api_token = $token;
            $user->save();

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);

        }

        $resource = new Item($user, new LoginTransformers);
        $result = [
            "error" => false,
            "code" => 200,
            "token" => $token,
        ];

        return response()->json(array_merge($result, $this->fractal->createData($resource)->toArray()), 200);

    }

    private function errorMessage() {
        return [
            "error" => true,
            "code" => 500,
            "data" => null,
            "message" => "Something went wrong, please try again later.",
        ];
    }

    public function logout(Request $request)
    {
        $token = $this->jwt->getToken();

        // delete api_token
        $user = User::find($this->jwt->user()->id);
        $user->api_token = null;
        $user->save();

        $this->jwt->invalidate($this->jwt->getToken());
        return response()->json([
            "error" => false,
            "code" => 200,
            "message" => "Logout has success"
        ], 200);

    }

}
