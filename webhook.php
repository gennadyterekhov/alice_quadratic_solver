<?php

require_once __DIR__ . '/vendor/autoload.php';


$api_query_maker = new \App\Models\ApiQueryMaker();
$qc = new \App\Controllers\QuadraticController();
$data = file_get_contents("php://input");
$message = json_decode($data, true);



$msgtxt = $message["request"]["original_utterance"];
if ($msgtxt === "") {
    $response = $api_query_maker->send_message("Привет!\nЯ помогу решить квадратные уравнения. Для решения, введи коэффициенты через пробел.\n Например так: 1 4 3");
    echo $response;
    return true;
} else if($msgtxt === "хватит"){
    $response = $api_query_maker->send_message("закончено", $end_session=true);
    echo $response;
    return true;
} else {
    $check_input_res = $qc->check_input($msgtxt);

    if ($check_input_res === true) {
        $coef = $qc->get_coefs($msgtxt);
        $in_real_numbers = $qc->in_real_numbers($coef);
        if ($in_real_numbers){
            $roots = $qc->solve($coef);
            $response_text = $qc->respond($roots);
        } else {
            $response_text = "Дискриминант отрицательный. Решение в комплексных числах.\nА я их не знаю ;(";
            file_put_contents("log", "Дискриминант отрицательный. Решение в комплексных числах.\nА я их не знаю ;(" . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
        $response = $api_query_maker->send_message($response_text);
        echo $response;
        return true;
    } else {
        $response = $api_query_maker->send_message("Неверный ввод.\nПопробуй ещё раз по шаблону: 1 4 3");
        echo $response;
        return true;
    }
}


