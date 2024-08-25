<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <!-- BS LINKS -->
    <link rel="stylesheet" href="bsCSS/bootstrap.css">
    <link rel="stylesheet" href="bsCSS/bootstrap.min.css">
    <script src="bsJS/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Page CSS and JS -->
    <link rel="stylesheet" href="cssFile/signUpCSS.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        
    </style>

    

</head>
<body >
  
    <div class="tabbed-menu">
      <img class="logo" src="images/login/LOGO.png" > 
      <ul class="tabs">
        <li id="tab1" class="selected">Log In</li>
        <li id="tab2">Sign Up</li>
      </ul>
      <div class="contentWrapper">
        <div class="content">
          <div class="page" id="tab1" style="display:block;">
            	
					<p class="login-email input"> 
						<input type="text" name="userEmailLog" id="userEmailLog"  class="innerInput"  maxlength="60" placeholder="E-mail"> 
					</p>
					<p class="login-password input"> 
						<input type="password" name="userPassLog" id="userPassLog" class="innerInput" maxlength="60"  placeholder="Password"> 
					</p> 
					<p class="login-submit"> 
						<button type="button" name="" id="signInButton" class="button-primary" value="Log In">Log In</button>
					</p> 
					<p>
						<span class="login-forget"  title="Send email and get new password">Forgot password?</span>
					</p>
					<span class="loginForgot">
						<hr class="line">
						<p class="login-email input"> 
							<input type="text" name="" id="emailInput"  class="innerInput" maxlength="60" placeholder="Enter your email"> 
						</p>
            <p class="login-submit">
                  <button type="submit" name="retrievePassword" id="retrievePasswordButton" class="button-primary" value="Submit">Submit</button>
            </p>
					</span>
				
          </div>
          <!-- <div class="page" id="tab2" style="display:none;">
          <p class="login-student# input"> 
						<input type="text" name="athNum" id="athNum" class="innerInput" maxlength="11"  placeholder="Student Number" required> 
            <span id="athNumError" class="error-message">Invalid Student Number</span>
					</p><br>
          <style>
          .error-message {
              color: red;
              font-size: 0.9em;
              margin-top: 5px;
              display: none;
          }
          </style> -->
          <div class="page" id="tab2" style="display:none;"><div class="page" id="tab2" style="display:none;">
          
              <div class="input-group">
              <p class="login-email inputss"> 
                  <input type="text" name="athNum" id="athNum" class="form-control innerInput" maxlength="11" placeholder="Student Number" required>
              </p>
              </div>
              <div id="athNumError" class="error-message collapse">Invalid Student Number</div>
          
          <style>
              .error-message {
                  color: red;
                  font-size: 0.9em;
                  margin-top: 5px;
              }
          </style>

					<p class="login-username2 input"> 
						<input type="text" name="athFirst" id="athFirst" class="innerInput form-control" maxlength="50"  placeholder="First Name"> 
            <div class="invalid-feedback-icon">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div
					</p>
					<p class="login-username2 input"> 
						<input type="text" name="athLast" id="athLast"  class="innerInput form-control" maxlength="50" placeholder="Last Name" required>
            <div class="invalid-feedback-icon">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
					</p>
          <div class="container">
              <p class="login-sport inputs">
                  <select id="sportInput" name="athSport" class="form-control">
                      <option value="--">--</option>
                      <!-- Add other options here -->
                  </select>
                  <div class="invalid-feedback-icon">
                      <i class="bi bi-exclamation-circle-fill"></i>
                  </div>
              </p>
              <p class="login-position inputs"> 
                  <select id="positionInput" name="athPosition" class="form-control">
                      <option value="--">--</option>
                      <!-- Add other options here -->
                  </select>
                  <div class="invalid-feedback-icon">
                      <i class="bi bi-exclamation-circle-fill"></i>
                  </div>
              </p>
          </div>


          <p class="login-email input"> 
            <input type="email" name="athEmail" id="athEmail" class="innerInput form-control" maxlength="60" placeholder="E-mail"> 
            <div class="invalid-feedback-icon">
              <i class="bi bi-exclamation-circle-fill"></i>
            </div>
          </p>

          <p class="login-password input"> 
            <input type="password" name="athPass" id="athPass" class="innerInput form-control" maxlength="60" placeholder="Password"> 
            <div class="invalid-feedback-icon">
              <i class="bi bi-exclamation-circle-fill"></i>
            </div>
          </p>

          <p class="login-password input"> 
            <input type="password" name="athConPass" id="athConPass" class="innerInput form-control" maxlength="60" placeholder="Confirm Password"> 
            <div class="invalid-feedback-icon">
              <i class="bi bi-exclamation-circle-fill"></i>
            </div>
          </p>

					
					<button type="button" id="signUpButton" class="button-primary"><span id="buttonText1">Sign Up</span></button>
					
				
          </div>
        </div>
      </div>
    </div>
    <!-- AJAX link -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="jsFile/signUpJS.js"></script>
    <script src="jsFile/forgotPass.js"></script>
    <script>
        $(document).ready(function() {
            $('.tabs li').click(function(){
                if ($(this).hasClass('selected')===false) {
                $('.tabs li').removeClass('selected');
                $(this).addClass('selected');
                }
                var selectionId = $(this).attr('id');
                $('.content').fadeOut('fast', function(){
                $('div .page').css('display','none');
                $('.page#'+selectionId).css('display','block');
                $('.content').fadeIn('fast');
                });
            });
            
        });

        $('.login-forget').click(function(){
                $('.loginForgot').toggle(400);	
        });

    </script>

</body>
</html>