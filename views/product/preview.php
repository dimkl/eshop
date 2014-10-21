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
        <!-- rating plugn css -->
        <link href="assets/css/jquery.raty.css" rel="stylesheet">
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
        <div class="wrapper">
            <div class="box">
                <div class="row">
                    <!-- sidebar -->
                    <div class="column col-sm-3" id="sidebar">
                        <div class="row">
                            <a class="logo" href="#">B</a>
                            <span><?php echo $user->getFirstname(); ?></span>
                            <span><?php echo $user->getLastname(); ?></span>
                        </div>

                        <ul class="nav">
                            <li class="active"><a href="navigation/account/register">Register</a>
                            </li>
                            <li><a href="navigation/account/login">Login</a>
                            </li>
                        </ul>
                        <ul class="nav hidden-xs" id="sidebar-footer">
                            <li>
                                <a href="http://www.bootply.com">
                                    <h3>Basis</h3>
                                    Made with <i class="glyphicon glyphicon-heart-empty"></i> by Bootply</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /sidebar -->

                    <!-- main -->
                    <div class="column col-sm-9" id="main">
                        <div class="padding">
                            <div class="full col-sm-9">

                                <!-- content -->

                                <div class="col-sm-12" id="featured">   
                                    <div class="page-header text-muted">
                                        Featured
                                    </div> 
                                </div>
                                <?php foreach ($comments as $k => $comment): ?>
                                    <!--/top story-->
                                    <div class="row">    
                                        <div class="col-sm-10">
                                            <h3>This is Some Awesome Featured Content</h3>
                                            <h4 class="col-xs-12">
                                                <span class="label label-default col-xs-3"><?php echo "{$comment->getUser()->getFirstName()} {$comment->getUser()->getLastName()}"; ?> </span>
                                                <span class="raty col-xs-5" data-score="<?php echo $comment->getRating(); ?>" data-number="10" ></span>
                                                <span class="text-muted col-xs-4"><small><?php echo $comment->getCreationDatetime(); ?></small> </span>
                                            </h4>
                                            <h4 class="col-xs-12">
                                                <small class="text-muted"><?php echo $comment->getContent(); ?></small>
                                            </h4>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="#" class="pull-right"><img src="http://api.randomuser.me/portraits/thumb/men/19.jpg" class="img-circle"></a>
                                        </div> 
                                    </div>
                                <?php endforeach; ?>

                            </div><!-- /col-9 -->
                        </div><!-- /padding -->
                    </div>
                    <!-- /main -->

                </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="assets/js/libs/jquery-1.11.1.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="assets/js/libs/bootstrap.min.js"></script>
        <!-- jQuery rating plugin -->
        <script src="assets/js/libs/jquery.raty.js"></script>
        <!-- genaral javascript file -->
        <script src="assets/js/main.js"></script>
        <!-- page secific script -->
        <script src="assets/js/pages/preview.js"></script>
    </body>
</html>