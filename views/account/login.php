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
    <body>
        <section id="login">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-wrap">
                            <h1>Log in with your email account</h1>
                            <form role="form"  method="post" id="login-form" autocomplete="off">
                                <div id="statusLabel"></div>
                                <div class="form-group">
                                    <label for="email" class="sr-only">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="key" class="sr-only">Password</label>
                                    <input type="password" name="password" id="key" class="form-control" placeholder="Password">
                                </div>
                                <div class="checkbox">
                                    <span class="character-checkbox" id="showPassword-btn"></span>
                                    <span class="label">Show password</span>
                                </div>
                                <input type="button" id="login-btn" class="btn btn-custom btn-lg btn-block" value="Log in">
                            </form>
                            <hr>
                        </div>
                    </div> <!-- /.col-xs-12 -->
                </div> <!-- /.row -->
            </div> <!-- /.container -->
        </section>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="assets/js/libs/jquery-1.11.1.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="assets/js/libs/bootstrap.min.js"></script>
        <!--script for validation -->
        <script src="assets/js/libs/jquery.validate.min.js"></script>
        <!-- genaral javascript file -->
        <script src="assets/js/main.js"></script>
        <!-- page secific script -->
        <script src="assets/js/pages/login.js"></script>
    </body>
</html>