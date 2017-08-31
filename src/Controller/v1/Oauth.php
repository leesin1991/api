<?php

namespace Api\Controller\v1;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class Oauth extends Controller
{
    const DOMAIN = 'http://api.lc';
    const API_VERSION = 'v1';
    const REDIRECT_URL = self::DOMAIN.'/'.self::API_VERSION;
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

    public function authorize1(Request $request, Response $response)
    {

        $this->oauth2->handleTokenRequest( \OAuth2\Request::createFromGlobals())->send();
    }

    public function authorize(Request $req, Response $res, $args) {
        $request = \OAuth2\Request::createFromGlobals();
        $post = $request->request;
        if ($args['auth'] != substr(md5($post['client_id'] . $post['state'] . 'authorize'), 0, 8)) {
            $response = new \OAuth2\Response();
            if ($this->oauth2->validateAuthorizeRequest($request, $response)) {
                $clientId = 0;
                $this->oauth2->handleAuthorizeRequest($request, $response, true, $clientId);
                $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);
                $authorize = $this->oauth2->getStorage('authorization_code');
                $authed = $authorize->getAuthorizationCode($code);
                print_r($code);die;
                $data = array(
                    'app_key' => $request->request('client_id'),
                    'authorize_code' => $code,
                    'expire_time' => $authed['expires']
                );
                $return = [
                    'status' => true,
                    'errno' => '0',
                    'data' => $data
                ];
            } else {
                $return = array(
                    'status' => false,
                    'errno' => '40004',
                    'errmsg' => '授权失败'
                );
            }
        } else {
            $return = array(
                'status' => false,
                'errno' => '40003',
                'errmsg' => '请求参数不合法'
            );
        }
        return $res->withHeader('Content-type', 'application/json')->write(json_encode($return));
    }

    /*
     * 有效期为 1209600s，可以在 OAuth2/ResponseType/AccessToken.php 中的 AccessToken class 中的构造函数配置中进行修改。
     * curl -u app_key:app_secret /authed/token/********.html -d grant_type=authorization_code&code=$authcode
     */

    public function tokenGet(Request $req, Response $res, $args) {
        $server = $this->app()->oauthServer();
        $request = \OAuth2\Request::createFromGlobals();
        $post = $request->request;
        if ($args['auth'] == substr(md5($post['client_id'] . $post['state'] . 'token'), 0, 8)) {
            $response = new \OAuth2\Response();
            $resp = $server->handleTokenRequest(\OAuth2\Request::createFromGlobals(), $response);
            $body = $resp->getResponseBody();
            $data = json_decode($body, true);
            if (isset($data['access_token'])) {
                $return = [
                    'status' => true,
                    'errno' => '0',
                    'data' => $data
                ];
            } else {
                $return = [
                    'status' => false,
                    'errno' => '40005',
                    'data' => $data
                ];
            }
        } else {
            $return = [
                'status' => false,
                'errno' => '40003',
                'errmsg' => '请求参数不合法'
            ];
        }
        return $res->withHeader('Content-type', 'application/json')->write(json_encode($return));
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
            'authorize_url' => self::REDIRECT_URL.'/oauth/authorize/'.$authorize.'.html',
            'token_url' => self::REDIRECT_URL.'/oauth/token/'. $token.'.html',
            'refresh_url' => self::REDIRECT_URL.'/oauth/refresh/'.$refresh.'.html',
            'source_url' => self::REDIRECT_URL.'/oauth/resource/'.$resource.'.html',
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
