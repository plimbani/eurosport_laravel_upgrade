<template>
    <div class="modal fade" id="user_profile">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-5 form-control-label">{{$lang.user_management_add_name}}</label>
                        <div class="col-sm-6">
                            <input  v-validate="'required|alpha'" v-model="userData.name" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control" placeholder="Your name">
                            <i v-show="errors.has('name')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 form-control-label">{{$lang.user_desktop_surname}}</label>
                        <div class="col-sm-6">
                            <input  v-validate="'required|alpha'" v-model="userData.surname" :class="{'is-danger': errors.has('surname') }" name="surname" type="text" class="form-control" placeholder="Enter second name">
                            <i v-show="errors.has('surname')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('surname')">{{ errors.first('surname') }}</span>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-sm-5 form-control-label">{{$lang.user_management_email}}</label>
                        <div class="col-sm-6">
                            
                            <input v-model="userData.emailAddress" v-validate="'required|email'" :class="{'is-danger': errors.has('email_address') }" name="email_address" type="email" class="form-control" placeholder="Enter email address">
                            <i v-show="errors.has('email_address')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('email_address')">{{ errors.first('email_address') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-5 control-label">{{$lang.user_management_image}}</label>
                        <div class="col-sm-6">
                            <div v-if="!image">
                             <img v-if="userData.image" v-bind:src="'/assets/img/users/' + userData.image" width="60px" height="60px"/>
                            <img v-else src="http://placehold.it/60x60" width="60px" height="60px"/>
                                    <button type="button" name="btnImage" id="btnImage">Choose file</button>
                                    <input type="file" id="selectFile" style="display:none;" @change="onFileChange">
                                    <p class="help-block">Maximum size of 1 MB.</p>
                            </div>
                            <div v-else>
                                <img :src="image" width="60px" height="60px"/>
                                <button @click="removeImage">Remove image</button>
                            </div>
                        </div>      
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="updateUser()">
                        {{$lang.user_management_user_save}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/babel">
   export default {
   data() {
        return {
         userId:this.$store.state.Users.userDetails.id? this.$store.state.Users.userDetails.id : 1,
        'image': '',
        'name': ''
        }
    },
    mounted(){
        $('#btnImage').on('click',function(){
        $('#selectFile').trigger('click')
    })
    },
    props: ['userData'],
    methods : {
        updateUser(){
        let that = this;
        this.userData.user_image = this.image;
             axios.post("/api/user/update/"+this.userId,this.userData).then((response) => {
                toastr.success('User has been updated successfully.', 'Update User', {timeOut: 5000});
                $("#user_profile").modal("hide");
            });
        },
             onFileChange(e) {        
              var files = e.target.files || e.dataTransfer.files;
              if (!files.length)
                return;
              this.createImage(files[0]);
            },  
            createImage(file) {
              var image = new Image();
              var reader = new FileReader();
              var vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
              };
              reader.readAsDataURL(file);
            },
            removeImage: function (e) {
              this.image = '';
               e.preventDefault();
            },      
   }
}
</script>