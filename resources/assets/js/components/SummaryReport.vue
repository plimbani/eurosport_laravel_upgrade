<template>
	<div class="tab-content summary-report-content">
		<h6><strong>Reports</strong></h6>
		<div class="row">
			<div class="col-md-6">
				<span>Use the filters below to generate a report.</span>
			</div>
			<div class="col-md-6 text-right">
				<button type="button" class="btn btn-primary">Download</button>
                <button type="submit" class="btn btn-primary">Print</button>
			</div>
		</div>
		<div class="block-bg mt-4">
			<form class="col-md-12 report_form">
				<div class="form-group row text-left">
					<div class="col-md-7 row">
						<div class="col-md-4 mb-7">
							<label><strong>Age category</strong></label>
							<div class="form-group">
	                            <select class="form-control ls-select2">
		                            <option value="">Select</option>
		                            <option value="">-----------</option>
		                        </select>
		                    </div>
						</div>
						<div class="col-md-4 mb-7">
							<label><strong>Club</strong></label>
							<div class="form-group">
	                            <select class="form-control ls-select2">
		                            <option value="">Select</option>
		                            <option value="">-----------</option>
		                        </select>
		                    </div>
						</div>
						<div class="col-md-4 mb-7">
							<label><strong>Team</strong></label>
							<div class="form-group">
	                            <select class="form-control ls-select2">
		                        	<option value="">Select</option>
		                            <option value="">-----------</option>                          
		                        </select>
		                    </div>
						</div>
					</div>
					<div class="col-md-5 row">
						<div class="col-md-6 mb-5">
							<label><strong>From</strong></label>
							<div class="form-group">
	                            <select class="form-control ls-select2">
		                        	<option value="">Select</option>
		                            <option value="">-----------</option>                          
		                        </select>
		                    </div>
						</div>
						<div class="col-md-6 mb-5">
							<label><strong>To</strong></label>
							<div class="form-group">
	                            <select class="form-control ls-select2">
		                        	<option value="">Select</option>
		                            <option value="">-----------</option>                          
		                        </select>
		                    </div>
						</div>
					</div>
					<div class="col-md-7 row">
						<div class="col-md-4 mb-7">
							<label><strong>Location</strong></label>
							<div class="form-group">
	                            <select class="form-control ls-select2">
		                            <option value="">Select</option>
		                            <option value="">-----------</option>
		                        </select>
		                    </div>
						</div>
						<div class="col-md-4 mb-7">
							<label><strong>Pitch</strong></label>
							<div class="form-group">
	                            <select class="form-control ls-select2">
		                            <option value="">Select</option>
		                            <option value="">-----------</option>
		                        </select>
		                    </div>
						</div>
						<div class="col-md-4 mb-7">
							<label><strong>Referee</strong></label>
							<div class="form-group">
	                            <select class="form-control ls-select2">
		                        	<option value="">Select</option>
		                            <option value="">-----------</option>                          
		                        </select>
		                    </div>
						</div>
					</div>
					<div class="col-md-5 text-right">
						<button type="button" class="btn btn-primary">Clear</button>
                		<button type="submit" class="btn btn-primary">Generate</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-12 row mt-4">
			<table class="table">
				<thead>
                    <tr>
                        <th>Date (time)</th>
                        <th>Age category</th>
                        <th>Location</th>
                        <th>Pitch</th>
                        <th>Referee</th>
                        <th>Game</th>
                    </tr>
                </thead>
                <tbody>
                	<tr>
                		<td>Sat 21.04.2017 (10:30)</td>
                		<td>U17</td>
                		<td>Cadiz Real Sports Club</td>
                		<td>Pitch 1</td>
                		<td>Paul Jones</td>
                		<td>Jerez FC   Vs  Schleswig Holstein SV</td>
                	</tr>
                	<tr>
                		<td>Sat 21.04.2017 (10:00)</td>
                		<td>U15</td>
                		<td>Cadiz Real Sports Club</td>
                		<td>Pitch 2</td>
                		<td>John Smith</td>
                		<td>Jerez FC   Vs  Schleswig Holstein SV</td>
                	</tr>
                	<tr>
                		<td>Sat 21.04.2017 (12:45)</td>
                		<td>U17</td>
                		<td>Jerez FC</td>
                		<td>Pitch 3</td>
                		<td>Johan Seeker</td>
                		<td>Jerez FC   Vs  Schleswig Holstein SV</td>
                	</tr>
                	<tr>
                		<td>Etc...</td>
                		<td>Etc...</td>
                		<td>Etc...</td>
                		<td>Etc...</td>
                		<td>Etc...</td>
                		<td>Etc...</td>
                	</tr>
                </tbody>
			</table>
		</div>
	</div>

</template>

<script type="text/babel">
	
	
</script>