<template>
	<div>
		<div class="container" id="step3-template-setting">
			<div class="row justify-content-center">
				<div class="col-md-12">
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
						        				<select class="form-control ls-select2" v-model="placing.position_type" @change="onPositionTypeChange(placingIndex)">
							                    	<option value="placed">Placed</option>
							                    	<option value="winner">Winner</option>
							                    	<option value="loser">Loser</option>
							                    </select>
							                </div>
						        		</div>
						        		<div class="col-md-3">
						        			<div class="form-group mb-0">
							        			<select class="form-control ls-select2" v-model="placing.group">
							                    	<option v-for="group in getGroupsForSelection(placingIndex)" :value="group.value">{{ group.name }}
							                    	</option>
							                    </select>
							                </div>
						        		</div>						        		
						        		<div class="col-md-4">
					        				<div class="form-group mb-0">
						        				<select class="form-control ls-select2" v-model="placing.position">
							                    	<option :value="position.value" v-for="position in getPositionsForSelection(placingIndex, placing.group)">{{ position.name }}</option>
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
        		this.templateFormDetail.stepthree.placings.push({position_type: 'placed', group: '', position: ''});
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
            },
            getGroupsForSelection(placingIndex) {
        		let placing = this.templateFormDetail.stepthree.placings[placingIndex];
        		let groupsForSelection = [];
        		let roundRobinIndex = 0;
        		let placingMatchIndex = 0;
        		let roundGroupCount = 0;
                let placingGroupCount = 0;
                let vm = this;

        		_.forEach(this.templateFormDetail['steptwo'].rounds, function(round, roundIndex) {
					_.forEach(round.groups, function(group, groupIndex) {
						if(roundIndex === vm.roundIndex && groupIndex >= vm.index) return false;

						if(group.type === 'round_robin' && placing.position_type === 'placed') {
							groupsForSelection[roundRobinIndex] = {'name': 'Group ' + String.fromCharCode(65 +roundGroupCount), 'value': '-1,' + roundIndex + ',' + groupIndex};
							roundGroupCount += 1;

							if(roundRobinIndex === 0 && (placing.group === '' || typeof placing.group === 'undefined'))
								vm.templateFormDetail.stepthree.placings[placingIndex].group = groupsForSelection[roundRobinIndex].value;

							roundRobinIndex++;

							return true;
						}

						if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], placing.position_type) > -1) {
							placingGroupCount += 1;
							groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (placingGroupCount), 'value': '-1,' + roundIndex + ',' + groupIndex};

							if(placingMatchIndex === 0 && (placing.group === '' || typeof placing.group === 'undefined')) {
								vm.templateFormDetail.stepthree.placings[placingIndex].group = groupsForSelection[placingMatchIndex].value;
							}

							placingMatchIndex++;

							return true;
						}
					});
				});

				_.forEach(this.templateFormDetail['steptwo'].divisions, function(division, divisionIndex) {
					_.forEach(division.rounds, function(round, roundIndex) {
						_.forEach(round.groups, function(group, groupIndex) {
							if(group.type === 'round_robin' && placing.position_type === 'placed') {
								groupsForSelection[roundRobinIndex] = {'name': 'Group ' + String.fromCharCode(65 +roundGroupCount), 'value': divisionIndex + ',' + roundIndex + ',' + groupIndex};
								roundGroupCount += 1;

								if(roundRobinIndex === 0 && placing.group === '')
									vm.templateFormDetail.stepthree.placings[placingIndex].group = groupsForSelection[roundRobinIndex].value;

								roundRobinIndex++;

								return true;
							}

							if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], placing.position_type) > -1) {
								placingGroupCount += 1;
								groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (placingGroupCount), 'value': divisionIndex + ',' + roundIndex + ',' + groupIndex};

								if(placingMatchIndex === 0 && (placing.group === '' || typeof placing.group === 'undefined')) {
									vm.templateFormDetail.stepthree.placings[placingIndex].group = groupsForSelection[placingMatchIndex].value;
								}

								placingMatchIndex++;

								return true;
							}
						});
					});
				});
				return groupsForSelection;
            },
            getPositionsForSelection(placingIndex, group) {
            	let vm = this;
			    var positionsForSelection = [];

		    	let placing = this.templateFormDetail.stepthree.placings[placingIndex];
		    	if(group) {
			    	var currentDivisionRoundGroup = group.split(',');
			    	var groupData = null;
			    	if(currentDivisionRoundGroup[0] === '-1') {
			    		groupData = this.templateFormDetail['steptwo'].rounds[currentDivisionRoundGroup[1]].groups[currentDivisionRoundGroup[2]];
			    	} else {
			    		groupData = this.templateFormDetail['steptwo'].divisions[currentDivisionRoundGroup[0]].rounds[currentDivisionRoundGroup[1]].groups[currentDivisionRoundGroup[2]];
			    	}
			    	var teams = groupData.teams;
			    	var numberOfTeams = groupData.no_of_teams;
			    	var groupType = groupData.type;
			    	
				    // for round robin
			    	if(groupType === 'round_robin' && placing.position_type === 'placed') {
				    	if(this.templateFormDetail.stepthree.placings[placingIndex].position === '' || typeof this.templateFormDetail.stepthree.placings[placingIndex].position === 'undefined') {
				    		this.templateFormDetail.stepthree.placings[placingIndex].position = group + ',0';
				    	}
				    	_.forEach(teams, function(team, index) {
		    				positionsForSelection.push({'name': vm.getSuffixForPosition(index + 1), 'value': group + ',' + index});
		    			});
		    		}

			    	// for placing
					if(groupType === 'placing_match' && _.indexOf(['winner', 'loser'], placing.position_type) > -1) {
						let matches = numberOfTeams / 2;
						if(this.templateFormDetail.stepthree.placings[placingIndex].position === '' || typeof this.templateFormDetail.stepthree.placings[placingIndex].position === 'undefined') {
							this.templateFormDetail.stepthree.placings[placingIndex].position = group + ',0';
						}
						for (var i = 1; i <= matches; i++) {
							positionsForSelection.push({'name': 'Match' + i, 'value': group + ',' + (i - 1)});
						}
					}

			    	return positionsForSelection;
		    	}
            },
            onPositionTypeChange(placingIndex) {
            	let vm = this;
		    	this.templateFormDetail.stepthree.placings[placingIndex].group = '';
		    	this.templateFormDetail.stepthree.placings[placingIndex].position = '';
            },
            onGroupChange(teamIndex) {
		    	let vm = this;
		    	this.templateFormDetail.stepthree.placings[placingIndex].position = '';
		    },
        }
	}
</script>