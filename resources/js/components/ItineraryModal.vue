<template>
	<div class="modal" id="itinerary_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentItineraryOperation == 'add' ? $lang.homepage_add_an_itinerary : $lang.homepage_edit_an_itinerary }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('day') }">
            <label class="col-sm-5 form-control-label">{{ $lang.program_itinerary_name }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.name" v-validate="{'required':true}" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control">
                <i v-show="errors.has('name')" class="fas fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}<br>
                </span>                
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">{{ $lang.cancel_button }}</button>
          <button type="button" class="btn btn-primary" @click="validateForm()">{{ $lang.save_button }}</button>
        </div>
      </div>
    </div>
	</div>
</template>

<script type="text/babel">
  import Website from '../api/website.js';
  import { ErrorBag } from 'vee-validate';

  export default {
    props: ['currentItineraryOperation'],
    data() {
      return {
        hasError: false,
        formValues: {
          id: '',
          name: '',
          items: [],
        },
      };
    },
    created() {
      this.$root.$on('setItineraryData', this.setItineraryData);
    },
    beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('setItineraryData');
    },
    computed: {
    },
    methods: {
      validateForm() {
        this.$validator.validateAll().then((response) => {
          if(response) {
            if(this.currentItineraryOperation == 'add') {
              this.$emit('storeItinerary', this.formValues);
            } else {
              this.$emit('updateItinerary', this.formValues);
            }
          }
        }).catch(() => {
          // fail stuff
        });
      },
      setItineraryData(itineraryData) {
        this.formValues.id = itineraryData.id;
        this.formValues.name = itineraryData.name;
        this.formValues.items = itineraryData.items;
        this.clearErrorMsgs();
      }
    }
  }
</script>
