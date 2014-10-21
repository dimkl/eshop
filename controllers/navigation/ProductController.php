<?php

/**
 * Description of ProductController
 *
 * @author dimkl
 */
class ProductController implements IController {

    public function __construct() {
        
    }

    public function preview() {
        //setup requirements
        include Router::$modelPath . '/ProductModel.php';
        include Router::$modelPath . '/UserModel.php';
        include Router::$modelPath . '/CommentModel.php';
        //
        try {
            $comments = CommentModel::findWithJoin('productid', 1, ['User' => 'Comment.userid=User.id']);
            $user = UserModel::findByid(1);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        if ($user === NULL || $comments === NULL) {
            include Router::$viewPath . "/errorPage.html";
            exit();
        }
        //render view
        include Router::$viewPath . '/product/preview.php';
    }

}
