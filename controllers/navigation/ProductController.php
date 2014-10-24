<?php

/**
 * ProductController class is the controller for product pages
 *
 * @author dimkl
 */
class ProductController extends Controller {

    /**
     * Register method is responsible for rendering the product page.
     * This method is acceessible through url 'navigation/product/preview/{id}' 
     * 
     * @param int $productid
     * @throws ControllerException
     */
    protected function preview($productid) {
        $comments = NULL;
        $product = NULL;
        $user = NULL;
        $canComment = FALSE;
        $userid = NULL;
        try {
            Authorization::allowOnly([UserType::GUEST, UserType::USER]);
            $userid = Authorization::getCurrrentUserid();

            Model::load(['ProductModel', 'UserModel', 'CommentUserModel']);
            $productid = intval($productid);
            //getData
            $product = ProductModel::findById($productid);
            if (empty($product)) {
                throw new Exception("Product is missing");
            }
            $comments = CommentUserModel::allBy('productid', $productid);
            if ($userid !== NULL) {
                $user = UserModel::findById(intval($userid));
                $canComment = $user->allowedComment($productid);
            }
        } catch (Exception $ex) {
            throw new ControllerException($ex);
        }
        View::render('product/preview.php', compact('comments', 'product', 'user', 'canComment'));
    }

}
