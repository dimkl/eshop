<?php

/**
 * Description of ProductController
 *
 * @author dimkl
 */
class ProductController extends Controller {

    protected function preview($productid) {
        $productid = intval($productid);
        //getData
        Model::load(['ProductModel', 'UserModel', 'CommentUserModel']);

        $comments = CommentUserModel::findBy('productid', $productid);
        $product = ProductModel::findById($productid);
        $user = null;
        $canComment = FALSE;
        try {
            $userid = intval(Authorization::getCurrrentUserid());
            $user = UserModel::findById($userid);
            $canComment = $user->allowedComment($productid);
        } catch (Exception $ex) {
            
        }
        //
        View::render('product/preview.php', compact('comments', 'product', 'user', 'canComment'));
    }

}
