<?php
defined('API_TEST') or die ('Access denied');

function debug($arr){echo "<pre>".print_r($arr,true)."</pre>";}

function getArrUri($uri){
    $uri = trim(trim(trim($uri),'\\'),'/');
    return explode('/',$uri);
}

function getFirstPartUri($uri,&$count_part){
    $arr_uri = getArrUri($uri);
    $count_part = count($arr_uri);
    return $arr_uri[0];
}

function getArrErrorResponse($nameResponse){
    $not_found = [
        'code'=>'2',
        'message'=>'Not Found',
        'details'=>'Entity (or table) not found'
    ];
    $path_not_exist = [
        'code'=>'0',
        'message'=>'Path you requested not exist',
        'details'=>''
    ];
    $internal_error = [
        'code'=>'-1',
        'message'=>'Internal error',
        'details'=>'Please try again later'
    ];
    return $$nameResponse;
}