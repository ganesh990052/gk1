<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        .form-gap {
            padding-top: 70px;
        }
    </style>
</head>
<body>
    
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<div class="form-gap"></div>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <h3><i class="fa fa-lock fa-4x"></i></h3>
                  <h2 class="text-center">Forgot Password?</h2>
                  <p>You can reset your password here.</p>
                  <div class="panel-body">
    
                   <form id="register-form" autocomplete="off" action="{{ url('api/reset-password') }}" class="form" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                <input name="password" placeholder="New Password" class="form-control" type="password" required>
                                
                                 <input type="hidden" name="email" value="{{ $email }}">
                                 <input type="hidden" name="token" value="{{ $token }}">
                                 
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                <input name="password_confirmation" placeholder="Confirm New Password" class="form-control" type="password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary btn-block">Reset Password</button>
                        </div>
                    </form>
                    
                    <!-- Display error message -->
                    <div id="error-message" style="color: red; display: none;"></div>
                    
                    <!-- Display success message -->
                    <div id="success-message" style="color: green; display: none;"></div>

    
                  </div>
                </div>
              </div>
            </div>
          </div>
	</div>
</div>






   
</body>
</html>
