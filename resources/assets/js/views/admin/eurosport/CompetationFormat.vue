<template> 
	<div class="tab-content">
		<div class="card">
            <div class="card-block">
                <h6 class="mt4"><strong>Age categories</strong></h6>            	
                <div class="row">
                    <div class="col-md-12">                       
                        <competationFormatList></competationFormatList>
                    </div>
                </div>
            	<div class="row">
                    <div class="col-md-12">
            		     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Add Age Category</button>
                    </div>
            	</div>                
            	<AddAgeCateogryModel></AddAgeCateogryModel>
            </div>
		</div>	
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true" ></i>  Back</button>
                </div>
                <div class="pull-right">
                  <button class="btn btn-primary" @click="next()"> <i class="fa fa-angle-double-right" aria-hidden="true" ></i>  Next</button>
                </div>
            </div>
        </div>
	</div>
</template>

<script type="text/babel">
import AddAgeCateogryModel from '../../../components/AddAgeCategoryModal.vue'
import CompetationFormatList from '../../../components/CompetationFormatList.vue'
export default {	  
  components: {
      AddAgeCateogryModel, CompetationFormatList
  },
  methods: {
    next() {
      this.$root.$emit('setTemplate')
    },
    backward() {
     // this.$router.push({name: 'tournament_add'})   
    }
  }
}
</script>