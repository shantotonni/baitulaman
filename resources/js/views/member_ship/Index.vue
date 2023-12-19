<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Membership']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="exportMemberShip">
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
                      <th>Phone</th>
                      <th>Age</th>
                      <th>Gender</th>
                      <th>Address</th>
                      <th>Date of Birth</th>
                      <th>Father name</th>
                      <th>Father Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(member, i) in membership" :key="member.id" v-if="membership.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ member.name }}</td>
                      <td class="text-left">{{ member.email }}</td>
                      <td class="text-left">{{ member.phone }}</td>
                      <td class="text-left">{{ member.age }}</td>
                      <td class="text-left">{{ member.gender }}</td>
                      <td class="text-left">{{ member.address }}</td>
                      <td class="text-left">{{ member.date_of_birth }}</td>
                      <td class="text-left">{{ member.father_name }}</td>
                      <td class="text-left">{{ member.father_email }}</td>
                    </tr>
                    </tbody>
                  </table>
                  <br>
                  <pagination
                      v-if="pagination.last_page > 1"
                      :pagination="pagination"
                      :offset="5"
                      @paginate="query === '' ? getAllMailing() : searchData()"
                  ></pagination>
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
import {VueEditor} from "vue2-editor";
import {bus} from "../../app";

export default {
  components: {
    VueEditor
  },
  data() {
    return {
      membership: [],
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
        this.getAllMailing();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Membership List | Baitulaman';
    this.getAllMailing();
  },
  methods: {
    getAllMailing() {
      this.isLoading = true;
      axios.get(baseurl + 'api/membership?page=' + this.pagination.current_page).then((response) => {
        console.log(response)
        this.membership = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error) => {

      })
    },
    searchData() {
      axios.get(baseurl + "api/search/mailing/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.membership = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    exportMemberShip(){
      axios.get(baseurl + 'api/export-member-ship')
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
              bus.$emit('data-table-import', dataSets, columns, 'Member List')
            }
          }).catch((error)=>{
      })
    }
  }
}
</script>

<style scoped>

</style>
