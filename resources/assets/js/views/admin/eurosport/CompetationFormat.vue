<template> 
	<div class="tab-content">
		<div class="card">
            <div class="card-block">
                <h6 class="mt4"><strong>Age categories</strong></h6>
            	<!-- <div class="competition_list row">
            		<div class="col-md-2">
            			<span>Age category</span>
            		</div>
            		<div class="col-md-2">
            			<span>Competition format</span>
            		</div>
            		<div class="col-md-2">
            			<span>Total matches</span>
            		</div>
            		<div class="col-md-2">
            			<span>Total time</span>
            		</div>
            		<div class="col-md-2">
            			<span>Total time</span>
            		</div>
            		<div class="col-md-2">
            			<span>Edit</span>
            		</div>
            	</div> -->
                <table class="table table-hover table-striped table-bordered add-category-table">
                    <thead>
                        <tr>
                            <th>Age category</th>
                            <th>Competition format</th>
                            <th>Total matches</th>
                            <th>Total time</th>
                            <th>Match schedule</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>U11</td>
                            <td class="">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="exampleRadios" id="exampleRadios1" value="option1"
                                               checked>
                                        6 teams, RR1, EM
                                    </label>
                                </div>
                            </td>
                            <td class="">20</td>
                            <td class="">13 hours 40 minutes</td>
                            <td class="">
                                <a href="#">Veiw</a>
                            </td>
                            <td>
                                <a href="#">Edit</a> &nbsp;&nbsp;&nbsp;
                                <a href="#">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                                        6 teams, RR1, PM/F
                                    </label>
                                </div>
                            </td>
                            <td>18</td>
                            <td>12 hours 50 minutes</td>
                            <td>
                                <a href="">View</a>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            	<div class="row">
                    <div class="col-md-12">
            		     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Add Age Category</button>
                    </div>
            	</div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="add-text">Etc...</p>
                    </div>
                </div>
            	<AddAgeCateogryModel></AddAgeCateogryModel>
            </div>
		</div>	
	</div>
</template>

<script type="text/babel">
import AddAgeCateogryModel from '../../../components/AddAgeCategoryModal.vue'
export default {	
    components: {
        AddAgeCateogryModel
    }
}
</script>