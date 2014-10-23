<?php

include './libs/Request.php';
include './libs/Response.php';

/**
 * Description of CommentAjax
 *
 * @author dimkl
 */
class CommentAjax extends Controller {

    protected function create() {
        Request::allowHttpMethod(HttpMethods::POST);

        $data = Request::getPostData();
        try {
            //insert data to model
            Model::load(['CommentModel', 'CommentUserModel']);
            $comment = new CommentModel($data);
            //setup userid
            $comment->setUserid(Authorization::getCurrrentUserid());
            //save model
            if ($comment->create()) {
                $commentUser = CommentUserModel::findBy('id', $comment->getId());
                if (count($commentUser) !== 1) {
                    Response::error("Error at fetching the comment from server");
                }
                $commentUser = $commentUser[0];
                $data = $commentUser->exportToArray();
                $data['message'] = 'Comment was created successfully!!';
                //return response
                Response::ok($data);
            }
            Response::error('Comment was not created successfully.<br/>' . implode('<br/>', $comment->errorMessages));
        } catch (Exception $ex) {
            Response::error('Error occured during registration: ' . $ex->getMessage());
        }
    }

}
