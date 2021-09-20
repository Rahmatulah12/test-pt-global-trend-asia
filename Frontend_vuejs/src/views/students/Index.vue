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
                    Student
                </v-card-title>
                <v-card-subtitle>
                    List Student
                </v-card-subtitle>

                <v-divider class="mt-1 mb-5 mx-3"></v-divider>

                <div class="d-flex flex-row-reverse mr-5 mb-3">
                    <v-btn
                        color="primary"
                        small
                        @click="$router.push('/add-student')"
                    >
                        Add Student
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
                                        Email
                                    </th>
                                    <th class="text-justify">
                                        Phone
                                    </th>
                                    <th class="text-justify">
                                        Address
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
                                        {{ ucwords(item.email) }}
                                    </td>
                                    <td class="text-justify">
                                        {{ ucwords(item.phone) }}
                                    </td>
                                    <td class="text-justify">
                                        {{ ucwords(item.address) }}
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
                                                    @click="$router.push(`edit-student/${item.id}`)"
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

    </v-row>
</template>

<script>
import axios from '../../api';
  export default {
    name: 'Student',
    metaInfo: {
        // if no subcomponents specify a metaInfo.title, this title will be used
        title: 'Test PT. Kencana Konsep',
        // all titles will be injected into this template
        titleTemplate: '%s | Student'
    },
    mounted(){
        this.page = 1;
        this.getAllData();
    },
    data () {
        return {
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
        }
    },
    methods: {
        async getAllData(){
            try{
                let url = this.keyword != null && this.keyword.length > 0 ? 
                        `student?keyword=${this.keyword}&size=${this.size}&page=${this.page}` :
                        `student?size=${this.size}&page=${this.page}`
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
                let data = axios.delete(`student/${id}`, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
                if(data)
                {
                    console.log(JSON.stringify(data));
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
        }
        
    },
  }
</script>
<style lang="scss">
    @import '../../assets/scss/table.scss';
</style>