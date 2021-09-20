<template>
<v-app>
      <v-app-bar
            color="blue accent-4"
            dark
            app
      >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

            <v-toolbar-title>Test PT. Kencana Konsep</v-toolbar-title>

            <v-spacer></v-spacer>
      </v-app-bar>

      <v-navigation-drawer
            v-model="drawer"
            absolute
            bottom
            temporary
      >
            <v-list
                  nav
                  dense
            >
                  <v-list-item-group
                        v-model="group"
                        active-class="blue--text text--accent-4"
                  >

                        <router-link
                              to="student"
                              style="text-decoration: none;"
                        >
                              <v-list-item>
                                    <v-list-item-title>Student</v-list-item-title>
                              </v-list-item>
                        </router-link>

                         <router-link
                              to="hobby"
                              style="text-decoration: none;"
                        >
                              <v-list-item>
                                    <v-list-item-title>Hobby</v-list-item-title>
                              </v-list-item>
                        </router-link>

                        <router-link to="student-hobby" style="text-decoration: none;">
                              <v-list-item>
                                    <v-list-item-title>Student Hobby</v-list-item-title>
                              </v-list-item>
                        </router-link>

                        <v-list-item @click="logout();">
                              <v-list-item-title>Logout</v-list-item-title>
                        </v-list-item>

                  </v-list-item-group>
            </v-list>
      </v-navigation-drawer>

      <v-main style="background-color: grey !important;">
            <v-container>
                  <router-view></router-view>
            </v-container>
      </v-main>
</v-app>
</template>
<script>
import axios from '../api';
export default {
      name: 'Main',

      data: () => ({
            drawer: false,
            group: null,
      }),

      watch: {
            group () {
            this.drawer = false
            },
      },

      methods: {
            async logout(){
                  try{
                        let response = await axios.get("/logout");
                        if(response){
                              localStorage.removeItem("token");
                              this.$router.push("/login");
                              this.$toast.open({
                                    message: `You has been logout`,
                                    type: "success",
                                    dissmissible: true,
                                    position: "top-right",
                                    duration: 5000,
                              });
                        }
                  } catch (err){
                        this.$toast.open({
                              message: `${err}`,
                              type: "success",
                              dissmissible: true,
                              position: "top-right",
                              duration: 5000,
                        });
                  }
            },
      },
}
</script>