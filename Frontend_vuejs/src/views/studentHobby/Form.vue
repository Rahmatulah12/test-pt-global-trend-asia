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
                        {{ action }} Student Hobby
                  </v-card-subtitle>

                  <v-divider class="mt-1 mb-5 mx-3"></v-divider>

                  <validation-observer
                        ref="observer"
                        v-slot="{ invalid }"
                  >
                  
                        <form @submit.prevent="submit">
                        
                              <v-col cols="8" md="8" lg="8" v-if="$route.params.id !== undefined">
                                    <validation-provider
                                          v-slot="{ errors }"
                                          name="Id"
                                          rules="required"
                                    >
                                          <v-text-field
                                                v-model="studentHobby.id"
                                                label="Id"
                                                placeholder="Enter your id"
                                                required
                                                outlined
                                                filled
                                                dense
                                                :error-messages="errors"
                                                @keyup="clear()"
                                                readonly
                                          >
                                          </v-text-field>
                                    </validation-provider>
                              </v-col>

                              <v-col cols="8" md="8" lg="8">
                                    <validation-provider
                                          v-slot="{ errors }"
                                          name="Student"
                                          rules="required"
                                    >
                                          <v-autocomplete
                                                v-model="studentHobby.student_id"
                                                :items="studentItems"
                                                :value.sync="studentHobby.student_id"
                                                outlined
                                                dense
                                                label="Student"
                                                :error-messages="errors"
                                          ></v-autocomplete>
                                    </validation-provider>
                              </v-col>

                              <v-col cols="8" md="8" lg="8">
                                    <validation-provider
                                          v-slot="{ errors }"
                                          name="Hobby"
                                          rules="required"
                                    >
                                          <v-autocomplete
                                                v-model="studentHobby.hoby_id"
                                                :items="hobbyItems"
                                                :value.sync="studentHobby.hoby_id"
                                                outlined
                                                dense
                                                label="Hobby"
                                                :error-messages="errors"
                                          ></v-autocomplete>
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
import { required } from 'vee-validate/dist/rules';
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate';

setInteractionMode('eager');

extend('required', {
    ...required,
    message: '{_field_} can not be empty',
});
export default {
      components: {
            ValidationProvider,
            ValidationObserver,
      },

      'name' : 'FormStudentHobby',

      data(){
            return{
                  studentItems : [],
                  hobbyItems : [],
                  studentHobby: {
                        id: null,
                        student_id:null,
                        hoby_id: null,
                  },
                  action: '',
                  url: '',
                  dialog: false,
            }
      },

      mounted() {
            this.getDataStudent();
            this.getDataHobby();
            this.action = this.$route.params.id === undefined ? "Add" : "Edit";
            this.url = this.$route.params.id === undefined ? "student-hobby/add" : `student-hobby/update/${this.$route.params.id}`;
            if (this.$route.params.id !== undefined) {
                  // console.log(JSON.stringify(this.$router.params.id));
                  this.getDataStudentHobby(this.$route.params.id);
            }
      },

      methods: {

            async submit(){
                  try{
                        let valid = this.$refs.observer.validate();
                        if(valid){
                              let submit;
                              let data;
                              if(this.$route.params.id === undefined){
                                    data = {student_id : this.studentHobby.student_id, hoby_id: [this.studentHobby.hoby_id]};
                                    submit = await axios.post(this.url, data, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                              } else {
                                    data = {student_id : this.studentHobby.student_id, hoby_id: this.studentHobby.hoby_id};
                                    submit = await axios.patch(this.url, data, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                              }

                              if(submit){
                                    this.dialog = false;
                                    this.$router.back();
                                    data = {};
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
                              data = {};
                              return false;
                        }
                  } catch(err) {
                        this.dialog = false;
                        data = {};
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

            async getDataStudentHobby(id)
            {
                  try{
                        let response = await axios.get(`student-hobby/${id}`, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                        if(response){
                              this.studentHobby = response.data.data;
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
            },

            ucwords(str){
                  return str.replace (/\w\S*/g, 
                  function(txt)
                  {  return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); } );
            },

            async getDataStudent(){
                  try{
                        let data = await axios.get('student/options', { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                        if(data){
                              for(let i = 0; i < data.data.data.length; i++){
                                    this.studentItems.push({text: this.ucwords(data.data.data[i].name), value: data.data.data[i].id});
                              }
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
            },

            async getDataHobby(){
                  try{
                        let data = await axios.get('hobby/options', { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                        if(data){
                              for(let i = 0; i < data.data.message.length; i++){
                                    this.hobbyItems.push({text: this.ucwords(data.data.message[i].name), value: data.data.message[i].id});
                              }
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