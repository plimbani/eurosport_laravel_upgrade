<template>
	<div>
	    <div class="bordered-box">
	        <h6 class="font-weight-bold">Group {{ groupIndex + 1}} <span class="pull-right"><a href="javascript:void(0)" @click="removeGroup()"><i class="fa fa-trash"></i></a></span></h6>
	        <div class="form-group">
	            <div class="radio">
	                <label><input type="radio" name="grouping-method" checked="checked"> Round robin</label>
	                <label><input type="radio" name="grouping-method"> Placing match</label>
	            </div>
	        </div>
	        <div class="row">
	            <div class="col-md-6">
	                <div class="form-group mb-0">
	                    <label>Number of teams in group</label>
	                    <select class="form-control ls-select2" name="no_of_teams" id="no_of_teams" v-model="formValues.no_of_teams">
	                    	<option v-for="n in 28" v-if="n >=2" :value="n">{{ n }}</option>
	                    </select>
	                </div>
	            </div>

	            <div class="col-md-6">
	                <div class="form-group mb-0">
	                    <label>Teams play each other</label>
	                    <select class="form-control ls-select2" name="teams_play_each_other" id="teams_play_each_other" v-model="formValues.teams_play_each_other">
	                        <option value="once">Once</option>
	                        <option value="twice">Twice</option>
	                        <option value="three_times">Three times</option>
	                        <option value="four_times">Four times</option>
	                    </select>
	                </div>
	            </div>
	        </div>
	    </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        data() {
            return {
                formValues: {
                	grouping_method: {
                		round_robin: '',
                		placing_match: ''
                	},
                	no_of_teams: '',
                	teams_play_each_other: ''
                },
            }
        },
        props: ['groupIndex', 'roundIndex'],
        components: {
        },
        mounted() {
        },
        methods: {
        	removeGroup() {
        		this.$parent.removeGroup(this.groupIndex, this.roundIndex);
        	}
        }
    }
</script>