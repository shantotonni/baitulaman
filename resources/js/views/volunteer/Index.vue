<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Volunteer']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="datatable" v-if="!isLoading">
              <div class="card-body">
                <div class="d-flex">
                  <div class="flex-grow-1">
                    <div class="row">
                      <div class="col-md-2">
<!--                        <input v-model="query" type="text" class="form-control" placeholder="Search">-->
                      </div>
                    </div>
                  </div>
                  <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" @click="exportVolunteer">
                      <i class="fas fa-plus"></i>
                      Export
                    </button>
<!--                    <button type="button" class="btn btn-primary btn-sm" @click="reload">-->
<!--                      <i class="fas fa-sync"></i>-->
<!--                      Reload-->
<!--                    </button>-->
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                    <tr>
                      <th>SN</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Message</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(volunteer, i) in volunteers" :key="volunteer.id" v-if="volunteers.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ volunteer.name }}</td>
                      <td class="text-left">{{ volunteer.email }}</td>
                      <td class="text-left">{{ volunteer.message }}</td>
                      <td class="text-center">
                        <button @click="edit(volunteer)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                        <button @click="destroy(volunteer.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <br>

                </div>
              </div>
            </div>
            <div v-else>
              <skeleton-loader :row="14"/>
            </div>
          </div>
        </div>
      </div>
    </div>
    <data-export/>
  </div>
</template>

<script>
import {baseurl} from '../../base_url'
import {bus} from "../../app";

export default {
  data() {
    return {
      volunteers: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllVolunteer();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Our Volunteer List | Baitulaman';
    this.getAllVolunteer();
  },
  methods: {
    getAllVolunteer(){
      this.isLoading = true;
      axios.get(baseurl + 'api/volunteers?page='+ this.pagination.current_page).then((response)=>{
        this.volunteers = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/volunteers/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.volunteers = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    exportVolunteer(){
      axios.get(baseurl + 'api/export-volunteer')
          .then((response)=>{
            let dataSets = response.data.data;
            if (dataSets.length > 0) {
              let columns = Object.keys(dataSets[0]);
              columns = columns.filter((item) => item !== 'row_num');
              let rex = /([A-Z])([A-Z])([a-z])|([a-z])([A-Z])/g;
              columns = columns.map((item) => {
                let title = item.replace(rex, '$1$4 $2$3$5')
                return {title, key: item}
              });
              bus.$emit('data-table-import', dataSets, columns, 'Customer List')
            }
          }).catch((error)=>{
      })
    }
  },
}
</script>

<style scoped>

</style>
