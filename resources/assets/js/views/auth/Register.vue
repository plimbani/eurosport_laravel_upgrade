<template>
    <form action="" id="registerForm" method="post">
        {{csrf_field()}}
		<div class="form-group">
			<label>Your Name</label>
            <input type="text" class="form-control form-control-danger" placeholder="First Name" id = "first_name" name="first_name">
            <input type="text" class="form-control form-control-danger" placeholder="Sur Name" id = "last_name" name="last_name">
        </div>
        <div class="form-group">
			<label>Email Address</label>
            <input type="email" class="form-control form-control-danger" placeholder="Email" id = "email" name="email">
        </div>
        <div class="form-group">
			<label>Password</label>
            <input type="password" class="form-control form-control-danger" placeholder="Enter Password" name="password" id="password">
            <input type="password" class="form-control form-control-danger" placeholder="Retype Password" name="password_confirmation">
        </div>
		<div class="form-group">
			<label>Organization or Company Name</label>
            <input type="text" class="form-control form-control-danger" placeholder="Company Name" id="company_name" name="company_name" >
        </div>
		<div class="form-group">
			<label>Your Job Title</label>
            <input type="text" class="form-control form-control-danger" placeholder="Job Title" id="job_title" name="job_title" >
        </div>
		<div class="form-group">
			<label>Address</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="Address" id="address" name="address" >
        </div>
		<div class="form-group">
			<label>Town or City</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="City" id="city" name="city" >
        </div>
		<div class="form-group">
			<label>Zip or Postal Code</label>
            <input type="textarea" class="form-control form-control-danger" placeholder="Zip" id="zip" name="zip" >
        </div>
		<div class="form-group">
			<label>Country</label>
            <select></select>
        </div>
        <button class="btn btn-login btn-full">Register</button>
    </form>
</template>