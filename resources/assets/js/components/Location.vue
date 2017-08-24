<template>
<div>
<div v-for="(location, index) in locations">
<div class="">
<h6><strong>{{$lang.tournament_location}}</strong></h6>
</div>
<div class="form-group row" :class="{'has-error': errors.has('location.venue') }">
<label class="col-sm-2 form-control-label">{{$lang.tournament_venue}}*</label>

	<div class="col-sm-4">
		<input type="text" class="form-control" placeholder="" name="tournamemt_validation_venue"
		 v-model="location.tournament_venue_name" v-validate="'required'" :class="{'is-danger': errors.has('tournamemt_validation_venue') }">
		 <i v-show="errors.has('tournamemt_validation_venue')" class="fa fa-warning"></i>
		 <span class="help is-danger" v-show="errors.has('tournamemt_validation_venue')">{{$lang.tournamemt_validation_venue}}</span>
	</div>
</div>


<div class="form-group row">
<label class="col-sm-2 form-control-label">{{$lang.tournament_address}}</label>

<div class="col-sm-4">
<input type="text" class="form-control" 
v-model="location.touranment_venue_address"
placeholder="">
</div>
</div>
<div class="form-group row">
<label class="col-sm-2 form-control-label">{{$lang.tournament_town_city}}</label>

<div class="col-sm-4">
<input type="text" class="form-control" 
v-model="location.tournament_venue_city"
placeholder="">
</div>
</div>
<div class="form-group row">
<label class="col-sm-2 form-control-label">{{$lang.tournament_postcode}}</label>

<div class="col-sm-4">
<input type="text" class="form-control" 
v-model="location.tournament_venue_postcode"
placeholder="">
</div>
</div>
<div class="form-group row">
<label class="col-sm-2 form-control-label">{{$lang.tournament_state}}</label>

<div class="col-sm-4">
<input type="text" class="form-control"
v-model="location.tournament_venue_state"
placeholder="">
</div>
</div>
<div class="form-group row">
<label class="col-sm-2 form-control-label">{{$lang.tournament_country}}</label>

<div class="col-sm-4">
<div class="form-group">
<select class="form-control ls-select2" v-model="location.tournament_venue_country">
<option value="">{{$lang.tournament_country_please_select}}</option>
<option value="Andorra">Andorra</option>
<option value="Belgium">Belgium</option>
<option value="France">France </option>
<option value="Germany">Germany</option>
<option value="Italy">Italy</option>
<option value="Spain">Spain</option>
<option value="United Kingdom">United Kingdom</option></select>
</div>
</div>
</div>
<div class="form-group row">
<label class="col-sm-2 form-control-label">{{$lang.tournament_organiser}}</label>
<div class="col-sm-4">
<input type="text" class="form-control" placeholder="">
</div>
</div>
<div class="form-group row">
<div class="col-sm-3">
<button class="btn btn-danger w-75" @click.prevent="removeLocation(index)" v-if="index > 0">- Remove location</button>
</div>
</div>

</div>

</div>
</template>
<script type="text/babel">
export default {
     props: ['locations'],
     methods: {
     	removeLocation (index){
     		this.locations.splice(index,1)
     	}
     }
}
</script>
