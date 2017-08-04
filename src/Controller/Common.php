<?php

namespace Api\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;

class Common extends AbstractController
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

    public function get($id)
    {

    }

    public function has($id)
    {
        
    }


}
