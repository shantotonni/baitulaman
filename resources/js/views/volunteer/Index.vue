<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Volunteer List']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="createVolunteer">
                      <i class="fas fa-plus"></i>
                      Add Volunteer
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
                      <th>Mobile</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>Image</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(volunteer, i) in volunteers"
                        :key="volunteer.id"
                        v-if="volunteers.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ volunteer.name }}</td>
                      <td class="text-right">{{ volunteer.mobile }}</td>
                      <td class="text-left">{{ volunteer.email }}</td>
                      <td class="text-left">{{ volunteer.address }}</td>

                      <td class="text-center">
                        <img v-if="volunteer.image" height="40" width="40"
                             :src="tableImage(volunteer.image)" alt="">
                      </td>
                      <td class="text-left">{{ volunteer.status }}</td>
                      <td class="text-center">
                        <router-link :to="`volunteer-details/${volunteer.id}`" class="btn btn-primary btn-sm btn-xs"><i class="far fa-eye"></i></router-link>
                        <button @click="edit(volunteer)" class="btn btn-success btn-sm">
                          <i class="far fa-edit"></i></button>
                        <button @click="destroy(volunteer.id)"
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
    <div class="modal fade" id="VolunteerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Volunteer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Volunteer Name</label>
                      <input type="text" name="name" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                      <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Mobile</label>
                      <input type="number" name="mobile" v-model="form.mobile" class="form-control" :class="{ 'is-invalid': form.errors.has('mobile') }">
                      <div class="error" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" name="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                      <div class="error" v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Address</label>
                      <input type="text" name="address" v-model="form.address" class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
                      <div class="error" v-if="form.errors.has('address')" v-html="form.errors.get('address')" />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Status</label>
                      <select type="Status" name="status" v-model="form.status" class="form-control" :class="{ 'is-invalid': form.errors.has('status') }">
                        <option disabled value="">Select Status</option>
                        <option >Active</option>
                        <option >Inactive</option>
                      </select>
                      <div class="error" v-if="form.errors.has('status')" v-html="form.errors.get('status')"/>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Image <small>(Image type:jpeg,jpg,png,svg)</small></label>
                      <input @change="changeImage($event)" type="file" name="image" class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                      <div class="error" v-if="form.errors.has('image')" v-html="form.errors.get('image')"/>
                      <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px" width="40px">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Description</label>
                        <vue-editor name="description" v-model="form.description" :class="{ 'is-invalid': form.errors.has('description') }"></vue-editor>
                        <div class="error" v-if="form.errors.has('description')" v-html="form.errors.get('description')"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Educational Qualification</label>
                        <vue-editor name="educational_qualification" v-model="form.educational_qualification" :class="{ 'is-invalid': form.errors.has('educational_qualification') }"></vue-editor>
                        <div class="error" v-if="form.errors.has('educational_qualification')" v-html="form.errors.get('educational_qualification')"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Experience</label>
                        <vue-editor name="experience" v-model="form.experience" :class="{ 'is-invalid': form.errors.has('experience') }"></vue-editor>
                        <div class="error" v-if="form.errors.has('experience')" v-html="form.errors.get('experience')"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Volunteer</button>
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
      Volunteers: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id :'',
        name :'',
        mobile :'',
        email :'',
        address :'',
        description :'',
        image :'',
        status :'',
        educational_qualification :'',
        experience :'',
      }),
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
    reload(){
      this.getAllVolunteer();
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
      this.form.post(baseurl + "api/volunteers").then(response => {
        $("#VolunteerModal").modal("hide");
        this.getAllVolunteer();
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
      this.form.put(baseurl + "api/volunteers/" + this.form.id).then(response => {
        $("#VolunteerModal").modal("hide");
        this.getAllVolunteer();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    changeImage(event) {
      let file = event.target.files[0];
      let reader = new FileReader();
      reader.onload = event => {
        this.form.image = event.target.result;
      };
      reader.readAsDataURL(file);
    },
    showImage() {
      let img = this.form.image;
      if (img.length > 100) {
        return this.form.image;
      } else {
        return window.location.origin + "/images/volunteer/" + this.form.image;
      }
    },
    tableImage(image) {
      return window.location.origin + "/images/volunteer/" + image;
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
          axios.delete('api/volunteers/' + id).then((response) => {
            this.getAllVolunteer();
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
