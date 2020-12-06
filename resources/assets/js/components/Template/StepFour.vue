<template>
	<div>
		<div id="">
			<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="row">
						<div class="col-12">
							<h5 v-if="templateFormDetail.stepone.editor == 'knockout'">{{ $lang.add_template_modal_step3_header_knockout }}</h5>
							<h5 v-else>{{ $lang.add_template_modal_step4_header }}</h5>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<h6 class="font-weight-bold">{{templateFormDetail.stepone.templateName}} &nbsp;<span class="small">({{templateFormDetail.stepone.no_of_teams}} teams)</span></h6>
						</div>
					</div>
					
					<div class="card mb-3" v-for="(round, roundIndex) in templateFormDetail.steptwo.rounds">
						<div class="card-block">
							<div class="row align-items-center">
								<div class="col-12">
									<h6 class="font-weight-bold">{{ getRoundName(roundIndex, -1) }}&nbsp;<span class="small">({{ round.no_of_teams }} teams)</span></h6>
								</div>
							</div>
							<div class="row">
								<div class="col-6" v-for="(group, groupIndex) in round.groups">
									<h6 class="font-weight-bold mb-0">{{ getGroupName(groupIndex, roundIndex, -1) }}</h6>
									<p class="text-muted small mb-0" v-if="group.type === 'round_robin'">Teams play each other {{ getTeamPlayEachOther(group.teams_play_each_other) }}</p>
									<ul class="list-unstyled mb-4 round-details">
										<li v-if="group.type === 'round_robin'" v-for="(team, teamIndex) in group.teams">
											<div class="round-matches">
												<span class="w-80" v-if="roundIndex == 0">Team {{ teamIndex + 1 }}</span>
												<span v-if="roundIndex > 0" :class="{'w-180': groupPositionType(group.teams)}" v-html="getMatchDetail(team.position, team.position_type)"></span>
											</div>
										</li>
										<li v-if="group.type === 'placing_match'" v-for="(team, teamIndex) in group.teams">
											<div v-if="teamIndex % 2 === 0">
												<div class="round-matches" v-if="roundIndex == 0">
													<span class="w-80">{{ 'Team ' + (parseInt(group.teams[teamIndex].position) + 1) }}</span>
													<span class="w-7">vs</span>
													<span class="w-80">{{ 'Team ' + (parseInt(group.teams[teamIndex + 1].position) + 1) }}</span>
												</div>
												<div v-if="roundIndex > 0" class="round-matches">
													<span :class="{'w-180': groupPositionType(team.position_type)}" v-html="getMatchDetail(team.position, team.position_type)"></span>
													<span class="w-7">vs</span>
													<span v-if="roundIndex > 0" :class="{'w-180': groupPositionType(group.teams)}" v-html="getMatchDetail(group.teams[teamIndex + 1].position, group.teams[teamIndex + 1].position_type)"></span>
												</div>
												<!-- <span v-if="roundIndex > 0" class="round-matches">{{ getMatchDetail(team.position, team.position_type) + ' vs ' + getMatchDetail(group.teams[teamIndex + 1].position, group.teams[teamIndex + 1].position_type) }}</span> -->
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>

					<div class="card mb-3" v-if="templateFormDetail.stepone.editor == 'knockout'">
						<div class="card-block">
							<div class="row align-items-center">
								<div class="col-12">
									<h6 class="font-weight-bold">Round 2&nbsp;<span class="small">({{ templateFormDetail.stepone.no_of_teams_in_round_two }} teams)</span></h6>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<h6 class="font-weight-bold mb-0">PM 1</h6>
									<ul class="list-unstyled mb-4 round-details">
										<li v-for="(obj, index) in getKnockoutRoundTwoTeams()">
											<div>
												<div class="round-matches">
													<span class="w-180">{{ obj.name }}</span>
													<span class="w-7">{{ obj.no_of_teams }}</span>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
					<div class="card mb-3" v-for="(division, divisionIndex) in templateFormDetail.steptwo.divisions">
						<div class="card-block">
							<div class="row align-items-center">
								<div class="col-12">
									<h6 class="font-weight-bold">{{ 'Division ' + (divisionIndex + 1) }}&nbsp;<span class="small">({{ division.no_of_teams }} items)</span></h6>
								</div>
							</div>
							<div class="row align-items-center">
								<div class="col-12">
									<ul class="list-unstyled mb-4 round-details">
										<li v-for="(team, teamIndex) in division.teams">
											<div class="round-matches">
												<span class="mr-1">{{ (teamIndex + 1) + '.' }}</span>
												<!-- <span>.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> -->
												<span v-html="getMatchDetail(team.position, team.position_type)"></span>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<div class="card mb-3" v-for="(round, roundIndex) in division.rounds">
										<div class="card-block">
											<div class="row align-items-center">
												<div class="col-12">
													<h6 class="font-weight-bold">{{ getRoundName(roundIndex, divisionIndex) }}&nbsp;<span class="small">({{ round.no_of_teams }} items)</span></h6>
												</div>
											</div>
											<div class="row">
												<div class="col-6" v-for="(group, groupIndex) in round.groups">
													<h6 class="font-weight-bold mb-0">{{ getGroupName(groupIndex, roundIndex, divisionIndex) }}</h6>
													<p class="text-muted small mb-0" v-if="group.type === 'round_robin'">Teams play each other {{ group.teams_play_each_other }}</p>
													<ul class="list-unstyled mb-4 round-details">
														<li v-if="group.type === 'round_robin'" v-for="(team, teamIndex) in group.teams">
															<span v-if="roundIndex == 0">Team {{ teamIndex + 1 }}</span>
															<span v-if="roundIndex > 0" v-html="getMatchDetail(team.position, team.position_type)"></span>
														</li>
														<li v-if="group.type === 'placing_match'" v-for="(team, teamIndex) in group.teams">
															<div v-if="teamIndex % 2 === 0" class="round-matches">
																<div v-if="roundIndex == 0">
																	<span class="w-80">{{ 'Team ' + (parseInt(group.teams[teamIndex].position) + 1) }}</span>
																	<span class="w-7">vs</span>
																	<span class="w-80">{{ 'Team ' + (parseInt(group.teams[teamIndex + 1].position) + 1) }}</span>
																</div>
																<div v-if="roundIndex > 0">
																	<span :class="{'w-180': groupPositionType(group.teams)}" v-html="getMatchDetail(team.position, team.position_type)"></span>
																	<span class="w-7">vs</span>
																	<span :class="{'w-180': groupPositionType(group.teams)}" 
																	v-html="getMatchDetail(group.teams[teamIndex + 1].position, group.teams[teamIndex + 1].position_type)"></span>
																	<!-- <span v-if="roundIndex > 0">{{ getMatchDetail(team.position, team.position_type) + ' vs ' + getMatchDetail(group.teams[teamIndex + 1].position, group.teams[teamIndex + 1].position_type) }}</span> -->
																</div>
																<span v-if="group.matches[teamIndex % 2].is_final === true">
																	<i class="fa fa-igloo"></i> (final)
																</span>
															</div>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="card mb-3" v-if="templateFormDetail.stepone.editor !== 'knockout'">
						<div class="card-block">
							<div class="row align-items-center">
								<div class="col-12">
									<h6 class="font-weight-bold">Placings</h6>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<ul class="list-unstyled mb-4 round-details">
										<li v-for="(placing, placingIndex) in templateFormDetail.stepthree.placings">
											<div class="round-matches">
												<span class="position-number d-inline-block">{{ getSuffixForPosition((placingIndex + 1)) }}</span>
												<span class="w-180" v-html="getMatchDetail(placing.position, placing.position_type)"></span>
												<!-- <span>{{ getSuffixForPosition((placingIndex + 1)) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + getMatchDetail(placing.position, placing.position_type) }}</span> -->
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>

					<form>
						<div class="form-group row align-items-center mb-3">
							<div class="col-12">
								<button type="button" class="btn btn-primary" @click="back()">{{ $lang.add_template_modal_back_button }}</button>
								<button type="button" class="btn btn-primary" @click="saveTemplateDetail">{{ $lang.add_template_modal_save_button }}</button>
							</div>
				    	</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	import Template from '../../api/template.js'
	export default {
		props: ['templateFormDetail', 'editedTemplateId'],
        data() {
            return {
            }
        },
        created() {
            this.$root.$on('updateTemplateData', this.updateTemplateData);
        },
        beforeCreate: function() {
            this.$root.$off('updateTemplateData');
        },
        computed: {
		    userDetails: function() {
		      return this.$store.state.Users.userDetails
		    },
        },
        methods: {
        	saveTemplateDetail() {
        		let vm = this;
        		var templateData = {'templateFormDetail': this.templateFormDetail};
        		this.$validator.validateAll().then((response) => {
	        		if(response) {
	        			var templateData = { 'templateFormDetail': this.templateFormDetail };
	        			if(this.editedTemplateId) {
	        				templateData.editedTemplateId = this.editedTemplateId;
	        				Template.updateTemplateDetail(templateData).then(
			        			(response) => {
			        				toastr.success('Template has been updated successfully.', 'Update Template', {timeOut: 5000});
			        				vm.$root.$emit('clearFormFields');
			        				vm.$router.push({name: 'templates_list'})
			        				if(this.userDetails.role_slug == 'customer') {
			        					this.$router.push({name: 'dashboard'});
			        				}
			        			},
			        			(error) => {
			        			}
			        		);
	        			} else {
			        		Template.saveTemplateDetail(templateData).then(
			        			(response) => {
			        				toastr.success('Template has been added successfully.', 'Add Template', {timeOut: 5000});
			        				if(vm.userDetails.role_slug == 'customer') {
			        					vm.$router.push({name: 'dashboard'});
			        				} else {
			        					vm.$router.push({name: 'templates_list'})	
			        				}
			        			},
			        			(error) => {
			        			}
			        		);
	        			}
	        		}
                }).catch((errors) => {
                });	        		
        	},
        	back() {
        		if(this.templateFormDetail.stepone.editor === 'knockout') {
        			this.$emit('change-tab-index', 4, 2, 'stepfour', _.cloneDeep(this.templateFormDetail.stepfour));
        		} else {
        			this.$emit('change-tab-index', 4, 3, 'stepfour', _.cloneDeep(this.templateFormDetail.stepfour));
        		}
        	},
			getGroupName(groupIndex, roundIndex, divisionIndex) {
				return this.getGroupNameByRoundAndGroupIndex(groupIndex, roundIndex, divisionIndex);
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
		    getGroupNameByRoundAndGroupIndex(groupIndex, roundIndex, divisionIndex) {
		    	let vm = this;
		    	let roundData = null;
		    	if(divisionIndex === -1) {
		    		roundData = this.templateFormDetail['steptwo'].rounds[roundIndex];
		    	} else {
		    		roundData = this.templateFormDetail['steptwo'].divisions[divisionIndex].rounds[roundIndex];
		    	}
		    	let groupData = roundData.groups[groupIndex];

		    	if(groupData.type === 'round_robin') {
		    		return 'Group ' + this.getRoundRobinGroupName(roundData, groupIndex);
		    	}

		    	if(groupData.type === 'placing_match') {
		    		return 'PM ' + this.getPlacingMatchGroupName(roundData, groupIndex);
		    	}
		    },
		    getRoundRobinGroupName(roundData, groupIndex) {
		    	let currentRoundGroupCount =  _.filter(roundData.groups, function(o, index) { return (o.type === 'round_robin' && index < groupIndex); }).length;
		    	return String.fromCharCode(65 + roundData.start_round_group_count + currentRoundGroupCount);
		    },
		    getPlacingMatchGroupName(roundData, groupIndex) {
		    	let currentPlacingGroupCount =  _.filter(roundData.groups, function(o, index) { return (o.type === 'placing_match' && index <= groupIndex); }).length;
		    	return (roundData.start_placing_group_count + currentPlacingGroupCount);
		    },
		    getRoundName(roundIndex, divisionIndex) {
                let vm = this;

                if(divisionIndex === -1) {
                    return 'Round ' + (this.templateFormDetail.steptwo.start_round_count + roundIndex + 1);
                }

                return 'Round ' + (this.templateFormDetail.steptwo.divisions[divisionIndex].start_round_count + roundIndex + 1);
            },
            getMatchDetail(teamPosition, positionType) {
            	if(teamPosition) {
			    	let vm = this;

			    	let divisionRoundGroupPosition = teamPosition.split(',');
		    		let divisionIndex = parseInt(divisionRoundGroupPosition[0]);
		    		let roundIndex = parseInt(divisionRoundGroupPosition[1]);
		    		let groupIndex = parseInt(divisionRoundGroupPosition[2]);
		    		let position = parseInt(divisionRoundGroupPosition[3]) + 1;

		    		let groupName = null;
			    	let roundData = null;
			    	if(divisionIndex === -1) {
			    		roundData = this.templateFormDetail['steptwo'].rounds[roundIndex];
			    	} else {
			    		roundData = this.templateFormDetail['steptwo'].divisions[divisionIndex].rounds[roundIndex];
			    	}
			    	let groupData = roundData.groups[groupIndex];

			    	if(groupData) {
				    	if(groupData.type === 'round_robin') {
				    		groupName = 'Group ' + this.getRoundRobinGroupName(roundData, groupIndex);
				    		// return this.getSuffixForPosition(position) + ' - ' +  groupName;
				    		return '<span class="position-number d-inline-block">' + this.getSuffixForPosition(position) + '</span><span class="w-80">' + groupName +  '</span>';
				    	}

				    	if(groupData.type === 'placing_match') {
				    		groupName = 'PM ' + this.getPlacingMatchGroupName(roundData, groupIndex);
				    		// return (positionType.charAt(0).toUpperCase() + positionType.slice(1)) + ' ' + groupName + ' Match ' + position;
				    		return '<span class="w-60 d-inline-block">' + positionType.charAt(0).toUpperCase() + positionType.slice(1) + '</span><span class="w-50 d-inline-block">' + groupName +  '</span><span class="w-70 d-inline-block">Match '+ position +'</span>';
				    	}
				    }
			    }
		    },
		    getDivisions() {
		    	let divisions = new Array();
		    	divisions[-1]['rounds'] = new Array();
		    	divisions[-1]['rounds'] = this.templateFormDetail.steptwo.rounds;
		    	divisions.push(this.templateFormDetail.steptwo.divisions);
		    },
			getTeamPlayEachOther(times) {
				let teamPlayEachOther = {
					'once' : 'once',
					'twice' : 'twice',
					'three_times' : 'three times',
					'four_times' : 'four times',
				}
				return teamPlayEachOther[times];
			},
			groupPositionType(positionType) {
				if (positionType != 'placed') {
					return true;
				}
				return false;
			},
			nth(d) {
				if (d > 3 && d < 21) return 'th';
				switch (d % 10) {
					case 1:  return "st";
					case 2:  return "nd";
					case 3:  return "rd";
					default: return "th";
				}
			},
			getKnockoutRoundTwoTeams() {
				let vm = this;
				let roundTwoTeams = [];
				let noOfTeamsInRoundTwo = this.templateFormDetail.stepone.no_of_teams_in_round_two;
				let noOfGroups = this.templateFormDetail.stepone.no_of_groups;
				let noOfTeamsInRoundOneGroup = [];
				let roundTwoTeamCount = 0;
				let bestPlacedTeams = 1;
				noOfTeamsInRoundOneGroup = this.templateFormDetail.steptwo.rounds[0].groups.map(x => x['no_of_teams']);
				let minimumRoundOneGroup = _.min(noOfTeamsInRoundOneGroup);
				while(roundTwoTeamCount < noOfTeamsInRoundTwo) {
					let noOfTeams = 0;
					if(minimumRoundOneGroup >= bestPlacedTeams) {
						noOfTeams = ( (roundTwoTeamCount + (noOfGroups * 1)) <= noOfTeamsInRoundTwo) ? (noOfGroups * 1) : (noOfTeamsInRoundTwo - roundTwoTeamCount);
					} else {
						for (let i = 0; i < noOfTeamsInRoundOneGroup; i++) {
							if( (noOfTeamsInRoundOneGroup[i] >= bestPlacedTeams) && ((roundTwoTeamCount + (noOfTeams + 1)) <= noOfTeamsInRoundTwo) ) {
								noOfTeams++;
							}
						}
					}
					roundTwoTeams.push({
						'name': 'Best ' + bestPlacedTeams + vm.nth(bestPlacedTeams) + ' placed teams',
						'position': bestPlacedTeams,
						'no_of_teams': noOfTeams
					});
					bestPlacedTeams++;
					roundTwoTeamCount+=noOfTeams;
				}
				this.templateFormDetail.stepfour.round_two_knockout_teams = roundTwoTeams;
				return roundTwoTeams;
			},
        }
    }
</script>