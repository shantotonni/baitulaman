<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Program Schedule']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="createSchedule">
                      <i class="fas fa-plus"></i>
                      Add Schedule
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
                        <th>Name</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(schedule, i) in schedules"
                        :key="schedule.id"
                        v-if="schedules.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ schedule.name }}</td>
                      <td class="text-left">{{ schedule.day }}</td>
                      <td class="text-left">{{ schedule.time }}</td>
                      <td class="text-center">
                        <button @click="edit(schedule)" class="btn btn-success btn-sm">
                          <i
                              class="far fa-edit"></i></button>
                        <button @click="destroy(schedule.id)"
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
    <div class="modal fade" id="ScheduleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Schedule</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" >
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Schedule Name</label>
                      <input type="text" name="name" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                      <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Day</label>
                      <input type="text" name="day" v-model="form.day" class="form-control" :class="{ 'is-invalid': form.errors.has('day') }">
                      <div class="error" v-if="form.errors.has('day')" v-html="form.errors.get('day')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Time</label>
                      <input type="text" name="time" v-model="form.time" class="form-control" :class="{ 'is-invalid': form.errors.has('time') }">
                      <div class="error" v-if="form.errors.has('time')" v-html="form.errors.get('time')" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Schedule</button>
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
      schedules: [],
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
        day :'',
        time :'',
      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllSchedule();
      } else {
        this.searchData();
      }
    },
    AreaData: (val) => {
      this.form.body = $('.summernote').summernote("code", val);
    }
  },
  mounted() {
    document.title = 'Program Schedule List | Baitulaman';
    this.getAllSchedule();
  },
  methods: {
    getAllSchedule(){
      this.isLoading = true;
      axios.get(baseurl + 'api/program-schedule?page='+ this.pagination.current_page).then((response)=>{
        this.schedules = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/program-schedule/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.schedules = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllSchedule();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#ScheduleModal").modal("hide");
    },
    createSchedule(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#ScheduleModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/program-schedule").then(response => {
        $("#ScheduleModal").modal("hide");
        this.getAllSchedule();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(schedule) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(schedule);
      $("#ScheduleModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/program-schedule/" + this.form.id).then(response => {
        $("#ScheduleModal").modal("hide");
        this.getAllSchedule();
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
          axios.delete('api/program-schedule/' + id).then((response) => {
            this.getAllSchedule();
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
