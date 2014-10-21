<!DOCTYPE html5>
<html lang="en">
    <head>
        <base href="/Eshop/"/>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Preview product page</title>

        <!-- Bootstrap -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom site  css -->
        <link href="assets/css/site.css" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body id="registration-body">
        <div class="container" id="wrap">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <form action="r" method="post" accept-charset="utf-8" class="form" role="form">   <legend>Sign Up</legend>
                        <h4>It's free and always will be.</h4>
                        <div class="row">
                            <div class="col-xs-6 col-md-6">
                                <input type="text" name="firstname" value="" class="form-control input-lg" placeholder="First Name"  />                        </div>
                            <div class="col-xs-6 col-md-6">
                                <input type="text" name="lastname" value="" class="form-control input-lg" placeholder="Last Name"  />                        </div>
                        </div>
                        <input type="text" name="email" value="" class="form-control input-lg" placeholder="Your Email"  /><input type="password" name="password" value="" class="form-control input-lg" placeholder="Password"  /><input type="password" name="confirm_password" value="" class="form-control input-lg" placeholder="Confirm Password"  />                    <label>Birth Date</label>                    <div class="row">
                        </div>
                        <br />
                        <span class="help-block">By clicking Create my account, you agree to our Terms and that you have read our Data Use Policy, including our Cookie Use.</span>
                        <button class="btn btn-lg btn-primary btn-block signup-btn" type="submit">
                            Create my account</button>
                    </form>          
                </div>
            </div>            
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/libs/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/js/libs/bootstrap.min.js"></script>
    <!-- genaral javascript file -->
    <script src="assets/js/main.js"></script>
    <!-- page secific script -->
    <script src="assets/js/pages/register.js"></script>
</body>
</html>