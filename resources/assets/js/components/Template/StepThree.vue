<template>
	<div>
		<div id="step3-template-setting" class="step3-template-setting">
			<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="row">
						<div class="col-12">
							<h5>{{ $lang.add_template_modal_step3_header }}</h5>
						</div>
					</div>
					<div class="card mb-3">
						<div class="card-block">
							<div class="placing-row">
								<div class="row align-items-center" v-for="(placing, placingIndex) in templateFormDetail.stepthree.placings">
									<div class="col-md-3">
							        	<div class="title-placing"><label class="mb-0">{{ getSuffixForPosition(placingIndex + 1) }} place</label></div>
							        </div>
							        <div class="col-md-9">
							        	<div class="row align-items-center">
							        		<div class="col-md-4">
						        				<div class="form-group m-0" :class="{'has-error': errors.has('position_type'+placingIndex) }">
							        				<select class="form-control ls-select2" v-model="placing.position_type" @change="onPositionTypeChange(placingIndex)" v-validate="'required'" :class="{'is-danger': errors.has('position_type'+placingIndex) }" :name="'position_type'+placingIndex" data-vv-as="Position type">
								                    	<option value="placed">Placed</option>
								                    	<option value="winner">Winner</option>
								                    	<option value="loser">Loser</option>
								                    </select>
								                    <i v-show="errors.has('position_type'+placingIndex)" class="fas fa-warning"></i>
										        	<span class="help is-danger" v-show="errors.has('position_type'+placingIndex)">{{ errors.first('position_type'+placingIndex) }}</span>
								                </div>
							        		</div>
							        		<div class="col-md-3">
							        			<div class="form-group m-0" :class="{'has-error': errors.has('position_group'+placingIndex) }">
								        			<select class="form-control ls-select2" v-model="placing.group" v-validate="'required'" :class="{'is-danger': errors.has('position_group'+placingIndex) }" 
								        			:name="'position_group'+placingIndex" data-vv-as="group">
								                    	<option v-for="group in getGroupsForSelection(placingIndex)" :value="group.value">{{ group.name }}
								                    	</option>
								                    </select>
								                    <i v-show="errors.has('position_group'+placingIndex)" class="fas fa-warning"></i>
										        	<span class="help is-danger" v-show="errors.has('position_group'+placingIndex)">{{ errors.first('position_group'+placingIndex) }}</span>
								                </div>
							        		</div>						        		
							        		<div class="col-md-4">
						        				<div class="form-group m-0" :class="{'has-error': errors.has('position_name'+placingIndex) }">
							        				<select class="form-control ls-select2" v-model="placing.position" :name="'position_name'+placingIndex" v-validate="'required'" :class="{'is-danger': errors.has('position_name'+placingIndex) }" data-vv-as="match name">
								                    	<option :value="position.value" v-for="position in getPositionsForSelection(placingIndex, placing.group)">{{ position.name }}</option>
								                    </select>
								                    <i v-show="errors.has('position_name'+placingIndex)" class="fas fa-warning"></i>
										        	<span class="help is-danger" v-show="errors.has('position_name'+placingIndex)">{{ errors.first('position_name'+placingIndex) }}</span>
								                </div>
							        		</div>
							        		<div class="col-md-1 d-flex justify-content-center">
							        			<div class="icon-delete-column">
							        				<a href="javascript:void(0)" @click="removePlacing(placingIndex)"><i class="fas fa-trash text-danger"></i></a>
							        			</div>
							        		</div>
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
            	errorMessages: {
            		en: {
            			custom: {
            				position_type: {
            					required: 'This field is required.',
            				},
            				position_group: {
            					required: 'This field is required.',
            				},
            				position_name: {
								required: 'This field is required.',
            				}
            			}
            		},
            		fr: {
						custom: {
            				position_type: {
            					required: 'FThis field is required.',
            				},
            				position_group: {
            					required: 'FThis field is required.',
            				},
            				position_name: {
								required: 'FThis field is required.',
            				}
            			}
            		}
            	}
            }
        },
        created() {
        	this.$validator.localize(this.errorMessages);
        	this.$root.$on('updatePositions', this.updatePositions);
        },
        beforeCreate: function() {
        	// Remove custom event listener 
            this.$root.$off('updatePositions');
        },
        components: {
        },
        inject: ['$validator'],
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
                this.$validator.validateAll().then((response) => {
	                if(response) {                    	
	    				this.$emit('change-tab-index', 3, 4, 'stepthree', _.cloneDeep(this.templateFormDetail.stepthree));
	                }
				}).catch((errors) => {

            	});
		    },
		    back() {
                this.$emit('change-tab-index', 3, 2, 'stepthree', _.cloneDeep(this.templateFormDetail.stepthree));
            },
            getGroupsForSelection(placingIndex) {
        		let placing = this.templateFormDetail.stepthree.placings[placingIndex];
        		let groupsForSelection = [];
        		let roundRobinIndex = 0;
        		let placingMatchIndex = 0;
                let vm = this;

        		_.forEach(this.templateFormDetail['steptwo'].rounds, function(round, roundIndex) {
					_.forEach(round.groups, function(group, groupIndex) {
						if(roundIndex === vm.roundIndex && groupIndex >= vm.index) return false;

						let roundData = vm.templateFormDetail['steptwo'].rounds[roundIndex];
						if(group.type === 'round_robin' && placing.position_type === 'placed') {
							groupsForSelection[roundRobinIndex] = {'name': 'Group ' + vm.getRoundRobinGroupName(roundData, groupIndex), 'value': '-1,' + roundIndex + ',' + groupIndex};

							if(roundRobinIndex === 0 && (placing.group === '' || typeof placing.group === 'undefined'))
								vm.templateFormDetail.stepthree.placings[placingIndex].group = groupsForSelection[roundRobinIndex].value;

							roundRobinIndex++;

							return true;
						}

						if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], placing.position_type) > -1) {
							groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (vm.getPlacingMatchGroupName(roundData, groupIndex)), 'value': '-1,' + roundIndex + ',' + groupIndex};

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

							let roundData = vm.templateFormDetail['steptwo'].divisions[divisionIndex].rounds[roundIndex];
							if(group.type === 'round_robin' && placing.position_type === 'placed') {
								groupsForSelection[roundRobinIndex] = {'name': 'Group ' + vm.getRoundRobinGroupName(roundData, groupIndex), 'value': divisionIndex + ',' + roundIndex + ',' + groupIndex};

								if(roundRobinIndex === 0 && placing.group === '')
									vm.templateFormDetail.stepthree.placings[placingIndex].group = groupsForSelection[roundRobinIndex].value;

								roundRobinIndex++;

								return true;
							}

							if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], placing.position_type) > -1) {
								groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (vm.getPlacingMatchGroupName(roundData, groupIndex)), 'value': divisionIndex + ',' + roundIndex + ',' + groupIndex};

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

			    	if(groupData) {
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
								positionsForSelection.push({'name': 'Match ' + i, 'value': group + ',' + (i - 1)});
							}
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
		    updatePositions() {
		    	let vm = this;

		    	let placings = _.cloneDeep(vm.templateFormDetail.stepthree.placings);

	    		_.forEach(vm.templateFormDetail.stepthree.placings, function(placing, placingIndex) {
		    		let group = placing.group != '' ? placing.group.split(',') : '';
		    		let position = placing.position != '' ? placing.position.split(',') : '';
		    		let allRounds = null;
		    		let selectedGroup = null;
		    		if(group[0] === '-1') {
		    			allRounds = vm.templateFormDetail['steptwo'].rounds;
		    		} else {
		    			if(!(group[0] in vm.templateFormDetail['steptwo'].divisions)) {
		    				placings.splice(placingIndex, 1);
		    				delete placings[placingIndex];
		    				return true;
		    			}
		    			allRounds = vm.templateFormDetail['steptwo'].divisions[group[0]].rounds;
		    		}

		    		if( (!(group[1] in allRounds)) || (!(group[2] in allRounds[group[1]].groups)) ) {
		    			delete placings[placingIndex];
		    			return true;
		    		}
		    		selectedGroup = allRounds[group[1]].groups[group[2]];

		    		if(placing.position != '') {
		    			if(placing.position_type === 'placed') {
		    				if(!(position[3] in selectedGroup.teams)) {
		    					delete placings[placingIndex];
		    					return true;
		    				}
		    			}
		    			if((placing.position_type === 'winner') || (placing.position_type === 'loser')) {
		    				if(!(position[3] in selectedGroup.matches)) {
		    					delete placings[placingIndex];
		    					return true;
		    				}
		    			}
		    		}
		    	});
		    
		    	vm.templateFormDetail.stepthree.placings = _.cloneDeep(_.compact(placings));
		    },
		    getRoundRobinGroupName(roundData, groupIndex) {
		    	let currentRoundGroupCount =  _.filter(roundData.groups, function(o, index) { return (o.type === 'round_robin' && index < groupIndex); }).length;
		    	return String.fromCharCode(65 + roundData.start_round_group_count + currentRoundGroupCount);
		    },
		    getPlacingMatchGroupName(roundData, groupIndex) {
		    	let currentPlacingGroupCount =  _.filter(roundData.groups, function(o, index) { return (o.type === 'placing_match' && index <= groupIndex); }).length;
		    	return (roundData.start_placing_group_count + currentPlacingGroupCount);
		    },
        }
	}
</script>