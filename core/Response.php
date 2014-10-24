<?php

interface IResponse {

    static function sanitize($data);

    static function error($message);

    static function ok($data);
}

/**
 * Description of Response
 *
 * @author dimkl
 */
class Response implements IResponse {

    static function sanitize($data) {
        if (!is_array($data)) {
            return null;
        }
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = Response::sanitize($v);
            } else {
                $data[$k] = htmlspecialchars($v, ENT_COMPAT, 'UTF-8');
            }
        }
        return $data;
    }

    static function error($message) {
        if (!is_string($message)) {
            throw new Exception("Response error method takes 'data' only as string");
        }
        $response = [
            "status" => "error",
            "errormessage" => $message
        ];
        exit(json_encode($response));
    }

    static function ok($data) {
        if (!is_array($data)) {
            throw new Exception("Response ok method takes 'data' only as array");
        }
        $sanitizedData = Response::sanitize($data);
        if ($sanitizedData === NULL) {
            Response::error("Response send method takes 'data' only as array");
        }

        exit(json_encode([
            "status" => "success",
            "errormessage" => "",
            "data" => $sanitizedData])
        );
    }

}
