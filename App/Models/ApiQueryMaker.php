<?php
namespace App\Models;


class ApiQueryMaker
{

    public function send_message($text, $end_session=false){
        $data = [
            "response" => [
                "text" => $text,
                "end_session" => $end_session
            ],
            "version" =>  "1.0"
        ];
        $json_res = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $json_res;
    }
}
