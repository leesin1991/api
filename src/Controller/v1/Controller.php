<?php

namespace Api\Controller\v1;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;

class Controller extends AbstractController
{

    // public function __construct(ContainerInterface $container)
    // {
    //     parent::__construct();
    // }

    public function toArray($obj) 
    {
        foreach($obj as $row) {
            $ret[] = iterator_to_array($row); 
        }
        return $ret;
    }

    public function getRandom($length=6,$num=null)
    {
        $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
        if($num)$str = "0123456789";
        $len = strlen($str)-1;
        for($i=0 ; $i<$length; $i++){
            $s .=  $str[rand(0,$len)];
        }
        return $s;
    }
    
    public function validateMobile($mobile)
    {
        if (preg_match('/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\\d{8}$/', $mobile)) {
            return true;
        }   
    }


}
