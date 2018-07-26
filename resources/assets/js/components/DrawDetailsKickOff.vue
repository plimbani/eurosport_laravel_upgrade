<template>
<div class="col-md-6 row mt-4">
<h3>U15-PM</h3>
<select class="form-control ls-select2 col-sm-8 offset-sm-2" 
v-on:change="onChangeGroup"
v-model="tournament">
        <option value="">Select another draw</option>
        <option>
        U15-Group A
        </option>                                
        <option>
        U15-Group B
        </option>  
    </select>
 Draw Details KO
</div>
</template>
<script type="text/babel">

export default {
	data() {
		return {
			drawsData:[],
		}
	},
	mounted() {
		// here we call function to get all the Draws Listing
		
	},
	methods: {
		
	}
}
</script>