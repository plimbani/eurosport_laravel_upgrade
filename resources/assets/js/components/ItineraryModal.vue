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
            <label class="col-sm-5 form-control-label">{{ $lang.program_itinerary_day }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.day" v-validate="{'required':true}" :class="{'is-danger': errors.has('day') }" name="day" type="text" class="form-control">
                <i v-show="errors.has('day')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('day')">{{ errors.first('day') }}<br>
                </span>                
            </div>
          </div>
          <div class="form-group row" :class="{'has-error': errors.has('time') }">
            <label class="col-sm-5 form-control-label">{{ $lang.program_itinerary_time }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.time" v-validate="{'required':true}" :class="{'is-danger': errors.has('time') }" name="time" type="text" class="form-control">
                <i v-show="errors.has('time')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('time')">{{ errors.first('time') }}<br>
                </span>                
            </div>
          </div>
          <div class="form-group row" :class="{'has-error': errors.has('item') }">
            <label class="col-sm-5 form-control-label">{{ $lang.program_itinerary_item }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.item" v-validate="{'required':true}" :class="{'is-danger': errors.has('item') }" name="item" type="text" class="form-control">
                <i v-show="errors.has('item')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('item')">{{ errors.first('item') }}<br>
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
          day: '',
          time: '',
          item: '',
        },
      };
    },
    created() {
      this.$root.$on('setItineraryData', this.setItineraryData);
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
        this.formValues.day = itineraryData.day;
        this.formValues.time = itineraryData.time;
        this.formValues.item = itineraryData.item;
        this.errors.clear();        
      }
    }
  }
</script>
