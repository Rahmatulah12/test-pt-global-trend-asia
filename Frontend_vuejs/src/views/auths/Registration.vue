<template>
      <v-container fluid>
            <v-row
                  no-gutters
            >
                  <v-col
                        cols="12"
                        md="12"
                        lg="12"
                  >
                        <v-card
                              elevation="3"
                              max-width="40%"
                              class="mx-auto my-12"
                              dark
                              color="accent darken-3"
                              :loading="loading"
                        >

                              <template slot="progress">
                                    <v-progress-linear
                                          color=" deep-purple accent-2"
                                          height="25"
                                          indeterminate
                                    ></v-progress-linear>
                              </template>

                              <v-card-title>
                                    Registration
                              </v-card-title>

                              <v-divider class="my-3"></v-divider>

                              <validation-observer
                                    ref="observer"
                                    v-slot="{ invalid }"
                              >

                                    <form @submit.prevent="submit">

                                          <v-col cols="12">
                                                <validation-provider
                                                      v-slot="{ errors }"
                                                      name="Username"
                                                      rules="required"
                                                >
                                                      <v-text-field
                                                            v-model="username"
                                                            label="Username"
                                                            placeholder="Username"
                                                            required
                                                            outlined
                                                            filled
                                                            dense
                                                            :error-messages="errorMessage.username == '' ? errors : errorMessage.username"
                                                            @keyup="clear()"
                                                      >
                                                      </v-text-field>
                                                </validation-provider>
                                          </v-col>

                                          <v-col cols="12">
                                                <validation-provider
                                                      v-slot="{ errors }"
                                                      name="Email"
                                                      rules="required|email"
                                                >
                                                      <v-text-field
                                                      v-model="email"
                                                      label="Email"
                                                      placeholder="example@example.com"
                                                      required
                                                      outlined
                                                      filled
                                                      dense
                                                      :error-messages="errorMessage.email == '' ? errors : errorMessage.email"
                                                      @keyup="clear()"
                                                      >
                                                      </v-text-field>
                                                </validation-provider>
                                          </v-col>

                                          <v-col cols="12">
                                                <validation-provider
                                                      v-slot="{ errors }"
                                                      name="Password"
                                                      rules="required|min:8|password:@Confirmation"
                                                >
                                                      <v-text-field
                                                      v-model="password"
                                                      label="Password"
                                                      :counter="8"
                                                      :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'"
                                                      @click:append="showPass = !showPass"
                                                      required
                                                      outlined
                                                      dense
                                                      filled
                                                      :type="showPass ? 'text' : 'password'"
                                                      :error-messages="errors"
                                                      ></v-text-field>
                                                </validation-provider>
                                          </v-col>

                                          <v-col cols="12">
                                                <validation-provider
                                                      v-slot="{ errors }"
                                                      name="Confirmation"
                                                      rules="required"
                                                >
                                                      <v-text-field
                                                      v-model="confirmationPassword"
                                                      label="Confirmation Password"
                                                      :append-icon="showConfirmationPass ? 'mdi-eye' : 'mdi-eye-off'"
                                                      @click:append="showConfirmationPass = !showConfirmationPass"
                                                      required
                                                      outlined
                                                      dense
                                                      filled
                                                      :type="showConfirmationPass ? 'text' : 'password'"
                                                      :error-messages="errors"
                                                      ></v-text-field>
                                                </validation-provider>
                                          </v-col>

                                          <v-divider class="my-2"></v-divider>

                                          <v-col cols="12">

                                                <v-btn
                                                      class="ma-2"
                                                      color="success"
                                                      type="submit"
                                                >
                                                      Registration
                                                </v-btn>

                                                <v-btn
                                                      class="ma-2"
                                                      color="grey"
                                                      @click="$router.push('/login')"
                                                >
                                                      <span class="text--white">Sign In</span>
                                                </v-btn>

                                          </v-col>
                                    
                                    </form>

                              </validation-observer>

                        </v-card>
                  </v-col>
            </v-row>
      </v-container>
</template>

<script>
import axios from '../../api';
import { required, email, min } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'

setInteractionMode('eager');

extend('required', {
    ...required,
    message: '{_field_} can not be empty',
});

extend('min', {
    ...min,
    message: '{_field_} min {length} characters',
});

extend('email', {
    ...email,
    message: 'Email must be valid',
});

extend('password', {
  params: ['target'],
  validate(value, { target }) {
    return value === target;
  },
  message: 'Password confirmation does not match'
});
export default {
    components: {
      ValidationProvider,
      ValidationObserver,
    },
    name: 'Registration',
    metaInfo: {
        // if no subcomponents specify a metaInfo.title, this title will be used
        title: 'Test PT.Kencana Konsep',
        // all titles will be injected into this template
        titleTemplate: '%s | Registration'
    },
    data(){
        return {
            username: '',
            email: '',
            password: null,
            showPass: false,
            showConfirmationPass: false,
            loading: false,
            valid: true,
            loader: false,
            confirmationPassword: null,
            user: {},
            dialog: false,
            errorMessage: {
                  username: "",
                  email: "",
                  password: "",
                  passwordConfirmation: "",
            }
        }
    },
    methods: {
        submit () {
            this.$refs.observer.validate();
            this.user = {username: this.username, email: this.email, password: this.password, password_confirmation: this.confirmationPassword};
            this.loading = true;
            axios.post('registration', this.user).then((response) => {
                  this.username = '';
                  this.email = "";
                  this.password = null;
                  this.confirmationPassword = null;
                  setTimeout(() => (this.loading = false), 1000);
                  this.$router.push("/login");
                  this.$toast.open({
                        message: `Registration has been successful`,
                        type: "success",
                        dissmissible: true,
                        position: "top-right",
                        duration: 5000,
                  });
            })
            .catch((err) => {
                setTimeout(() => (this.loading = false), 1000);
                this.errorMessage.email = err.response.data.message.email[0] ? err.response.data.message.email[0] : '';
                this.errorMessage.username = err.response.data.message.username[0] ? err.response.data.message.username[0] : '';
                this.$toast.open({
                    message: `${err}`,
                    type: "error",
                    dissmissible: true,
                    position: "top-right",
                    duration: 5000,
                });
            });
        },
        clear () {
            this.errorMessage.email = '';
            this.errorMessage.username = '';
            // for reset validation vee-validate
            // this.$refs.observer.reset();
        },
    },
}
</script>