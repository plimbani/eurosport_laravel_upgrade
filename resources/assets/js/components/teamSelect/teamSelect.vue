<template>
	<select :disabled="!canChangeTeamOption" v-bind:data-id="team.id" v-model="team.group_name" :name="'sel_'+team.id" :id="'sel_'+team.id" class="form-control ls-select2 selTeams">
	  <option value="" class="blnk">{{seleTeam}}</option>
	  <optgroup :label="getGroupName(group)"
	  v-for="group in grps">
	    <option :class="'sel_'+team.id" v-for="(n,index) in group['group_count']" :disabled="isSelected(group['groups']['group_name'],n)" :value="getGroupValueInSelection(group, n)" >{{ getGroupDisplayNameInSelection(group, n) }} </option>
	  </optgroup>
	</select>
</template>
<script type="text/babel">
	export default {
		data() {
			return {
				'seleTeam':'De-select',
			}
		},
		props: ['team','grps', 'canChangeTeamOption'],
		mounted: function () {
			var vm = this
		    $(this.$el)
		      .select2({
		      	minimumResultsForSearch: Infinity,
		      })
		      .on('select2:select', function () {
		        vm.$emit('assignTeamGroupName', $(this).data('id'), $(this).val())
		        vm.$emit('onAssignGroup', $(this).data('id'))
				$('select').select2();
		      })
		      .on('select2:opening', function () {
		      	if(vm.canChangeTeamOption) {
		        	vm.$emit('beforeChange', $(this).data('id'))
		        }
		      })
		      .on('select2:open', function() {
		      	if(vm.canChangeTeamOption === false) {
		      		toastr['error']('Can not change team as one or more scores for this team are already entered.', 'Error');
		      		$('#sel_' + vm.team.id).select2('close');
		      	}
		      })
	  	},
	  	methods:{
	  		getGroupName(group) {
		        let splitGroupName = group['name'].split('-');
		        let competitionType = splitGroupName[0];
		        if( competitionType == 'PM' ) {
		          return group['groups']['group_name'].replace('Group-', '')
		        }
		        return group['groups']['group_name']
	      	},
	  		isSelected(grp,index){
				if (this.$parent.selectedGroupsTeam.includes(grp + index)) {
					return true;
				} else {
					return false;
				}
	      	},
	      	getGroupValueInSelection(group, n) {
		        let splitGroupName = group['name'].split('-');
		        let competitionType = splitGroupName[0];
		        if( competitionType == 'PM') {
		          return group['groups']['actual_group_name'] + '-' + n
		        }
		        return group['groups']['group_name'] + n
	      	},
	      	getGroupDisplayNameInSelection(group, n) {
		        let splitGroupName = group['name'].split('-');
		        let competitionType = splitGroupName[0];
		        if( competitionType == 'PM') {
		          let actualGroupName = group['groups']['actual_group_name'].split('-');
		          return actualGroupName[0] + '-' + n
		        }
		        return (group['groups']['group_name']).replace('Group-','') + n
		    }
	  	}
	}
</script>