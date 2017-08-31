<?php

namespace Api\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class Oauth extends Controller
{
    const DOMAIN = 'http://api.lc';
    const REDIRECT_URL = 'http://api.lc';
    const ENCRYPT_SECRET = 'QING';


    public function register(Request $req, Response $res, $args) {
        $request = \OAuth2\Request::createFromGlobals();
        $post = $request->request;
        $storage = $this->oauth2->getStorage('client');
        // print_r(md5(15900545092));die;
        if (isset($post['imei']) && isset($post['code'])) {
            if (md5($post['imei']) == $post['code']) {
                $client_id = md5($post['imei']);
                $client_secret = md5(self::ENCRYPT_SECRET.uniqid());
                $status = $storage->setClientDetails($client_id, $client_secret, self::REDIRECT_URL);
                // print_r(self::ENCRYPT_SECRET);die;
                if ($status) {
                    $details = $storage->getClientDetails($client_id);
                    $data = $this->client($details);
                    $return = ['status' => true,'errno' => '0','data' => $data];
                } else {
                    $return = ['status' => false,'errno' => '40002'];
                }
            } else {
                $return = ['status' => false,'errno' => '40003'];
            }
        } else {
            $return = ['status' => false,'errno' => '40001'];
        }
        return $res->withHeader('Content-type', 'application/json')->write(json_encode($return));
    }

    public function authorize(Request $request, Response $response)
    {

        $this->oauth2->handleTokenRequest( \OAuth2\Request::createFromGlobals())->send();
    }

    public function client($details) {
        $seed = md5(uniqid());
        $authorize = substr(md5($details['client_id'] . $seed . 'authorize'), 0, 8);
        $token = substr(md5($details['client_id'] . $seed . 'token'), 0, 8);
        $refresh = substr(md5($details['client_id'] . $seed . 'refresh'), 0, 8);
        $resource = substr(md5($details['client_id'] . $seed . 'resource'), 0, 8);
        return array(
            'app_key' => $details['client_id'],
            'app_secret' => $details['client_secret'],
            'authorize_url' => self::DOMAIN.'/authed/authorize/'.$authorize.'.html',
            'token_url' => self::DOMAIN.'/authed/token/'. $token.'.html',
            'refresh_url' => self::DOMAIN.'/authed/refresh/'.$refresh.'.html',
            'source_url' => self::DOMAIN.'/authed/resource/'.$resource.'.html',
            'seed_secret' => $seed,
            'expire_time' => 30
        );
    }



    public function test(Request $request, Response $response)
    {
        //echo base64_encode(random_bytes(32));die; //wwTCGJizEE9W0BBonTbOM78yeJcDc7LlDohKVOSQm+s=
        //echo phpinfo();die;
        // echo md5(123456);
        $rs = $this->db->oauth_users()->select('');
        $data = $this->toArray($rs);
        print_r($data);
    }



}
