<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Backup List']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="store">
                      <i class="fas fa-plus"></i>
                      Add Backup
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" @click="reload">
                      <i class="fas fa-sync"></i>
                      Reload
                    </button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                      <tr>
                        <th>SN</th>
                        <th> File Name</th>
                        <th>Size</th>
                        <th>Created_at</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(backup, i) in backups" :key="backup.backup_id" v-if="backups.length">
                        <th scope="row">{{ ++i }}</th>
                        <td>{{ backup.file_name }}</td>
                        <td>{{ backup.file_size }}</td>
                        <td>{{ backup.created_at }}</td>
                        <td>
                          <button @click="download(backup.file_name)" class="btn btn-success btn-sm"><i class="fas fa-download"></i></button>
                          <button @click="destroy(backup.file_name)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
<!--                <div class="row">-->
<!--                  <div class="col-4">-->
<!--                    <div class="data-count">-->
<!--                      Show {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} rows-->
<!--                    </div>-->
<!--                  </div>-->
<!--                  <div class="col-8">-->
<!--                    <pagination-->
<!--                        v-if="pagination.last_page > 1"-->
<!--                        :pagination="pagination"-->
<!--                        :offset="5"-->
<!--                        @paginate="query === '' ? getAllBackup() : searchData()"-->
<!--                    ></pagination>-->
<!--                  </div>-->
<!--                </div>-->
              </div>
            </div>
            <div v-else>
              <skeleton-loader :row="14"/>
            </div>
          </div>
        </div>
      </div>
    </div>
<!--    <div class="modal fade" id="BackupModelModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">-->
<!--      <div class="modal-dialog modal-lg">-->
<!--        <div class="modal-content">-->
<!--          <div class="modal-header">-->
<!--            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Backup</h5>-->
<!--            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>-->
<!--          </div>-->
<!--          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">-->

<!--          </form>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import moment from "moment";
import {baseurl} from '../../base_url'
export default {
  name: "List",
  components: {
    Datepicker
  },
  data() {
    return {
      backups: [],
      pagination: {
        current_page: 1,
        from: 1,
        to: 1,
        total: 1,
      },
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        created_at :'',
        file_size :'',
        file_name :'',

      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllBackup();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Backup List | Baitulaman';
    this.getAllBackup();
  },
  methods: {
    getAllBackup(){
      axios.get(baseurl+ 'api/backups?page='+ this.pagination.current_page
      ).then((response)=>{
        this.backups = response.data.data;
        this.pagination = response.data.meta;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl+"api/search/backups/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.backups = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllBackup();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#BackupModelModal").modal("hide");
    },
    download(file_name) {
      this.form.post(baseurl+'api/backups/download/'+ file_name)

    },
// createBackup(){
//   this.editMode = false;
//   this.form.reset();
//   this.form.clear();
//   $("#BackupModelModal").modal("show");
// },
    store(){
      this.form.post(baseurl+ "api/backups").then(response => {
        console.log(response)
        this.getAllBackup();
        this.$toaster.success('backup Added');
      })
    },

    update(){
      this.form.busy = true;
      this.form.put(baseurl+"api/backups/" + this.form.backup_id).then(response => {
        $("#BackupModelModal").modal("hide");
        this.getAllBackup();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    destroy(file_name){
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(baseurl+'api/backups/'+ file_name).then((response)=>{
            this.getAllBackup();
            Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
            )
          })
        }
      })
    },
  },
}
</script>

<style scoped>

</style>
