<template>
  <v-row>
      <v-col
            cols="12"
            md="12"
            lg="12"
      >
      
            <v-card
                elevation="2"
                shaped
            >
            
                  <v-card-title>
                        Student
                  </v-card-title>
                  <v-card-subtitle>
                        {{ action }} Student
                  </v-card-subtitle>

                  <v-divider class="mt-1 mb-5 mx-3"></v-divider>

                  <validation-observer
                        ref="observer"
                        v-slot="{ invalid }"
                  >
                  
                        <form @submit.prevent="submit">
                        
                              <v-col cols="8" md="8" lg="8">
                                    <validation-provider
                                          v-slot="{ errors }"
                                          name="Name"
                                          rules="required"
                                    >
                                          <v-text-field
                                                v-model="student.name"
                                                label="Name"
                                                placeholder="Enter your name"
                                                required
                                                outlined
                                                filled
                                                dense
                                                :error-messages="errorMessage.name == '' ? errors : errorMessage.name"
                                                @keyup="clear()"
                                          >
                                          </v-text-field>
                                    </validation-provider>
                              </v-col>

                              <v-col cols="8" md="8" lg="8">
                                    <validation-provider
                                          v-slot="{ errors }"
                                          name="Email"
                                          rules="required|email"
                                    >
                                          <v-text-field
                                                v-model="student.email"
                                                label="Email"
                                                placeholder="Enter your email"
                                                required
                                                outlined
                                                filled
                                                dense
                                                type="email"
                                                :error-messages="errorMessage.email == '' ? errors : errorMessage.email"
                                                @keyup="clear()"
                                          >
                                          </v-text-field>
                                    </validation-provider>
                              </v-col>

                              <v-col cols="8" md="8" lg="8">
                                    <validation-provider
                                          v-slot="{ errors }"
                                          name="Phone"
                                          rules="required|min:10|max:12"
                                    >
                                          <v-text-field
                                                v-model="student.phone"
                                                label="Phone"
                                                placeholder="Enter your phone number"
                                                required
                                                outlined
                                                filled
                                                dense
                                                type="number"
                                                min="0"
                                                :error-messages="errorMessage.phone == '' ? errors : errorMessage.phone"
                                                @keyup="clear()"
                                          >
                                          </v-text-field>
                                    </validation-provider>
                              </v-col>
                              <v-col cols="8" md="8" lg="8">
                                    <validation-provider
                                          v-slot="{ errors }"
                                          name="Address"
                                          rules="required"
                                    >
                                          <v-textarea
                                                auto-grow
                                                v-model="student.address"
                                                label="Address"
                                                placeholder="Enter your address"
                                                required
                                                outlined
                                                filled
                                                dense
                                                :error-messages="errorMessage.address == '' ? errors : errorMessage.address"
                                                @keyup="clear()"
                                          >
                                          </v-textarea>
                                    </validation-provider>
                              </v-col>

                              <v-divider class="my-2"></v-divider>

                              <v-col cols="8">

                                    <v-btn
                                          class="ma-2"
                                          color="info"
                                          @click="dialog = true"
                                    >
                                          Submit
                                    </v-btn>
                              
                                    <v-btn
                                          class="ma-2"
                                          color="grey"
                                          @click="$router.back()"
                                    >
                                          <span class="text--white">Back</span>
                                    </v-btn>

                              </v-col>

                        </form>
                  
                  </validation-observer>

            </v-card>
      
      </v-col>
      <v-row justify="center">
            <v-dialog
                  v-model="dialog"
                  persistent
                  max-width="350"
            >

                  <v-card>
                        <v-card-title class="headline">
                              Are you sure?
                        </v-card-title>
                        <v-card-text>
                              This data will be saved, on our database. If there is incorrect data, please click the cancel button. 
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                              color="green darken-1"
                              type="submit"
                              text
                              @click="submit()"
                        >
                              Save
                        </v-btn>
                        <v-btn
                              color="red darken-1"
                              text
                              @click="dialog = false"
                        >
                              Cancel
                        </v-btn>
                        </v-card-actions>
                        </v-card>

            </v-dialog>
      </v-row>
  </v-row>
</template>
<script>
import axios from '../../api';
import { required, email, min, max, digits } from 'vee-validate/dist/rules';
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate';

setInteractionMode('eager');

extend('required', {
    ...required,
    message: '{_field_} can not be empty',
});

extend('min', {
    ...min,
    message: '{_field_} min {length} characters',
});

extend('max', {
    ...max,
    message: '{_field_} max {length} characters',
});

extend('email', {
    ...email,
    message: 'Email must be valid',
});

export default {
      components: {
            ValidationProvider,
            ValidationObserver,
      },

      'name' : 'FormStudent',

      data(){
            return{
                  action: '',
                  student: {
                        name: '',
                        email: '',
                        phone: '',
                        address: '',
                  },
                  errorMessage: {
                        name: '',
                        email: '',
                        phone: '',
                        address: '',
                  },
                  dialog: false,
                  url: '',
            }
      },

      mounted() {
            this.action = this.$route.params.id === undefined ? "Add" : "Edit";
            this.url = this.$route.params.id === undefined ? "student/add" : `student/update/${this.$route.params.id}`;
            if (this.$route.params.id !== undefined) {
                  // console.log(JSON.stringify(this.$router.params.id));
                  this.getDataStudent(this.$route.params.id);
            }
      },

      methods: {

            async submit(){
                  try{
                        let valid = this.$refs.observer.validate();
                        if(valid){
                              let submit;
                              if(this.$route.params.id === undefined){
                                    submit = await axios.post(this.url, this.student, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                              } else {
                                    submit = await axios.patch(this.url, this.student, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                              }

                              if(submit){
                                    this.dialog = false;
                                    this.$router.back();
                                    this.$toast.open({
                                          message: `Data has been saved.`,
                                          type: "success",
                                          dissmissible: true,
                                          position: "top-right",
                                          duration: 5000,
                                    });
                              }   
                        } else {
                              this.dialog = false;
                              return false;
                        }
                  } catch(err) {
                        this.dialog = false;
                        if(err.response.status == 401){
                              localStorage.removeItem("token");
                              this.$router.push("/login");
                              this.$toast.open({
                                    message: `${err.response.data.message}`,
                                    type: "error",
                                    dissmissible: true,
                                    position: "top-right",
                                    duration: 5000,
                              });
                        } else {
                              this.errorMessage = {
                                    name: err.response.data.message.name[0] ? err.response.data.message.name[0] : '',
                                    email: err.response.data.message.email[0] ? err.response.data.message.email[0] : '',
                                    phone: err.response.data.message.phone[0] ? err.response.data.message.phone[0] : '',
                                    address: err.response.data.message.address[0] ? err.response.data.message.address[0] : '',
                              };
                              this.$toast.open({
                                    message: `${err.response.statusText}`,
                                    type: "error",
                                    dissmissible: true,
                                    position: "top-right",
                                    duration: 5000,
                              });
                        } 
                  }
            },

            clear () {
                  this.errorMessage = {
                        name : '',
                        email: '',
                        phone: '',
                        address: '',
                  };
                  // for reset validation vee-validate
                  // this.$refs.observer.reset();
            },

            async getDataStudent(id)
            {
                  try{
                        let response = await axios.get(`student/${id}`, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                        if(response){
                              this.student = response.data.data;
                        }
                  } catch(err){
                        if(err.response.status == 401){
                              localStorage.removeItem("token");
                              this.$router.push("/login");
                              this.$toast.open({
                                    message: `${err.response.data.message}`,
                                    type: "error",
                                    dissmissible: true,
                                    position: "top-right",
                                    duration: 5000,
                              });
                        } else {
                              this.$toast.open({
                                    message: `${err}`,
                                    type: "error",
                                    dissmissible: true,
                                    position: "top-right",
                                    duration: 5000,
                              });
                        } 
                  }
            }
      },
}
</script>