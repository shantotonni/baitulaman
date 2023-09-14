<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Slider Details']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="datatable" v-if="!isLoading">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                    <tr>
                      <th>Description</th>
                      <th>Link</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td class="text-center" v-html="sliders.paragraph"></td>
                      <td class="text-left">{{ sliders.link }}</td>
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
      sliders: [],
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
        this.getSliderDetails();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Slider Details | Baitulaman';
    this.getSliderDetails();
  },
  created(){
    this.getSliderDetails();
  },
  methods: {
    getSliderDetails(){
      this.isLoading = true;
      axios.get(`/api/slider-details/${this.$route.params.id}`).then((response)=>{
        console.log(response);
        this.sliders = response.data.data
        this.isLoading = false;
      }).catch((error)=>{

      })
    },


    reload(){
      this.query = "";
      this.getSliderDetails();
      this.$toaster.success('Data Successfully Refresh');
    },

  },
}
</script>

<style lang="scss" scoped>

</style>
