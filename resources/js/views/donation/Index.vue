<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Donation List']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="exportDonate">
                      <i class="fas fa-plus"></i>
                      Export
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" @click="reload">
                      <i class="fas fa-sync"></i>
                      Reload
                    </button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table
                      class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                      <tr>
                        <th>SN</th>
                        <th>Donner Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Purpose</th>
                        <th>Payment ID</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(donate, i) in donations" :key="donate.id" v-if="donations.length">
                        <th class="text-center" scope="row">{{ ++i }}</th>
                        <td class="text-center">{{ donate.name }}</td>
                        <td class="text-center">{{ donate.email }}</td>
                        <td class="text-center">{{ donate.mobile }}</td>
                        <td class="text-center">{{ donate.purpose }}</td>
                        <td class="text-center">{{ donate.payment_id }}</td>
                        <td class="text-right">{{ donate.amount }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <br>
                  <pagination
                      v-if="pagination.last_page > 1"
                      :pagination="pagination"
                      :offset="5"
                      @paginate="query === '' ? getAllDonation() : searchData()"
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
import {bus} from "../../app";
import {baseurl} from '../../base_url'

export default {
  data() {
    return {
      donations: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
    }
  },
  watch: {
    query: function (newQ, old) {
      if (newQ === "") {
        this.getAllDonation();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Donation List | Baitulaman';
    this.getAllDonation();
  },
  methods: {
    getAllDonation() {
      this.isLoading = true;
      axios.get(baseurl + 'api/donation-list?page=' + this.pagination.current_page).then((response) => {
        this.donations = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error) => {

      })
    },
    searchData() {
      axios.get(baseurl + "api/search/donation/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.donations = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload() {
      this.getAllDonation();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    exportDonate(){
      axios.get(baseurl + 'api/export-donation')
          .then((response)=>{
            let dataSets = response.data.data;
            console.log(dataSets)
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
    },
  },
}
</script>

<style scoped>

</style>
