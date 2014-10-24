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
                            <?php if (!is_null($user)): ?>
                                <span><?php echo $user->getFirstname(); ?></span>
                                <span><?php echo $user->getLastname(); ?></span>
                            <?php endif; ?>
                        </div>

                        <ul class="nav">
                            <?php if (is_null($user)): ?>
                                <li class="active"><a href="navigation/account/register">Register</a></li>
                                <li><a href="navigation/account/login">Login</a></li>
                            <?php else: ?>
                                <li><a href="navigation/account/logout">Logout</a></li>
                            <?php endif; ?>
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
                                <!-- pdocut part -->
                                <div>
                                    <div class="col-xs-4">
                                        <img src="assets/images/clickmedia.jpg"/>
                                    </div>
                                    <div class="col-xs-8">
                                        <div>
                                            <?php echo $product->getName() ?>
                                        </div>
                                        <div>
                                            <?php echo $product->getDescription() ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- content -->
                                <?php if ($canComment === TRUE): ?>
                                    <div class="col-sm-12" id="commentForm-panel" >   
                                        <div class="page-header text-muted">
                                            <form class="row" id="commentForm">
                                                <div id="statusLabel"></div>
                                                <input type="hidden" name="productid" value="<?php echo $product->getId() ?>"/>
                                                <div class="col-xs-12 ">
                                                    <span class="raty col-xs-6" data-score="10" data-number="10" ></span>
                                                </div>
                                                <div class="col-xs-12">
                                                    <textarea class="col-xs-6"  name="content" rows="5" cols="5"></textarea>
                                                </div>
                                                <input type="button" value="submit" class="pull-right col-xs-3 col-xs-offset-2" id="commentBtn"/>
                                            </form>
                                        </div> 
                                    </div>
                                <?php endif; ?>
                                <div class="col-sm-12" id="featured">   
                                    <div class="page-header text-muted">
                                        Comments
                                    </div> 
                                </div>
                                <div id="commentPanel">
                                    <?php foreach ($comments as $k => $comment): ?>
                                        <!--/top story-->
                                        <div class="row">    
                                            <div class="col-sm-10">
                                                <h3><?php echo $comment->getContent(); ?></h3>
                                                <h4 class="col-xs-12">
                                                    <span class="label label-default col-xs-3"><?php echo $comment->getUser()->getFirstname() . " " . $comment->getUser()->getLastName(); ?> </span>
                                                    <span class="raty-readonly col-xs-5" data-score="<?php echo $comment->getRating(); ?>" data-number="10" ></span>
                                                    <span class="text-muted col-xs-4"><small><?php echo $comment->getCreationDatetime(); ?></small> </span>
                                                </h4>
                                            </div>
                                            <div class="col-sm-2">
                                                <a href="#" class="pull-right"><img src="assets/images/person.png" class="img-circle"></a>
                                            </div> 
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div><!-- /col-9 -->
                        </div><!-- /padding -->
                    </div>
                    <!-- /main -->

                </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="assets/js/libs/jquery-1.11.1.min.js"></script>
        <!--script for validation -->
        <script src="assets/js/libs/underscore-min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="assets/js/libs/bootstrap.min.js"></script>
        <!-- jQuery rating plugin -->
        <script src="assets/js/libs/jquery.raty.js"></script>
        <!--script for validation -->
        <script src="assets/js/libs/jquery.validate.min.js"></script>
        <!-- genaral javascript file -->
        <script src="assets/js/main.js"></script>
        <!-- page secific script -->
        <script src="assets/js/pages/preview.js"></script>

        <!-- templates-->
        <script id="commentTemplate" type="text/plain">
            <!--/top story-->
            <div class="row">    
            <div class="col-sm-10">
            <h3><%-content%></h3>
            <h4 class="col-xs-12">
            <span class="label label-default col-xs-3"><%-user.firstname%> <%-user.lastname%>  </span>
            <span class="raty-readonly col-xs-5" data-score="<%-rating%>" data-number="10" ></span>
            <span class="text-muted col-xs-4"><small><%-creationDatetime%></small> </span>
            </h4>
            </div>
            <div class="col-sm-2">
            <a href="#" class="pull-right"><img src="assets/images/person.png" class="img-circle"></a>
            </div> 
            </div>
        </script>
    </body>
</html>