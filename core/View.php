<?php

/**
 * Description of View
 *
 * @author dimkl
 */
class View {

    const VIEWBASEPATH = "./views/";

    public static function render($pageFile, $dataArray = []) {
        if (!is_string($pageFile)) {
            throw new Exception('$pageFile supplied to render must be string.');
        }
        if (!is_array($dataArray)) {
            throw new Exception('$dataArray supplied  must be array.');
        }
//        $isAssoc = array_keys($dataArray) !== range(0, count($dataArray) - 1);
//        if (!$isAssoc) {
//            throw new Exception('$dataArray supplied must be "associative" array.');
//        }

        try {
            $viewPath = View::VIEWBASEPATH . $pageFile;
            if (!file_exists($viewPath)) {
                throw new Exception("View pageFile with name " . $pageFile . " was not found");
            }

            extract($dataArray);

            include $viewPath;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
