<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edte">

    <!-- jQuery  -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"> </script>
    
    <!-- Vue JS-->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
   
   <!-- Axios Fungsi Ajax j Query-->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        .todolist-wrapper{
            border: 1px solid #cccccc;
            min-height: 100px;
        }
    </style>
    
    <title>Todolist Page</title>
    
  </head>
  <body>
        <div class="container"> 
           <div id="app">
               
            <div class="modal" id="modal-form">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Todo List Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                           <div class="form-group"> 
                                <label for="">Content</label>
                                <textarea v-model="content" class="form-control" rows="5"></textarea>
                            </div> 
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" href="javascript:;" @click="saveTodolist" class="btn btn-primary">Save Todolist</button>
                        </div>
                    </div>
                </div>
            </div>



               <div class="row">
                 <div class="col-sm-3"></div>
                 <div class="col-sm-6"> 
                    <div class="text-right mb-3">
                    <a href="javascript:;" @click="openForm" class="btn btn-primary">Tambah Todolist</a>                    
                    </div>

                    <div class="text-center mb-3">
                       <input type="text" v-model="search" @change="findData"  placeholder="Cari Data" class="form-control">  
                    </div>
              
                    <div class="todolist-wrapper">
                        <table class="table table-striped table-borderd">
                            <th color="red">Content </th>
                            <tbody> 
                                <tr v-for="item in data_list">
                                    <td> @{{ item.content }} 
                                    <a href="javascript:;" @click="editData(item.id)" class="btn btn-primary" >Edit </a> 
                                    <a href="javascript:;" @click="deleteData(item.id)" class="btn btn-danger" >Delete </a> 
                                    </td> 
                                </tr>
                                <tr v-if="!data_list.length"> 
                                   <td>Data Masih Kosong</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-3"></div>              
        </div>
        
        <script>
                var vue= new Vue({
                    el: "#app",

                    mounted() {
                        this.getDataList();

                    },
                    data: {
                        data_list: [], 
                        content: "",
                        id: "",
                        search: ""               

                    },
                    methods: {
                        findData: function(){
                            this.getDataList();   
                        },

                        openForm: function() {
                           $('#modal-form').modal('show');
                        },

                        deleteData: function(id){
                           if(confirm('Apakah Data ini Akan di hapus ?')) { 

                           axios.post("{{ url('api/todolist/delete') }}/"+ id) 
                                .then(resp=>{
                                    alert(resp.data.message);    
                                    this.getDataList();   
                                })
                                .catch(err=>{
                                    alert("Terjadi Kesalahan Sistem "+ err);
                                })
                           }  
                        },

                        editData: function(id) {
                           this.id = id;     
                           axios.get("{{ url('api/todolist/read') }}/"+ this.id)
                                .then(resp=>{
                                    var item= resp.data;
                                    this.content = item.content;
                                    
                                    $('#modal-form').modal('show');
                                })
                                .catch(err=>{
                                    alert("Terjadi Kesalahan Pada Sistem");
                                })                                
                                
                        },             

                        saveTodolist: function() {
                           var form_data = new FormData();
                           form_data.append("content", this.content);   
 

                           if(this.id){ // jika id nya ada maka
                            axios.post("{{ url('api/todolist/update') }}/" + this.id, form_data)
                                    .then(resp=>{
                                        var item = resp.data;
                                        alert(item.message);  
                                        this.getDataList();                                      
                                    }                                
                                    )
                                    .catch(err=>{
                                        alert("Terjadi Kesalahan pada Sistem");
                                    }
                                    )
                                    .finally(()=>{
                                        $('#modal-form').modal('hide');
                                    })
                           } else {
                                axios.post("{{ url('api/todolist/create') }}",form_data)
                                    .then(resp=>{
                                        var item = resp.data;
                                        alert(item.message);  
                                        this.getDataList();                                      
                                    }                                
                                    )
                                    .catch(err=>{
                                        alert("Terjadi Kesalahan pada Sistem");
                                    }
                                    )
                                    .finally(()=>{
                                        $('#modal-form').modal('hide');
                                    })
                           }

                           
                        } ,

                        getDataList: function() {
                            axios.get("{{ url('api/todolist/list') }}?search=" + this.search)
                                 .then(resp=>{
                                     this.data_list = resp.data
                                 })
                                 .catch(err=>{
                                    alert("Terjadi Kesalahan pada Sistem");                                     
                                 })
                        }
                         
                    }
                });
        </script>
        
  </body>


</html>