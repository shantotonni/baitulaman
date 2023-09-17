<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Advisory Board']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="datatable" v-if="!isLoading">
              <div class="card-body">
                <div class="d-flex">
                  <div class="flex-grow-1">
                    <div class="row">
                      <div class="col-md-2">
                        <input v-model="query" type="text" class="form-control" placeholder="Search">
                      </div>
                    </div>
                  </div>
                  <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" @click="createAdvisor">
                      <i class="fas fa-plus"></i>
                      Add Advisor
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
                        <th>Advisor Name</th>
                        <th>Designation</th>
                        <th>Address</th>
                        <th>Mobile NO</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(advisor, i) in advisors"
                        :key="advisor.id"
                        v-if="advisors.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ advisor.name }}</td>
                      <td class="text-left">{{ advisor.designation }}</td>
                      <td class="text-left">{{ advisor.address }}</td>
                      <td class="text-left">{{ advisor.mobile }}</td>
                      <td class="text-center">
                        <button @click="edit(advisor)" class="btn btn-success btn-sm">
                          <i
                              class="far fa-edit"></i></button>
                        <button @click="destroy(advisor.id)"
                                class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                        </button>
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
    <!--  Modal content for the above example -->
    <div class="modal fade" id="AdvisorModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Advisor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" >
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Advisor Name</label>
                      <input type="text" name="name" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                      <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Advisor Designation</label>
                      <input type="text" name="designation" v-model="form.designation" class="form-control" :class="{ 'is-invalid': form.errors.has('designation') }">
                      <div class="error" v-if="form.errors.has('designation')" v-html="form.errors.get('designation')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Address</label>
                      <input type="text" name="address" v-model="form.address" class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
                      <div class="error" v-if="form.errors.has('address')" v-html="form.errors.get('address')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Mobile</label>
                      <input type="text" name="mobile" v-model="form.mobile" class="form-control" :class="{ 'is-invalid': form.errors.has('mobile') }">
                      <div class="error" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" />
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Advisor</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {baseurl} from '../../base_url'
import {VueEditor} from "vue2-editor";

export default {
  data() {
    return {
      advisors: [],
      pagination: {
        current_page: 1
      },
      AreaData : '',
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id :'',
        name :'',
        designation :'',
        address :'',
        mobile :'',
      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllAdvisor();
      } else {
        this.searchData();
      }
    },
    AreaData: (val) => {
      this.form.body = $('.summernote').summernote("code", val);
    }
  },
  mounted() {
    document.title = 'Advisory Board List | Baitulaman';
    this.getAllAdvisor();
  },
  methods: {
    getAllAdvisor(){
      this.isLoading = true;
      axios.get(baseurl + 'api/advisors?page='+ this.pagination.current_page).then((response)=>{
        this.advisors = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/advisors/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.advisors = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllAdvisor();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#AdvisorModal").modal("hide");
    },
    createAdvisor(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#AdvisorModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/advisors").then(response => {
        $("#AdvisorModal").modal("hide");
        this.getAllAdvisor();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(advisor) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(advisor);
      $("#AdvisorModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/advisors/" + this.form.id).then(response => {
        $("#AdvisorModal").modal("hide");
        this.getAllAdvisor();
      }).catch(e => {
        this.isLoading = false;
      });
    },

    destroy(id) {
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
          axios.delete('api/advisors/' + id).then((response) => {
            this.getAllAdvisor();
            Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
            )
          })
        }
      })
    }
  },
}
</script>

<style scoped>

</style>
