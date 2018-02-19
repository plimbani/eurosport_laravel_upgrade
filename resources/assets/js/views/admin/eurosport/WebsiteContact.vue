<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_contact}}</strong></h6>
        <form name="frm_website_contact" enctype="multipart/form-data">
          <div class="row justify-content-between">
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-sm-2 form-control-label">{{$lang.contact_name}}</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" :placeholder="$lang.contact_name"
                    v-model="contact.name" name="contact_name">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-sm-2 form-control-label">{{$lang.contact_phone_number}}</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" :placeholder="$lang.contact_phone_number"
                    v-model="contact.phone_number" name="contact_phone_number">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row" :class="{'has-error': errors.has('contact.email_address') }">
                <label class="col-sm-2 form-control-label">{{$lang.contact_email_address}}</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" :placeholder="$lang.contact_email_address"
                    v-model="contact.email_address" name="contact_email_address" data-vv-as="email address" v-validate="'email'" :class="{'is-danger': errors.has('contact_email_address') }">
                  <i v-show="errors.has('contact_email_address')" class="fa fa-warning"></i>
                  <span class="help is-danger" v-show="errors.has('contact_email_address')">{{ errors.first('contact_email_address') }}</span>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-sm-2 form-control-label">{{$lang.contact_address}}</label>
                <div class="col-sm-4">
                    <textarea rows="5" class="form-control" :placeholder="$lang.contact_address"
                    v-model="contact.address" name="contact_address"></textarea>
                </div>
              </div>
            </div>
          </div>
        </form>
			</div>
		</div>
		<div class="row">
	    <div class="col-md-12">
	      <div class="pull-left">
	        <button class="btn btn-primary" @click="redirectToBackward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.website_back_button}}</button>
	      </div>
	      <div class="pull-right">
	        <button class="btn btn-primary" @click="saveContactDetails()">{{$lang.website_save_button}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
	      </div>
	    </div>
	  </div>
  </div>
</template>
<script>
var moment = require('moment');
import Website from '../../../api/website.js';
export default {
  data() {
    return {
      contact: {
        name: '',
        phone_number: '',
        email_address: '',
        contact_address: '',
        website_id: null,
      }
    }
  },
	mounted() {
		let currentNavigationData = {
			activeTab:'website_contact',
			currentPage:'Contact'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
	},
	computed: {
	},
	methods: {
		saveContactDetails() {
      this.$validator.validateAll().then(
      (response) => {
        if(response) {
          this.contact.website_id = this.getWebsiteId();
          $("body .js-loader").removeClass('d-none');
          Website.website_id(this.contact).then(
            (response)=> {
              $("body .js-loader").addClass('d-none');
              toastr['success']('Contact page has been updated successfully', 'Success');
              this.$router.push({name:'website_add'});
            },
            (error)=>{
              toastr['error']('Error while saving data', 'Error');
            }
          );
        }
      }
		},
		redirectToBackward() {
			this.$router.push({name:'website_media'})
		},
    getWebsiteId() {
      return this.$store.state.Website.id;
    },
	},
}
</script>
