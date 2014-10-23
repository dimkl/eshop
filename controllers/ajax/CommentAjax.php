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
        Request::allowOnlyMethod(Request::POSTMETHOD);
        
    }

}
