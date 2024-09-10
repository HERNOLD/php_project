<?php

namespace App\Html;

use App\Repositories\CountyRepository;

class Request{
    static function handle(){
        switch($_SERVER["REQUEST_METHOD"]){
            case "POST":
                self::postRequest();
                break;
            case "PUT":
                self::putRequest();
                break;
            case "GET":
                self::getRequest();
                break;
            case "DELETE":
                self::deleteRequest();
                break;
            default:
                echo 'Unknown request type';
                break;
        }
    }

    private static function getRequest(){
        $uri=$_SERVER['REQUEST_URI'];
        switch($uri){
            case '/counties';
                $repository=new CountyRepository();
                $entities=$repository->getAll();
                $code=200;
                if(empty($entities)){
                    $code=404;
                }
                Response::response($entities, $code);
                break;
            default:
                Response::response([], 404, "$uri not found");
        }
    }
}