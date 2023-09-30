<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Customer Events List']"/>
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
                      <th>Customer Name</th>
                      <th>Customer Email</th>
                      <th>Customer Phone</th>
                      <th>Customer Address</th>
                      <th>Event Title</th>
                      <th>Event Date</th>
<!--                      <th>Action</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(CE, i) in customer_events" :key="CE.id" v-if="customer_events.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ CE.customer_name }}</td>
                      <td class="text-left">{{ CE.customer_email }}</td>
                      <td class="text-left">{{ CE.customer_phone }}</td>
                      <td class="text-left">{{ CE.customer_address }}</td>
                      <td class="text-left">{{ CE.title }}</td>
                      <td class="text-left">{{ CE.event_date }}</td>
<!--                      <td class="text-center">-->
<!--                        <button @click="edit(CE)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>-->
<!--                        <button @click="destroy(CE.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>-->
<!--                      </td>-->
                    </tr>
                    </tbody>
                  </table>
                  <br>
                  <pagination
                      v-if="pagination.last_page > 1"
                      :pagination="pagination"
                      :offset="5"
                      @paginate="query === '' ? getAllCustomerEvents() : searchData()"
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
      customer_events: [],
      pagination: {
        current_page: 1
      },
      query: "",
      isLoading: false,
      form: new Form({
        id: '',
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        ages_of_children: '',
        ages_of_father: '',
        want_to_receive_email: '',
        customer_status: '',
      }),
    }
  },
  watch: {
    query: function (newQ, old) {
      if (newQ === "") {
        this.getAllCustomerEvents();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Customer Events List | Baitulaman';
    this.getAllCustomerEvents();
  },
  methods: {
    getAllCustomerEvents() {
      this.isLoading = true;
      axios.get(baseurl + 'api/get-all-customer-events?page=' + this.pagination.current_page).then((response) => {
        this.customer_events = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error) => {

      })
    },
    searchData() {
      axios.get(baseurl + "api/search/customers/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.customer_events = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload() {
      this.getAllCustomerEvents();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
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
          axios.delete('api/customers/' + id).then((response) => {
            this.getAllCustomerEvents();
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
