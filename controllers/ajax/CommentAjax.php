<?php

/**
 * Description of CommentAjax
 *
 * @author dimkl
 */
class CommentAjax extends Controller {

    /**
     * Create method inserts a comment to the database and returns a Response.
     * This ajax method is acceessible through url 'ajax/comment/create' 
     * and request must be with post http method.
     */
    protected function create() {
        try {
            Request::allowHttpMethod(HttpMethods::POST);
            Authorization::ajaxAllowOnly([UserType::USER]);
        } catch (Exception $ex) {
            Response::error($ex->getMessage());
        }
        try {
            $data = Request::getPostData();
            //insert data to model
            Model::load(['CommentModel', 'CommentUserModel']);
            $comment = new CommentModel($data);
            //setup userid
            $comment->setUserid(Authorization::getCurrrentUserid());
            //save model
            if (!$comment->create()) {
                Response::error('Comment was not created successfully.<br/>' . implode('<br/>', $comment->errorMessages));
            }
            $commentUser = CommentUserModel::allBy('id', $comment->getId());
            if (count($commentUser) !== 1) {
                Response::error("Error at fetching the comment from server");
            }
            $data = $commentUser[0]->exportToArray();
            $data['message'] = 'Comment was created successfully!!';
            //return response
            Response::ok($data);
        } catch (Exception $ex) {
            Response::error('Error occured during registration: ' . $ex->getMessage());
        }
    }

}
