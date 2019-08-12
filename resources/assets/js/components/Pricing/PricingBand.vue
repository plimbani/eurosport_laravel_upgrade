<template>
	<div>
		<div class="row align-items-center mt-2" v-for="(band, bandIndex) in bands">
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                <div class="form-group" :class="{'has-error': errors.has('min_teams'+bandIndex) }">
                    <input type="text" class="form-control" placeholder="e.g. 1" :class="{'is-danger': errors.has('min_teams'+bandIndex) }" :name="'min_teams'+bandIndex" v-validate="'required'" v-model="band.min_teams" data-vv-as="Min teams">
                    <div class="error-block">
                        <i v-show="errors.has('min_teams'+bandIndex)" class="fas fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('min_teams'+bandIndex)">{{ errors.first('min_teams'+bandIndex) }}</span>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id" :value="band.id">
            <div class="col-sm-4 col-md-1 col-lg-1 col-xl-1 text-center">
                <div class="form-group">
                    <p class="m-0">to</p>
                </div>
            </div>
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                <div class="form-group" :class="{'has-error': errors.has('max_teams'+bandIndex) }">
                    <input type="text" class="form-control" placeholder="e.g. 5" :class="{'is-danger': errors.has('max_teams'+bandIndex) }" :name="'max_teams'+bandIndex" v-validate="'required'" v-model="band.max_teams" data-vv-as="Max teams">
                    <div class="error-block">
                        <i v-show="errors.has('max_teams'+bandIndex)" class="fas fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('max_teams'+bandIndex)">{{ errors.first('max_teams'+bandIndex) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                <div class="form-group" :class="{'has-error': errors.has('price'+bandIndex) }">
                    <input type="text" class="form-control" :class="{'is-danger': errors.has('price'+bandIndex) }" placeholder="e.g. 100" v-model="band.price" :name="'price'+bandIndex" v-validate="'required'" data-vv-as="Basic price">
                    <div class="error-block">
                        <i v-show="errors.has('price'+bandIndex)" class="fas fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('price'+bandIndex)">{{ errors.first('price'+bandIndex) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2" v-if="tournamentType == 'cup'">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="e.g. 100" v-model="band.advanced_price">
                </div>
            </div>

            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-3 text-left text-sm-center" v-if="bands.length > 1">
                <div class="form-group">
                    <p class="m-0"><a href="javascript:void(0)" class="text-primary" @click="removeBand(bandIndex)"><i class="fas fa-trash text-danger"></i></a></p>
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
        inject: ['$validator'],
        props: ['tournamentType', 'bands'],
        components : {
        },
        methods: {
        	removeBand(index) {
        		this.bands.splice(index, 1);
        	}
        }
	}
</script>