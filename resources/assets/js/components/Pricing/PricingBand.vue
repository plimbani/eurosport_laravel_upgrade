<template>
	<div>
		<div class="row align-items-center" v-for="(band, bandIndex) in bands">
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                <div class="form-group">
                    <input type="text" class="form-control" id="min-team" aria-describedby="min-team" placeholder="e.g. 1">
                </div>
            </div>
            <div class="col-sm-4 col-md-1 col-lg-1 col-xl-1 text-center">
                <div class="form-group">
                    <p class="m-0">to</p>
                </div>
            </div>
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                <div class="form-group">
                    <input type="text" class="form-control" id="max-team" aria-describedby="max-team" placeholder="e.g. 5">
                </div>
            </div>
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2">
                <div class="form-group">
                    <input type="text" class="form-control" id="basic-price" aria-describedby="basic-price" placeholder="e.g. 100">
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2" v-if="tournamentType == 'cup'">
                <div class="form-group">
                    <input type="text" class="form-control" id="advanced-price" aria-describedby="advanced-price" placeholder="e.g. 100">
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
        components : {
        },
        methods: {
        	removeBand(index) {
        		this.bands.splice(index, 1);
        	}
        }
	}
</script>