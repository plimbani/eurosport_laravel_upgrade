<template>
	<div>
		<div class="container" id="">
			<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="row">
						<div class="col-12">
							<h5>{{ $lang.add_template_modal_step4_header }}</h5>
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
									<h6 class="font-weight-bold">{{ getRoundName(roundIndex, -1) }}&nbsp;<span class="small">({{ round.no_of_teams }} items)</span></h6>
								</div>
							</div>
							<div class="row">
								<div class="col-6" v-for="(group, groupIndex) in round.groups">
									<h6 class="font-weight-bold mb-0">{{ getGroupName(groupIndex, roundIndex, -1) }}</h6>
									<p class="text-muted small mb-0" v-if="group.type === 'round_robin'">Teams play each other {{ group.teams_play_each_other }}</p>
									<ul class="list-unstyled mb-4">
										<li v-if="group.type === 'round_robin'" v-for="(team, teamIndex) in group.teams">
											<span v-if="roundIndex == 0">Team {{ teamIndex + 1 }}</span>
											<span v-if="roundIndex > 0">{{ getMatchDetail(team.position, team.position_type) }}</span>
										</li>
										<li v-if="group.type === 'placing_match'" v-for="(team, teamIndex) in group.teams">
											<div v-if="teamIndex % 2 === 0">
												<span v-if="roundIndex == 0">{{ 'Team ' + (parseInt(group.teams[teamIndex].position) + 1) + ' vs ' + 'Team ' + (parseInt(group.teams[teamIndex + 1].position) + 1) }}</span>
												<span v-if="roundIndex > 0">{{ getMatchDetail(team.position, team.position_type) + ' vs ' + getMatchDetail(group.teams[teamIndex + 1].position, group.teams[teamIndex + 1].position_type) }}</span>
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
									<ul class="list-unstyled mb-4">
										<li v-for="(team, teamIndex) in division.teams">
											<span>{{ (teamIndex + 1) + '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + getMatchDetail(team.position, team.position_type) }}</span>
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
													<ul class="list-unstyled mb-4">
														<li v-if="group.type === 'round_robin'" v-for="(team, teamIndex) in group.teams">
															<span v-if="roundIndex == 0">Team {{ teamIndex + 1 }}</span>
															<span v-if="roundIndex > 0">{{ getMatchDetail(team.position, team.position_type) }}</span>
														</li>
														<li v-if="group.type === 'placing_match'" v-for="(team, teamIndex) in group.teams">
															<div v-if="teamIndex % 2 === 0">
																<span v-if="roundIndex == 0">{{ 'Team ' + (parseInt(group.teams[teamIndex].position) + 1) + ' vs ' + 'Team ' + (parseInt(group.teams[teamIndex + 1].position) + 1) }}</span>
																<span v-if="roundIndex > 0">{{ getMatchDetail(team.position, team.position_type) + ' vs ' + getMatchDetail(group.teams[teamIndex + 1].position, group.teams[teamIndex + 1].position_type) }}</span>
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

					<div class="card mb-3">
						<div class="card-block">
							<div class="row align-items-center">
								<div class="col-12">
									<h6 class="font-weight-bold">Placings</h6>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<ul class="list-unstyled mb-4">
										<li v-for="(placing, placingIndex) in templateFormDetail.stepthree.placings"><span>{{ getSuffixForPosition((placingIndex + 1)) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + getMatchDetail(placing.position, placing.position_type) }}</span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<form>
						<div class="form-group" :class="{'has-error': errors.has('remarks') }">
							<label for="remarks">Remarks</label>
							<input name="remarks" type="text" class="form-control" v-model="templateFormDetail.stepfour.remarks" v-validate="'required'" :class="{'is-danger': errors.has('remarks') }" placeholder="Remarks">
							<i v-show="errors.has('remarks')" class="fa fa-warning"></i>
                        	<span class="help is-danger" v-show="errors.has('remarks')">{{ errors.first('remarks') }}</span>
						</div>

						<div class="form-group">
							<label for="remarks">Round schedule</label>
							<div v-for="(roundSchedule, index) in templateFormDetail.stepfour.roundSchedules" class="row">
								<div class="col-sm-6 col-md-4 col-lg-4 col-xl-4">
									<div class="form-group" :class="{'has-error': errors.has('round_schedule'+index) }">
										<input :name="'round_schedule'+index" type="text" v-model="templateFormDetail.stepfour.roundSchedules[index]" class="form-control" placeholder="Round schedule" :class="{'is-danger': errors.has('round_schedule'+index) }" v-validate="'required'" data-vv-as="Round schedule">
									    <div class="error-block">
									        <i v-show="errors.has('round_schedule'+index)" class="fas fa-warning"></i>
									        <span class="help is-danger" v-show="errors.has('round_schedule'+index)">{{ errors.first('round_schedule'+index) }}</span>
									    </div>										
									</div>
								</div>

								<div class="col-sm-2 col-md-2 col-lg-2 col-xl-3 text-left text-sm-center" v-if="templateFormDetail.stepfour.roundSchedules.length > 1">
									<div class="form-group">
					                    <p class="m-0"><a href="javascript:void(0)" class="text-primary" @click="removeRoundSchedule(index)"><u>Delete</u></a></p>
					                </div>
								</div>
							</div>

							<button type="button" class="btn btn-primary" @click="addNewRoundSchedule()">Add</button>
						</div>

						<div class="form-group row">
							<label class="col-12 form-control-label">Colour</label>
							<div class="col-12">
								<div class="template-font-color-box pull-left mr-2" @click="setTemplateFontColor(color)" v-for="color in templateFontColors" :style="{'background-color': color}" :class="{ 'template-font-color-active' : templateFormDetail.stepfour.template_font_color == color }" ></div>
								<input type="hidden" name="template_font_color" v-model="templateFormDetail.stepfour.template_font_color" v-validate="'required'" :class="{'is-danger': errors.has('template_font_color') }" data-vv-as="template font color">
							</div>
							<div class="col-12">
								<i v-show="errors.has('template_font_color')" class="fa fa-warning"></i>
    	                    	<span class="help is-danger" v-show="errors.has('template_font_color')">{{ errors.first('template_font_color') }}</span>						
							</div>
						</div>					
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
            	templateFontColors: [
            		'rgb(146,208,80)', 'rgb(255,192,0)', 'rgb(217,149,148)'
            	],
            }
        },
        created() {
            this.$root.$on('updateTemplateData', this.updateTemplateData);
        },
        beforeCreate: function() {
            this.$root.$off('updateTemplateData');
        },
        computed: {
        	
        },
        methods: {
        	saveTemplateDetail() {
        		var templateData = {'templateFormDetail': this.templateFormDetail};
        		this.$validator.validateAll().then((response) => {
	        		if(response) {
	        			var templateData = { 'templateFormDetail': this.templateFormDetail };
	        			if(this.editedTemplateId) {
	        				templateData.editedTemplateId = this.editedTemplateId;
	        				Template.updateTemplateDetail(templateData).then(
			        			(response) => {
			        			},
			        			(error) => {
			        			}
			        		);
	        			} else {
			        		Template.saveTemplateDetail(templateData).then(
			        			(response) => {
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
        		this.$emit('change-tab-index', 4, 3, 'stepfour', _.cloneDeep(this.templateFormDetail.stepfour));
        	},
        	setTemplateFontColor(color) {
        		this.templateFormDetail.stepfour.template_font_color = color;
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

			    	if(groupData.type === 'round_robin') {
			    		groupName = 'Group ' + this.getRoundRobinGroupName(roundData, groupIndex);
			    		return this.getSuffixForPosition(position) + ' - ' +  groupName;
			    	}

			    	if(groupData.type === 'placing_match') {
			    		groupName = 'PM ' + this.getPlacingMatchGroupName(roundData, groupIndex);
			    		return (positionType.charAt(0).toUpperCase() + positionType.slice(1)) + ' ' + groupName + ' Match ' + position;
			    	}
			    }
		    },
		    getDivisions() {
		    	let divisions = new Array();
		    	divisions[-1]['rounds'] = new Array();
		    	divisions[-1]['rounds'] = this.templateFormDetail.steptwo.rounds;
		    	divisions.push(this.templateFormDetail.steptwo.divisions);
		    },
		    addNewRoundSchedule() {
		    	this.templateFormDetail.stepfour.roundSchedules.push('');
		    },
		    removeRoundSchedule(index) {
		    	this.templateFormDetail.stepfour.roundSchedules.splice(index, 1);
		    }
        }
    }
</script>