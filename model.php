<?php
defined('API_TEST') or die ('Access denied');

function getTables($db){
    $res = $db->query('SHOW TABLES');
    $tables = [];
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $tables[] = $row['Tables_in_'.DB_NAME];
    }
    return $tables;
}

function prepareSelect($db,$tablename,$params){
    if(!$params) return false;

    $where = '';
    $to_prepare = [];
    foreach ($params as $name=>$val){
        $where .= "$name = :$name AND ";
        $to_prepare[$name] = $val;
    }
    $where = trim($where,' AND ');

    $sql = "SELECT * FROM $tablename WHERE $where";
    $res = $db->prepare($sql);
    $res->execute($to_prepare);

    return $res->fetchAll(PDO::FETCH_ASSOC);
}