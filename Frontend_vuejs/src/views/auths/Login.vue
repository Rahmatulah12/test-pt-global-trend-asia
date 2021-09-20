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
                                    Sign In
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
                                                      :error-messages="errors"
                                                      ></v-text-field>
                                                </validation-provider>
                                          </v-col>

                                          <v-col cols="12">
                                                <validation-provider
                                                      v-slot="{ errors }"
                                                      name="Password"
                                                      rules="required"
                                                >
                                                      <v-text-field
                                                      v-model="password"
                                                      label="Password"
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

                                          <v-divider class="my-2"></v-divider>

                                          <v-col cols="12">

                                                <v-btn
                                                      class="ma-2"
                                                      color="success"
                                                      type="submit"
                                                >
                                                      Sign In
                                                </v-btn>

                                                <v-btn
                                                      class="ma-2"
                                                      color="grey"
                                                      @click="$router.push('/registration')"
                                                >
                                                      <span class="text--white">Register</span>
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
import { required, } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'

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
      name: 'Login',
      metaInfo: {
            // if no subcomponents specify a metaInfo.title, this title will be used
            title: 'Test PT.Kencana Konsep',
            // all titles will be injected into this template
            titleTemplate: '%s | Sign In'
      },
      data: () => ({
            user:{},
            username: '',
            password: null,
            showPass: false,
            loading: false,
            valid: true,
            loader: false,
            dialog: false,
      }),
      computed: {
            params() {
                  return {
                        username: this.username,
                        password: this.password
                  }
            }
      },
      methods: {
            submit(){
                        this.$refs.observer.validate();
                        this.loading = true;
                        // this.user = {email: this.email, password: this.password};
                        axios.post('login', this.params).then((response) => {
                              this.username = "";
                              this.password = null;
                              setTimeout(() => (this.loading = false), 1000);
                              localStorage.setItem('token', response.data.token);
                              this.$router.push("/student");
                              this.$toast.open({
                                    message: `Login has been success`,
                                    type: "success",
                                    dissmissible: true,
                                    position: "top-right",
                                    duration: 5000,
                              });
                        })
                        .catch((err) => {
                              setTimeout(() => (this.loading = false), 1000);
                              this.$toast.open({
                                    message: `${err.response.data.message}`,
                                    type: "error",
                                    dissmissible: true,
                                    position: "top-right",
                                    duration: 5000,
                              });
                        });
                  
            },
            registration(){
                  this.$router.push("/registration")
            }
      },

}
</script>
