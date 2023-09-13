<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Imam List']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="createImam">
                      <i class="fas fa-plus"></i>
                      Add Imam
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
                      <th>Description</th>
                      <th>Educational Qualification</th>
                      <th>Experience</th>
                      <th>Status</th>
                      <th>Image</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(imam, i) in imams"
                        :key="imam.id"
                        v-if="imams.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ imam.name }}</td>
                      <td class="text-right">{{ imam.mobile }}</td>
                      <td class="text-left">{{ imam.email }}</td>
                      <td class="text-left">{{ imam.address }}</td>
                      <td class="text-left" style="width: 30%"  v-html="imam.description "></td>
                      <td class="text-left">{{ imam.educational_qualification }}</td>
                      <td class="text-left">{{ imam.experience }}</td>
                      <td class="text-left">{{ imam.status }}</td>
                      <td class="text-center">
                        <img v-if="imam.image" height="40" width="40"
                             :src="tableImage(imam.image)" alt="">
                      </td>
                      <td class="text-center">
                        <button @click="edit(imam)" class="btn btn-success btn-sm">
                          <i
                              class="far fa-edit"></i></button>
                        <button @click="destroy(imam.id)"
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
    <div class="modal fade" id="ImamModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Imam</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Imam Name</label>
                      <input type="text" name="name" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                      <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                    </div>
                  </div>  <div class="col-md-6">
                    <div class="form-group">
                      <label>Mobile</label>
                      <input type="number" name="mobile" v-model="form.mobile" class="form-control" :class="{ 'is-invalid': form.errors.has('mobile') }">
                      <div class="error" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" />
                    </div>
                  </div>  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" name="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                      <div class="error" v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                    </div>
                  </div>  <div class="col-md-6">
                    <div class="form-group">
                      <label>Address</label>
                      <input type="text" name="address" v-model="form.address" class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
                      <div class="error" v-if="form.errors.has('address')" v-html="form.errors.get('address')" />
                    </div>
                  </div>  <div class="col-md-6">
                    <div class="form-group">
                      <label>Educational Qualification</label>
                      <input type="text" name="educational_qualification" v-model="form.educational_qualification" class="form-control" :class="{ 'is-invalid': form.errors.has('educational_qualification') }">
                      <div class="error" v-if="form.errors.has('educational_qualification')" v-html="form.errors.get('educational_qualification')" />
                    </div>
                  </div>  <div class="col-md-6">
                    <div class="form-group">
                      <label>Experience </label>
                      <input type="text" name="experience" v-model="form.experience" class="form-control" :class="{ 'is-invalid': form.errors.has('experience') }">
                      <div class="error" v-if="form.errors.has('experience')" v-html="form.errors.get('experience')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <select type="Status" name="status" v-model="form.status"
                              class="form-control"
                              :class="{ 'is-invalid': form.errors.has('status') }">
                        <option disabled value="">Select Status</option>
                        <option >
                          Active
                        </option>
                        <option >
                          Inactive
                        </option>
                      </select>
                      <div class="error" v-if="form.errors.has('status')"
                           v-html="form.errors.get('status')"/>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image <small>(Image type:jpeg,jpg,png,svg)</small></label>
                      <input @change="changeImage($event)" type="file" name="image"
                             class="form-control"
                             :class="{ 'is-invalid': form.errors.has('image') }">
                      <div class="error" v-if="form.errors.has('image')"
                           v-html="form.errors.get('image')"/>
                      <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px"
                           width="40px">
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
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Imam</button>
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
      imams: [],
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
        this.getAllImam();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Our Imam List | Baitulaman';
    this.getAllImam();
  },
  methods: {
    getAllImam(){
      this.isLoading = true;
      axios.get(baseurl + 'api/imams?page='+ this.pagination.current_page).then((response)=>{
        this.imams = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/imams/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.imams = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllImam();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#ImamModal").modal("hide");
    },
    createImam(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#ImamModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/imams").then(response => {
        $("#ImamModal").modal("hide");
        this.getAllImam();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(imam) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(imam);
      $("#ImamModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/imams/" + this.form.id).then(response => {
        $("#ImamModal").modal("hide");
        this.getAllImam();
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
        return window.location.origin + "/images/imam/" + this.form.image;
      }
    },
    tableImage(image) {
      return window.location.origin + "/images/imam/" + image;
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
          axios.delete('api/imams/' + id).then((response) => {
            this.getAllImam();
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
