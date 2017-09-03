<?php

namespace Api\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Index extends Controller
{

    public function index(Request $request, Response $response)
    {
        die;
		$args = array('name' => "leesin");
        return $this->view->render($response, 'index.html');
    }

    public function test(Request $request, Response $response)
    {
        //echo base64_encode(random_bytes(32));die; //wwTCGJizEE9W0BBonTbOM78yeJcDc7LlDohKVOSQm+s=
        //echo phpinfo();die;
        $rs = $this->db->admin()->select('');
        $data = $this->toArray($rs);
        print_r($data);
    }



}
