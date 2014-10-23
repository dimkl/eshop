<?php

/**
 * Description of ProductController
 *
 * @author dimkl
 */
class ProductController extends Controller {

    protected function preview($productid) {
        $productid = intval($productid);
        $userid = intval(Authorization::getCurrrentUserid());
        //getData
        Model::load(['ProductModel', 'UserModel', 'CommentUserModel']);

        $comments = CommentUserModel::findBy('productid', $productid);
        $product = ProductModel::findById($productid);
        $user = UserModel::findById(1);
        var_dump($comments);
        //
        View::render('product/preview.php', compact('comments', 'product', 'user'));
    }

}
