<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">ตารางแสดงข้อมูลตัวชี้วัด</h3>

                <div class="card-tools">

                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      สร้างตัวชี้วัด
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>รหัสตัวชี้วัด</th>
                      <th>ชื่อตัวชี้วัด</th>
                      <th>สถานะอนุมัติการสร้าง</th>
                      <th>ปีงบประมาณ</th>
                      <th>อัพเดท</th>
                      <th>การกระทำ</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="kpi in kpis.data" :key="kpi.id">

                      <td>{{kpi.kpi_no}}</td>
                      <td>{{kpi.kpi_title | truncate(30, '...')}}</td>
                      <td>{{kpi.approve_status.approve_status_th}}</td>
                      <td>{{dateTime2(kpi.budget_year)}}</td>
                      <td>{{dateTime(kpi.updated_at)}}</td>
                      <td>

                        <a href="#" @click="editModal(kpi)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteProduct(kpi.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="kpis" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">แบบฟอร์มสร้างตัวชี้วัด</h5>
                    <h5 class="modal-title" v-show="editmode">แก้ไขตัวชี้วัด</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateKpi() : createKpi()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>รหัสตัวชี้วัด</label>
                            <input v-model="form.kpi_no" type="text" name="kpi_no"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('kpi_no') }">
                            <has-error :form="form" field="kpi_no"></has-error>
                        </div>
                        <div class="form-group">
                            <label>ชื่อตัวชี้วัด</label>
                            <input v-model="form.kpi_title" type="text" name="kpi_title"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('kpi_title') }">
                            <has-error :form="form" field="kpi_title"></has-error>
                        </div>
                        <div class="form-group">
                            <label>สถานะอนุมัติการสร้าง</label>
                            <select class="form-control" v-model="form.approve_status_id">
                              <option
                                  v-for="(astatus,index) in approvestatus" :key="index"
                                  :value="index"
                                  :selected="index == form.approve_status_id">{{ astatus }}</option>
                            </select>
                            <has-error :form="form" field="approve_status_id"></has-error>
                        </div>
                        <div class="form-group">
                            <label>ปีงบประมาณ</label>
                            <input v-model="form.budget_year" type="text" name="budget_year"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('budget_year') }">
                            <has-error :form="form" field="budget_year"></has-error>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';
    import moment from 'moment';
    moment.locale('th');

    export default {
      components: {
          VueTagsInput,
        },
        data () {
            return {
                editmode: false,
                kpis : {},
                form: new Form({
                    id : '',
                    kpi_no : '',
                    kpi_title: '',
                    approve_status_id: '',
                    budget_year:  ''
                }),
                approvestatus: [],
                autocompleteItems: [],
            }
        },
        methods: {
          dateTime(value) {
            return moment(value).add(543, 'year').format('LLL');
          },
          dateTime2(value) {
            return moment(value).add(543, 'year').format('YYYY');
          },
          getResults(page = 1) {

              this.$Progress.start();

              axios.get('api/kpi?page=' + page).then(({ data }) => (this.kpis = data.data));

              this.$Progress.finish();
          },
          loadKpis(){

            // if(this.$gate.isAdmin()){
              axios.get("api/kpi").then(({ data }) => (this.kpis = data.data));
            // }
          },
            loadApproveStatus(){
              axios.get("/api/kpi/list").then(({ data }) => (this.approvestatus = data.data));
          },
        //   loadCategories(){
        //       axios.get("/api/category/list").then(({ data }) => (this.categories = data.data));
        //   },
        //   loadTags(){
        //       axios.get("/api/tag/list").then(response => {
        //           this.autocompleteItems = response.data.data.map(a => {
        //               return { text: a.name, id: a.id };
        //           });
        //       }).catch(() => console.warn('Oh. Something went wrong'));
        //   },
          editModal(kpi){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(kpi);
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },
          createKpi(){
              this.$Progress.start(); // ตัวแท็ปโหลด

              this.form.post('api/kpi')
              .then((data)=>{
                if(data.data.success){
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                  this.$Progress.finish();
                  this.loadKpis();

                } else {
                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });

                  this.$Progress.failed();
                }
              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          },
          updateKpi(){
              this.$Progress.start();
              this.form.put('api/kpi/'+this.form.id)
              .then((response) => {
                  // success
                  $('#addNew').modal('hide');
                  Toast.fire({
                    icon: 'success',
                    title: response.data.message
                  });
                  this.$Progress.finish();
                      //  Fire.$emit('AfterCreate');

                  this.loadKpis();
              })
              .catch(() => {
                  this.$Progress.fail();
              });

          },
          deleteProduct(id){
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.form.delete('api/product/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadKpis();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },

        },
        mounted() {

        },
        created() {
            this.$Progress.start();

            this.loadKpis();
            this.loadApproveStatus();

            this.$Progress.finish();
        },
        filters: {
            truncate: function (text, length, suffix) {
                return text.substring(0, length) + suffix;
            },
        },
        computed: {
          filteredItems() {
            return this.autocompleteItems.filter(i => {
              return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
            });
          },
        },
    }
</script>
