<?php

use GuzzleHttp\Client;

include "vendor/autoload.php";

try{
    $client = new Client(['verify'=>false]);
    $response = $client->get('http://localhost:8080/api/file/ce4954e0b8289e11e0e91e8a36d70977');
var_dump($response->getHeaderLine('content-disposition'));
    preg_match("@filename=\"([a-zA-Z0-9\.]+)\"@",$response->getHeaderLine('content-disposition'),$matches);
    var_dump($matches);
    if (count($matches) > 1){
        $filename = $matches[1];
    } else {
        $filename = 'test';
    }
    var_dump($filename);
    return $response;
}catch(\Exception $e){
    var_dump($e->getMessage());
}