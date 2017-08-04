<?php

namespace Api\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;

 
abstract class AbstractController implements ContainerInterface
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
        // $this->mongo = $container->get('oauth2'); 
        // print_r($this->db);
    }

    public function get($id)
    {

    }

    public function has($id)
    {
        
    }

    public function __invoke(Request $request)
    {
        $action = 'http' . ucfirst(strtolower($request->getMethod()));
        return call_user_func_array([$this, $action], func_get_args());
    }

}
