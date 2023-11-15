<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Question']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="datatable" v-if="!isLoading">
              <div class="card-body">
                <div class="d-flex">
<!--                  <div class="flex-grow-1">-->
<!--                    <div class="row">-->
<!--                      <div class="col-md-2">-->
<!--                        <input v-model="query" type="text" class="form-control" placeholder="Search">-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
                  <div class="card-tools">
<!--                    <button type="button" class="btn btn-success btn-sm" @click="createVolunteer">-->
<!--                      <i class="fas fa-plus"></i>-->
<!--                      Add Volunteer-->
<!--                    </button>-->
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
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(question, i) in questions" :key="question.id" v-if="questions.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ question.name }}</td>
                      <td class="text-left">{{ question.email }}</td>
                      <td class="text-left">{{ question.message }}</td>
                      <td class="text-left">
                        <span v-if="question.status ==='pending'" class="badge badge-danger">Pending</span>
                        <span v-if="question.status ==='done'" class="badge badge-danger">Done</span>
                      </td>
                      <td class="text-center">
                        <button @click="reply(question.id)" class="btn btn-success btn-sm" type="button">Reply</button>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <br>
                  <pagination
                      v-if="pagination.last_page > 1"
                      :pagination="pagination"
                      :offset="5"
                      @paginate="query === '' ? getAllQuestion() : searchData()"
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
    <!--  Modal content for the above example -->
    <div class="modal fade" id="VolunteerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel"> Reply</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="replyStore()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" name="title" v-model="form.title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                      <div class="error" v-if="form.errors.has('title')" v-html="form.errors.get('title')" />
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Description</label>
                        <vue-editor name="message" v-model="form.message" :class="{ 'is-invalid': form.errors.has('message') }"></vue-editor>
                        <div class="error" v-if="form.errors.has('message')" v-html="form.errors.get('message')"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Reply</button>
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
  components: {
    VueEditor
  },
  data() {
    return {
      questions: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: "",
      isLoading: false,
      form: new Form({
        id :'',
        title :'',
        message :'',
        QuestionId: ''
      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllQuestion();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Question List | Baitulaman';
    this.getAllQuestion();
  },
  methods: {
    getAllQuestion(){
      this.isLoading = true;
      axios.get(baseurl + 'api/questions?page='+ this.pagination.current_page).then((response)=>{
        this.questions = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/questions/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.questions = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllQuestion();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#VolunteerModal").modal("hide");
    },
    createVolunteer(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#VolunteerModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/questions").then(response => {
        $("#VolunteerModal").modal("hide");
        this.getAllQuestion();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(volunteer) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(volunteer);
      $("#VolunteerModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/questions/" + this.form.id).then(response => {
        $("#VolunteerModal").modal("hide");
        this.getAllQuestion();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reply(id){
      this.form.reset();
      this.form.clear();
      this.form.QuestionId = id;
      $("#VolunteerModal").modal("show");
    },
    replyStore(){
      this.form.busy = true;
      this.form.post(baseurl + "api/question-reply").then(response => {
        //$("#VolunteerModal").modal("hide");
        this.getAllQuestion();
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
          axios.delete('api/questions/' + id).then((response) => {
            this.getAllQuestion();
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
