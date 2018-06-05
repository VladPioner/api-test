<?php
defined('API_TEST') or die ('Access denied');

try{
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS);
}catch (Exception $e){
    header("HTTP/1.0 400 Bad Request");
    echo json_encode(getArrErrorResponse('internal_error'));
    exit;
}

$first_part_uri = getFirstPartUri($_SERVER['REQUEST_URI'],$count_part_uri);


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($count_part_uri > 1 or !$first_part_uri){//Проверка на правильность URL в запросе
        header("HTTP/1.0 404 Not Found");
        $data_response = getArrErrorResponse('path_not_exist');
    }else{
        $tables = getTables($db);
        $rawPost = file_get_contents('php://input');
        if(!in_array($first_part_uri,$tables) OR !$rawPost){//Если НЕТ такой таблици или нет данных в POST запросе
            header("HTTP/1.0 400 Bad Request");
            $data_response = getArrErrorResponse('not_found');
        }else{//Если таблица существует и есть данные в POST запросе
            $data_request = json_decode($rawPost);
            $data_response = prepareSelect($db,$first_part_uri,$data_request);
            if($data_response){//Если все хотрошо и были получены данные из таблици
                header("HTTP/1.0 200 Ok");
            }else{
                header("HTTP/1.0 400 Bad Request");
                $data_response = getArrErrorResponse('not_found');
            }
        }
    }
}


$content = trim(json_encode($data_response));

if($_SERVER['REQUEST_METHOD'] != 'POST' or $content === 'null' or $content === '""' or !$content){//Все осталные ошибки
    header("HTTP/1.0 400 Bad Request");
    $content = json_encode(getArrErrorResponse('internal_error'));
}
