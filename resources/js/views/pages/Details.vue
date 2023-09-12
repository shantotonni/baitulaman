<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Details']"/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane p-3 active" id="home-1" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                              <tr>
                                                <td class="text-left">{{ pages.title }}</td>
                                                <td class="text-left">{{ pages.slug }}</td>
                                                <td class="text-left" v-html="pages.body "></td>
                                              </tr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

export default {
    name: "List",
    data() {
      return {
        pages: [],
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
    document.title = 'Page Details | Baitulaman';
    this.getPageDetails();
  },
    methods: {
        getPageDetails(){
            this.isLoading = true;
            axios.get(`/api/page-details/${this.$route.params.id}`).then((response)=>{
                console.log(response);
                this.pages = response.data.data
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
