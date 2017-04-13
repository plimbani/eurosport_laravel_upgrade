<template> 
	<div class="tab-content">
		<div class="card">
            <div class="card-block">
                <h6 class="mt4"><strong>{{$lang.competation_age_categories}}</strong></h6>            	
                <div class="row">
                    <div class="col-md-12">                       
                        <competationFormatList></competationFormatList>
                    </div>
                </div>
            	<div class="row">
                    <div class="col-md-12">
            		     <button type="button" class="btn btn-primary" @click="addCategory()"><i class="fa fa-plus"></i>{{$lang.competation_add_age_category}}</button>
                    </div>
            	</div>                
            	<AddAgeCateogryModel></AddAgeCateogryModel>
            </div>
		</div>	
        <!--<div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true" ></i>{{$lang.competation_button_back}}</button>
                </div>
                <div class="pull-right">
                  <button class="btn btn-primary" @click="next()"> <i class="fa fa-angle-double-right" aria-hidden="true" ></i>{{$lang.competation_button_next}}</button>
                </div>
            </div>
        </div>-->
	</div>
</template>

<script type="text/babel">
import AddAgeCateogryModel from '../../../components/AddAgeCategoryModal.vue'
import CompetationFormatList from '../../../components/CompetationFormatList.vue'
export default {	  
  components: {
      AddAgeCateogryModel, CompetationFormatList
  },
  data() {
    return {
      type : 'edit'
    }
  },
  mounted() {
           
    // Here if tournament Id is Not Set Redirect to Login page
    let tournamentId = this.$store.state.Tournament.tournamentId
    if(tournamentId == null || tournamentId == '' || tournamentId == undefined) {
      toastr['error']('Please Select Tournament', 'Error');
      this.$router.push({name: 'welcome'});
    } else {
      // Means Set Here
      
       let currentNavigationData = {activeTab:'competition_format', currentPage: 'Competition Format'}
      this.$store.dispatch('setActiveTab', currentNavigationData)
    }
  },
  methods: {
    next() {
       this.$root.$emit('setTemplate')
    },
    backward() {
     // this.$router.push({name: 'tournament_add'})   
    },
    addCategory() {
      this.type='add'
      $('#exampleModal').modal('show')
    },
  }
}
</script>