<?php

namespace Api\Controller\v1;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;

 
abstract class AbstractController 
{
    protected $container;

    protected $db;

    protected $view;

    protected $redis;

    protected $mongo;

    protected $oauth2;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db = $container->get('db');
        $this->view = $container->get('renderer');
        $this->redis = $container->get('redis');
        $this->mongo = $container->get('mongodb'); 
        $this->oauth2 = $container->get('oauth2'); 
        // print_r($this->oauth2);die;
    }

    public function __invoke(Request $request)
    {
        $action = 'http' . ucfirst(strtolower($request->getMethod()));
        return call_user_func_array([$this, $action], func_get_args());
    }


    // public function get($id)
    // {

    // }

    // public function has($id)
    // {
        
    // }

    protected function jsonSuccess(Response $response, $data = null)
    {
        $result = [
            'errno' => 0,
            'errmsg' => '',
        ];
        if ($data !== null) {
            $result['data'] = $data;
        }
        return $response->withJson($result);
    }

    protected function jsonError(Response $response, $errno, $defaultErrmsg = null, $data = null)
    {
        
        $errmsg = '出错了';
        if ($defaultErrmsg) {
            $errmsg = $defaultErrmsg;
        }
        $result = [
            'errno' => $errno,
            'errmsg' => $errmsg,
        ];
        if ($data !== null) {
            $result['data'] = $data;
        }
        return $response->withJson($result);
    }

}
