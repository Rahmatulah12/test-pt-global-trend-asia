<template>
    <v-row>
        <v-col
            cols="12"
        >
            <v-card
                elevation="2"
                shaped
            >
                <v-card-title>
                    Hobby
                </v-card-title>
                <v-card-subtitle>
                    List Hobby
                </v-card-subtitle>

                <v-divider class="mt-1 mb-5 mx-3"></v-divider>

                <div class="d-flex flex-row-reverse mr-5 mb-3">
                    <v-btn
                        color="primary"
                        small
                        @click="addDialog = true;"
                    >
                        Add Hobby
                        <v-icon dark right>
                            mdi-plus
                        </v-icon>
                    </v-btn>
                </div>

                <v-row class='mt-5'>
                    <v-col
                        cols="4"
                        md="4"
                    >
                        <v-text-field type="text" v-model="keyword" 
                            prepend-icon="mdi-magnify"
                            label="Search"
                            placeholder="Please enter keyword..."
                            @keyup.enter="searching()"
                            class="mx-2"
                            dense
                        ></v-text-field>
                    </v-col>
                    <v-col
                        cols="4"
                        md="4"
                        offset-md="4"
                    >
                        <v-combobox
                            v-model="size"
                            :items="optionSize"
                            label="Size"
                            :value.sync="size"
                            dense
                            class="mx-5"
                            @change="pageSizeChange()"
                        ></v-combobox>
                    </v-col>
                </v-row>
                <v-col
                    cols="12"
                    md="12"
                >
                    <v-simple-table fixed-header height="300">
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th class="text-justify">
                                        Name
                                    </th>
                                    <th class="text-justify">
                                        Created At
                                    </th>
                                    <th class="text-justify">
                                        Updated At
                                    </th>
                                    <th class="text-center">
                                        #
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item,index) in items" :key="index"
                                >
                                    <td class="text-justify">
                                        {{ ucwords(item.name) }}
                                    </td>
                                    <td class="text-justify">
                                        {{ formatDate(item.created_at) }}
                                    </td>
                                    <td class="text-justify">
                                        {{ formatDate(item.updated_at) }}
                                    </td>
                                    <td class="text-center">

                                        <v-tooltip top>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn
                                                    color="success"
                                                    dark
                                                    icon
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    @click="editDialog = true;selectedId = item.id;hobby.name=item.name;"
                                                >
                                                    <v-icon>mdi-eye</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>View</span>
                                        </v-tooltip>

                                        <v-tooltip top>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn
                                                    color="error"
                                                    dark
                                                    icon
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    @click="dialog = true;selectedId = item.id;"
                                                >
                                                    <v-icon>mdi-delete-empty</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Delete</span>
                                        </v-tooltip>

                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
                <div class="text-center mb-5">
                    <v-pagination
                        v-model="page"
                        :length="totalPage"
                        @input="pageChange(page)"
                        @input:next="pageChange(page)"
                        @input:prev="pageChange(page)"
                    ></v-pagination>
                </div>
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
                              This data will be deleted, on our database. If not sure, please click cancel. 
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                              color="green darken-1"
                              text
                              @click="destroy(selectedId)"
                        >
                              Deleted
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

      <!-- Add Form -->
      <v-row justify="center">
            <v-dialog
                  transition="dialog-bottom-transition"
                  max-width="600"
                  v-model="addDialog"
            >
                  <v-card>
                        <v-toolbar
                              color="primary"
                              dark
                        >
                              Add Hobby
                        </v-toolbar>
                        <validation-observer
                              ref="observer"
                              v-slot="{ invalid }"
                        >
                              <form @submit.prevent="submit">
                                    <v-card-text>
                                          <validation-provider
                                                v-slot="{ errors }"
                                                name="Name"
                                                rules="required"
                                          >
                                                <v-text-field
                                                      v-model="hobby.name"
                                                      label="Name"
                                                      placeholder="Enter hobby name"
                                                      required
                                                      outlined
                                                      filled
                                                      dense
                                                      :error-messages="errorMessage.name == '' ? errors : errorMessage.name"
                                                      @keyup="clear()"
                                                >
                                                </v-text-field>
                                          </validation-provider>
                                    </v-card-text>
                                    <v-card-actions class="justify-end">
                                          <v-btn
                                                text
                                                type="submit"
                                                color="success"
                                          >
                                                Save
                                          </v-btn>
                                          <v-btn
                                                text
                                                @click="addDialog = false"
                                                color="error"
                                          >
                                                Close
                                          </v-btn>
                                    </v-card-actions>
                              </form>
                        </validation-observer>
                        
                  </v-card>
            </v-dialog>
      </v-row>

      <!-- Edit Form -->
      <v-row justify="center">
            <v-dialog
                  transition="dialog-bottom-transition"
                  max-width="600"
                  v-model="editDialog"
            >
                  <v-card>
                        <v-toolbar
                              color="primary"
                              dark
                        >
                              Edit Hobby
                        </v-toolbar>
                        <validation-observer
                              ref="observer"
                              v-slot="{ invalid }"
                        >
                              <form @submit.prevent="update">
                                    <v-card-text>
                                          <validation-provider
                                                v-slot="{ errors }"
                                                name="Name"
                                                rules="required"
                                          >
                                                <v-text-field
                                                      v-model="hobby.name"
                                                      label="Name"
                                                      placeholder="Enter hobby name"
                                                      required
                                                      outlined
                                                      filled
                                                      dense
                                                      :error-messages="errorMessage.name == '' ? errors : errorMessage.name"
                                                      @keyup="clear()"
                                                >
                                                </v-text-field>
                                          </validation-provider>
                                    </v-card-text>
                                    <v-card-actions class="justify-end">
                                          <v-btn
                                                text
                                                type="submit"
                                                color="success"
                                          >
                                                Save
                                          </v-btn>
                                          <v-btn
                                                text
                                                @click="editDialog = false;selectedId = null;"
                                                color="error"
                                          >
                                                Close
                                          </v-btn>
                                    </v-card-actions>
                              </form>
                        </validation-observer>
                        
                  </v-card>
            </v-dialog>
      </v-row>

    </v-row>
</template>

<script>
import moment from "moment";
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
    name: 'Hobby',
    metaInfo: {
        // if no subcomponents specify a metaInfo.title, this title will be used
        title: 'Test PT. Kencana Konsep',
        // all titles will be injected into this template
        titleTemplate: '%s | Hobby'
    },
    mounted(){
        this.page = 1;
        this.getAllData();
    },
    data () {
        return {
              hobby: {
                    name: '',
              },
              errorMessage:{
                    name: '',
              },
            dialog: false,
            items: [],
            keyword: null,
            totalPage: 0,
            page:null,
            size: 25,
            lastActiveSize: null,
            optionSize: [25, 50, 100],
            nextUrl: '',
            prevUrl: '',
            search: null,
            selectedId : null,
            addDialog : false,
            editDialog: false,
        }
    },
      methods: {
            async getAllData(){
                  try{
                  let url = this.keyword != null && this.keyword.length > 0 ? 
                              `hobby?keyword=${this.keyword}&size=${this.size}&page=${this.page}` :
                              `hobby?size=${this.size}&page=${this.page}`
                  let data = await axios.get(url, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                  if(data){
                        this.items = data.data.data.data;
                        this.totalPage = Math.ceil(data.data.data.total / this.size);
                  }
                  } catch (err){
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
            async pageChange(page) {
                  try{
                  if(this.size != ''){
                        this.page = page;
                        await this.getAllData();
                  } else {
                        this.$toast.open({
                              message: `Size field can't be empty.`,
                              type: "error",
                              dissmissible: true,
                              position: "top-right",
                              duration: 5000,
                        });
                  }
                  } catch (err){
                  this.$toast.open({
                        message: `${err}`,
                        type: "error",
                        dissmissible: true,
                        position: "top-right",
                        duration: 5000,
                  });
                  }
                  
            },
            async searching(){
                  try{
                  if(this.size != ''){
                        await this.getAllData();
                  } else {
                        this.$toast.open({
                              message: `Size field can't be empty.`,
                              type: "error",
                              dissmissible: true,
                              position: "top-right",
                              duration: 5000,
                        });
                  }
                  
                  this.keyword = '';
                  } catch (err){
                  this.$toast.open({
                        message: `${err}`,
                        type: "error",
                        dissmissible: true,
                        position: "top-right",
                        duration: 5000,
                  });
                  }
                  
            },
            async pageSizeChange(){
                  try{
                  if(this.size != '')
                  {
                        this.page = 1;
                        await this.getAllData();
                  } else {
                        this.$toast.open({
                              message: `Size field can't be empty.`,
                              type: "error",
                              dissmissible: true,
                              position: "top-right",
                              duration: 5000,
                        });
                  }
                  } catch (err){
                  this.$toast.open({
                        message: `${err}`,
                        type: "error",
                        dissmissible: true,
                        position: "top-right",
                        duration: 5000,
                  });
                  }
                  
            },
            ucwords(str){
                  return str.replace (/\w\S*/g, 
                  function(txt)
                  {  return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); } );
            },

            async destroy(id)
            {
                  try{
                  let data = axios.delete(`hobby/delete/${id}`, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                  if(data)
                  {
                        this.dialog = false;
                        this.selectedId = null;
                        this.getAllData();
                        this.$toast.open({
                              message: `Data has been deleted.`,
                              type: "success",
                              dissmissible: true,
                              position: "top-right",
                              duration: 5000,
                        });
                  }
                  } catch (err){
                  this.dialog = false;
                  this.selectedId = null;
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

            formatDate(date)
            {
                  return moment(date).format('MMMM Do YYYY, h:mm:ss a');
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

            async submit(){
                  try{
                        let valid = this.$refs.observer.validate();
                        if(valid){
                              console.log(valid);
                              let post = await axios.post('hobby/add', this.hobby, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                              if(post)
                              {
                                    this.addDialog = false;
                                    this.getAllData();
                                    this.hobby.name = '';
                                    this.$toast.open({
                                          message: `Hobby has been added.`,
                                          type: "success",
                                          dissmissible: true,
                                          position: "top-right",
                                          duration: 5000,
                                    });

                              }
                        }
                  } catch(err){
                        this.addDialog = false;
                        this.hobby.name = '';
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

            async update()
            {
                  try{
                        let valid = this.$refs.observer.validate();
                        if(valid)
                        {
                              let update = await axios.patch(`hobby/update/${this.selectedId}`, this.hobby, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                              if(update){
                                    this.selectedId = null;
                                    this.editDialog = false;
                                    this.hobby.name = '';
                                    this.getAllData();
                                    this.$toast.open({
                                          message: `Hobby has been updated.`,
                                          type: "success",
                                          dissmissible: true,
                                          position: "top-right",
                                          duration: 5000,
                                    });
                              }
                        }
                  } catch (err){
                        this.selectedId = null;
                        this.editDialog = false;
                        this.hobby.name = '';
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
            }
        
      },
}
</script>
<style lang="scss">
    @import '../../assets/scss/table.scss';
</style>