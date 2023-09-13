<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Imam Details']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="datatable" v-if="!isLoading">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                    <tr>
                      <th class="text-center">Description</th>
                      <th class="text-center">Experience</th>
                      <th class="text-center">Educational Qualification</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td class="text-center" v-html="imams.description"></td>
                      <td class="text-center" v-html="imams.experience"></td>
                      <td class="text-center" v-html="imams.educational_qualification"></td>
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
  </div>
</template>
<script>
export default {
    name: "Details",
    data() {
      return {
        imams: [],
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
        this.getPageDetails();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Imam Details | Baitulaman';
    this.getImamDetails();
  },
  created(){
     this.getImamDetails();
  },
    methods: {
      getImamDetails(){
            this.isLoading = true;
            axios.get(`/api/imam-details/${this.$route.params.id}`).then((response)=>{
                console.log(response);
                this.imams = response.data.data
                this.isLoading = false;
            }).catch((error)=>{

            })
        },
        reload(){
            this.query = "";
            this.getPageDetails();
            this.$toaster.success('Data Successfully Refresh');
        },

    },
}
</script>

<style lang="scss" scoped>

</style>
