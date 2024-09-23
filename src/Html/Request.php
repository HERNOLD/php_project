<?php

namespace App\Html;

use App\Repositories\CountyRepository;
use App\Repositories\CityRepository;

class Request
{
    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]) {
            case "POST":
                self::postRequest();
                break;
            case "GET":
                self::getRequest();
                break;
            case "PUT":
                self::putRequest();
                break;
            case "DELETE":
                self::deleteRequest();
                break;
            default:
                echo 'Unknown request type';
                break;
        }
    }

    public static function getRequest()
    {
        // $uri = $_SERVER['REQUEST_URI'];

        $uri = self::getResourceName();
        switch ($uri) {
            case 'counties':
                self::dolog(new CountyRepository());
                break;
            case 'cities':
                self::dolog(new CityRepository());
                break;
            default:
                Response::response([], 404, "$uri not found");

        }
    }

    //azért hogy DRY legyen, nem tudok még rá értelmes nevet
    private static function dolog($repo)
    {
        $id = self::getResourceId();
        if ($id) {
            $entities = $repo->getById($id);
        } else {
            $entities = $repo->getAll();
        }

        $code = 200;
        if (empty($entities)) {
            $code = 404;
        }
        Response::response($entities, $code);
    }

    private static function deleteRequest()
    {
        $id = self::getResourceId();
        if (!$id) {
            Response::response([], 400, Response::STATUSES[400]);
        }
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $code = 404;
                $db = new CountyRepository();
                $result = $db->deleteById($id);
                if ($result) {

                    Response::response([], 200);
                } else {
                    Response::response([], 404);
                }
        }
        return;
    }

    private static function postRequest(){
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $data=self::getRequestData();
                if(isset($data['name'])){
                    $db=new CountyRepository();
                    $newId=$db->create($data);
                    $code=201;
                    if(!$newId){
                        $code=400;
                    }
                }
                Response::response(['id'=>$newId], $code);
                break;
        }
    }

    private static function putRequest(){
        $id = self::getResourceId();
        if (!$id) {
            Response::response([], 400, Response::STATUSES[400]);
            return;
        }
        $resourceName=self::getResourceName();
        switch ($resourceName){
            case 'counties':
                $data=self::getRequestData();
                $db=new CountyRepository();
                $entity=$db->find($id);
                $code=404;
                if($entity){
                    $result=$db->update($id, ['name'=>$data['name']]);
                    if($result){
                        $code=201;
                    }
                }
                Response::response([], $code);
                break;
            default:
                Response::response([], 404, $_SERVER['REQUEST_URI']."not found");    
        }
    }


    //szétdarabolja és ha az utolsó szám akkor az utolsó előttit adja vissza
    private static function getResourceName()
    {
        $arrUri = explode("/", $_SERVER['REQUEST_URI']);
        $last = $arrUri[count($arrUri) - 1]; //ha egy szam a vege akkor az egy id es elotte erroforras
        if (is_numeric($last)) {
            $last = $arrUri[count($arrUri) - 2];
        }
        return $last;
    }

    private static function getResourceId()
    {
        // $arrUri = self::getArrUri($_SERVER('REQUEST.URI'));
        $arrUri = explode("/", $_SERVER['REQUEST_URI']);
        $last = $arrUri[count($arrUri) - 1];
        if (is_numeric($last)) {
            return $last;
        }
        return false;
    }

    private static function getRequestData(): ?array
    {
        return json_decode(file_get_contents("php://input"), true);
    }
}