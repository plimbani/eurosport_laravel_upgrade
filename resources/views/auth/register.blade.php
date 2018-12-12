<html lang="en">
<head>
  <title>Euro-Sportring</title>
  
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>
  <body>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="js-register-form form-horizontal" role="form" method="POST" action="{{ route('commerialisation.buylicense') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="col-md-4 control-label">First Name</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						 <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('first_name') }}" required autofocus>

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('organisation') ? ' has-error' : '' }}">
							<label for="organisation" class="col-md-4 control-label">Organization or Company Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control form-control-danger" placeholder="Organisation" id="organisation" name="organisation" value='{{ old('organisation') }}'>
								@if ($errors->has('organisation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('organisation') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
							<label for="job_title" class="col-md-4 control-label">Your Job Title</label>
							<div class="col-md-6">
								<input type="text" class="form-control form-control-danger" value='{{ old('job_title') }}' placeholder="Job Title" id="job_title" name="job_title" >
								@if ($errors->has('job_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('job_title') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
							<label for="address" class="col-md-4 control-label">Address</label>
							<div class="col-md-6">
								<input type="textarea" value='{{ old('address') }}' class="form-control form-control-danger" placeholder="Address" id="address" name="address" >
								@if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
							<label for="city" class="col-md-4 control-label">Town or City</label>
							<div class="col-md-6">
								<input type="textarea" class="form-control form-control-danger" value='{{ old('city') }}' placeholder="City" id="city" name="city" >
								@if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
							<label for="zip" class="col-md-4 control-label">Zip or Postal Code</label>
							<div class="col-md-6">
								<input type="textarea" class="form-control form-control-danger" value='{{ old('zip') }}' placeholder="Zip" id="zip" name="zip" >
								@if ($errors->has('zip'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('zip') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group">
							<label for="country" class="col-md-4 control-label">Country</label>
                                                        <div class="col-md-6">
                                                            <select name="country" id="filter-country" value='{{ old('country') }}'class="form-control form-control-danger"  >
                                                                    <option value="">Select Country</option></option>
                                                             @foreach($countries as $country => $id)
                                    <option value="{{ $id }}">{{ $country }}</option>
                                @endforeach
                                                            </select>
                                                     </div>
						</div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

