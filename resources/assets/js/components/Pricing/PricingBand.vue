<template>
	<div>
		<div class="row align-items-center" v-for="(band, bandIndex) in bands">
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                <div class="form-group" :class="{'has-error': errors.has('min_teams') }">
                    <input type="text" class="form-control" placeholder="e.g. 1" :class="{'is-danger': errors.has('min_teams') }" name="min_teams" v-validate="'required'" v-model="band.min_teams">
                    <i v-show="errors.has('min_teams')" class="fas fa-warning"></i>
                    <span class="help is-danger" v-show="errors.has('min_teams')">{{ errors.first('min_teams') }}</span>
                </div>
            </div>
            <input type="hidden" name="id" :value="band.id">
            <div class="col-sm-4 col-md-1 col-lg-1 col-xl-1 text-center">
                <div class="form-group">
                    <p class="m-0">to</p>
                </div>
            </div>
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                <div class="form-group">
                    <input type="text" class="form-control" id="max-team" placeholder="e.g. 5" v-model="band.max_teams">
                </div>
            </div>
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                <div class="form-group">
                    <input type="text" class="form-control" id="basic-price" placeholder="e.g. 100" v-model="band.price">
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2" v-if="tournamentType == 'cup'">
                <div class="form-group">
                    <input type="text" class="form-control" id="advanced-price" placeholder="e.g. 100" v-model="band.advanced_price">
                </div>
            </div>

            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-3 text-left text-sm-center">
                <div class="form-group">
                    <p class="m-0"><a href="javascript:void(0)" class="text-primary" @click="removeBand(bandIndex)"><u>Delete</u></a></p>
                </div>
            </div>
        </div>
	</div>
</template>

<script type="text/babel">
	export default {
		data() {
            return {
            }
        },
        props: ['tournamentType', 'bands'],
        inject: ['$validator'],
        components : {
        },
        methods: {
        	removeBand(index) {
        		this.bands.splice(index, 1);
        	}
        }
	}
</script>