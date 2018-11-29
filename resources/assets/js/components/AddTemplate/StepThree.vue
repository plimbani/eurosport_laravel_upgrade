<template>
	<div>
		<div class="container" id="step3-template-setting">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="row">
						<div class="col-12">
							<h5>{{ $lang.add_template_modal_step3_header }}</h5>
						</div>
					</div>
					<div class="card mb-3">
						<div class="card-block">
							<div class="row align-items-center my-1" v-for="(placing, placingIndex) in templateFormDetail.stepthree.placings">
								<div class="col-md-3">
						        	<label class="mb-0">{{ getSuffixForPosition(placingIndex + 1) }} Place</label>
						        </div>
						        <div class="col-md-9">
						        	<div class="row align-items-center">
						        		<div class="col-md-4">
					        				<div class="form-group mb-0">
						        				<select class="form-control ls-select2" name="placing">
							                    	<option value="placed">Placed</option>
							                    	<option value="winner">Winner</option>
							                    	<option value="loser">Loser</option>
							                    	<option value="team">Team</option>
							                    </select>
							                </div>
						        		</div>
						        		<div class="col-md-3">
						        			<div class="form-group mb-0">
							        			<select class="form-control ls-select2" name="relevant_group">
							                    	<option value="group">Group</option>
							                    	<option value="pm">PM</option>
							                    </select>
							                </div>
						        		</div>						        		
						        		<div class="col-md-4">
					        				<div class="form-group mb-0">
						        				<select class="form-control ls-select2" name="placing">
							                    	<option value="placed">Match 1</option>
							                    	<option value="winner">Match 2</option>
							                    	<option value="loser">Match 3</option>
							                    	<option value="team">Match 4</option>
							                    </select>
							                </div>
						        		</div>
						        		<div class="col-md-1 d-flex align-items-center justify-content-center">
						        			<a href="javascript:void(0)" @click="removePlacing(placingIndex)"><i class="jv-icon jv-dustbin"></i></a>
						        		</div>
						        	</div>	        	
						        </div>
							</div>
						</div>
					</div>
					<div class="row align-items-center mb-3">
						<div class="col-12">
							<button type="button" class="btn btn-primary" @click="addNewPlacing()">Add a placing</button>
						</div>
			    	</div>
			    	<div class="row align-items-center">
			    		<div class="col-12">
			    			<button type="button" class="btn btn-primary" @click="back()">{{ $lang.add_template_modal_back_button }}</button>
			    			<button type="button" class="btn btn-primary" @click="next()">{{ $lang.add_template_modal_next_button }}</button>
			    		</div>
			    	</div>
			    </div>
		    </div>
	    </div>
	</div>
</template>

<script type="text/javascript">
	export default {
		props: ['templateFormDetail'],
        data() {
            return {
            }
        },
        created() {
        },
        beforeCreate: function() {
        },
        components: {
        },
        mounted() {
        },
        methods: {
        	addNewPlacing() {
        		this.templateFormDetail.stepthree.placings.push({type: "winner", position_type: "PM1", teams_play_each_other: "Match 1"});
        	},
        	removePlacing(index) {
        		this.templateFormDetail.stepthree.placings.splice(index, 1);
        	},
        	getSuffixForPosition(d) {
		      	if(d>=11 && d<=13) return d +'th';
		      	switch (d % 10) {
		            case 1:  return d +"st";
		            case 2:  return d +"nd";
		            case 3:  return d +"rd";
		            default: return d +"th";
		        }
		    },
		    updateTemplateData(data) {
		    	// this.templateFormDetail = data;
		    },
		    next() {
		    	this.$emit('change-tab-index', 3, 4, 'stepthree', _.cloneDeep(this.templateFormDetail.stepthree));
		    },
		    back() {
                this.$emit('change-tab-index', 3, 2, 'stepthree', _.cloneDeep(this.templateFormDetail.stepthree));
            }

        }
	}
</script>