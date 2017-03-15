<template> 
  <div class="tab-content">
  	<div class="card">
  		<div class="card-block">
  			<h6><strong>Teams and groups</strong></h6>
  			<form>
  				<div class="form-group row">
                    <label class="col-sm-2 form-control-label">Age category</label>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <select class="form-control ls-select2">
	                            <option value="">Select age category</option>
	                            <option value="">U9</option>
	                            <option value="">U11</option>
	                            <option value="">U15 </option>
	                            <option value="">Etc...</option>
	                        </select>
	                    </div>
                    </div>
                </div>
  			</form>
  			<div class="age-category">
  				<div class="d-flex justify-content-center align-items-center">
  					<div class="col-sm-3">
  						<div class="m_card hoverable">
                <div class="card-content">
    							  <span class="card-title">GROUP A</span>
      							<p>Barmstedt BV</p>
      							<p>A2</p>
      							<p>A3</p>
      							<p>A4</p>
                  </div>
  						</div>
  					</div>
  					<div class="col-sm-3">
  						<div class="m_card hoverable">
                <div class="card-content">
    							  <span class="card-title">GROUP B</span>
      							<p>Barmstedt BV</p>
      							<p>A2</p>
      							<p>A3</p>
      							<p>A4</p>
                  </div>
  						</div>
  					</div>
  					<div class="col-sm-3">
  						<div class="m_card hoverable">
                <div class="card-content">
    							  <span class="card-title">GROUP C</span>
      							<p>Barmstedt BV</p>
      							<p>A2</p>
      							<p>A3</p>
      							<p>A4</p>
                  </div>
  						</div>
  					</div>
  					<div class="col-sm-3">
  						<div class="m_card hoverable">
                <div class="card-content">
    							  <span class="card-title">GROUP D</span>
      							<p>Barmstedt BV</p>
      							<p>A2</p>
      							<p>A3</p>
      							<p>A4</p>
                  </div>
  						</div>
  					</div>
  				</div>
  			</div>
  			<div class="clearfix">
  				<div class="pull-left">
	  				<div class="mt-4"><strong>Team list</strong></div>
	  				<button type="button" class="btn btn-primary">Upload teams</button>
	  			</div>
	  			<div class="pull-right mt-4">
	  				<form class="form-inline filter-category-form">
	  					<div class="form-group">
	  						<label for="nameInput" class="control-label"><strong>Filter by:</strong></label> 
	  						<label class="radio-inline control-label">
  								<input type="radio" name="gender" value="team" checked="checked"> Team
                            </label>
                             <label class="radio-inline control-label">
                            	<input type="radio" name="gender" value="country"> Country
                            </label>
                            <label class="radio-inline control-label">
                            	<input type="radio" name="gender" value="age category"> Age Category
                            </label>
                            <select class="form-control ls-select2">
	                            <option value="">Select a location</option>
	                            <option value="">Location 1</option>
	                            <option value="">Location 2</option>
	                            <option value="">Location 3 </option>
	                            <option value="">Location 4</option>
	                            <option value="">Etc...</option>
	                        </select>
	                        <label class="control-label">
	                        	<a href="#">Clear</a>
	                        </label>
	  					</div> 
	  					
	  				</form>
	  			</div>
  			</div>
  			<div class="row mt-4">
  				<div class="col-md-12">
  					<table class="table add-category-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Name</th>
                                <th>Country</th>
                                <th>Age Category</th>
                                <th>Group</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1234567</td>
                                <td>Jerez FC</td>
                                <td>
                                	<img src="/assets/img/flag.png" width="20"> Spain
                                </td>
                                <td>U11</td>
                                <td>
                                	<select class="form-control ls-select2">
			                            <option value="">Select a location</option>
			                            <option value="">Location 1</option>
			                            <option value="">Location 2</option>
			                            <option value="">Location 3 </option>
			                            <option value="">Location 4</option>
			                            <option value="">Etc...</option>
			                        </select>
                                </td>
                            </tr>
                            <tr>
                                <td>John</td>
                                <td>Smith</td>
                                <td>
                                	<img src="/assets/img/flag.png" width="20"> Germany
                                </td>
                                <td>Blackpool Juniors FC</td>
                                <td>
                                	<select class="form-control ls-select2">
			                            <option value="">Select a location</option>
			                            <option value="">Location 1</option>
			                            <option value="">Location 2</option>
			                            <option value="">Location 3 </option>
			                            <option value="">Location 4</option>
			                            <option value="">Etc...</option>
			                        </select>
                                </td>
                            </tr>
                            <tr>
                                <td>1234567</td>
                                <td>Jerez FC</td>
                                <td>
                                	<img src="/assets/img/flag.png" width="20"> Spain
                                </td>
                                <td>U11</td>
                                <td>
                                	<select class="form-control ls-select2">
			                            <option value="">Select a location</option>
			                            <option value="">Location 1</option>
			                            <option value="">Location 2</option>
			                            <option value="">Location 3 </option>
			                            <option value="">Location 4</option>
			                            <option value="">Etc...</option>
			                        </select>
                                </td>
                            </tr>
                            <tr>
                                <td>John</td>
                                <td>Smith</td>
                                <td>
                                	<img src="/assets/img/flag.png" width="20"> Germany
                                </td>
                                <td>Blackpool Juniors FC</td>
                                <td>
                                	<select class="form-control ls-select2">
			                            <option value="">Select a location</option>
			                            <option value="">Location 1</option>
			                            <option value="">Location 2</option>
			                            <option value="">Location 3 </option>
			                            <option value="">Location 4</option>
			                            <option value="">Etc...</option>
			                        </select>
                                </td>
                            </tr>
                            <tr>
                                <td>1234567</td>
                                <td>Jerez FC</td>
                                <td>
                                	<img src="/assets/img/flag.png" width="20"> Spain
                                </td>
                                <td>U11</td>
                                <td>
                                	<select class="form-control ls-select2">
			                            <option value="">Select a location</option>
			                            <option value="">Location 1</option>
			                            <option value="">Location 2</option>
			                            <option value="">Location 3 </option>
			                            <option value="">Location 4</option>
			                            <option value="">Etc...</option>
			                        </select>
                                </td>
                            </tr>
                            <tr>
                                <td>John</td>
                                <td>Smith</td>
                                <td>
                                	<img src="/assets/img/flag.png" width="20"> Germany
                                </td>
                                <td>Blackpool Juniors FC</td>
                                <td>
                                	<select class="form-control ls-select2">
			                            <option value="">Select a location</option>
			                            <option value="">Location 1</option>
			                            <option value="">Location 2</option>
			                            <option value="">Location 3 </option>
			                            <option value="">Location 4</option>
			                            <option value="">Etc...</option>
			                        </select>
                                </td>
                            </tr>
                            <tr>
                                <td>1234567</td>
                                <td>Jerez FC</td>
                                <td>
                                	<img src="/assets/img/flag.png" width="20"> Spain
                                </td>
                                <td>U11</td>
                                <td>
                                	<select class="form-control ls-select2">
			                            <option value="">Select a location</option>
			                            <option value="">Location 1</option>
			                            <option value="">Location 2</option>
			                            <option value="">Location 3 </option>
			                            <option value="">Location 4</option>
			                            <option value="">Etc...</option>
			                        </select>
                                </td>
                            </tr>
                            <tr>
                                <td>John</td>
                                <td>Smith</td>
                                <td>
                                	<img src="/assets/img/flag.png" width="20"> Germany
                                </td>
                                <td>Blackpool Juniors FC</td>
                                <td>
                                	<select class="form-control ls-select2">
			                            <option value="">Select a location</option>
			                            <option value="">Location 1</option>
			                            <option value="">Location 2</option>
			                            <option value="">Location 3 </option>
			                            <option value="">Location 4</option>
			                            <option value="">Etc...</option>
			                        </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary pull-right">Update Groups</button>
  				</div>
  			</div>
  		</div>
  	</div>
  </div>
</template>

<script type="text/babel">
	export default {	
        
    }
</script>