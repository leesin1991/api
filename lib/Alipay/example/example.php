<?php

use Util\AliAppPay\AopClient;
use Util\AliAppPay\AlipayTradeAppPayRequest;

$aop = new AopClient;
$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
$aop->appId = "app_id";
$aop->rsaPrivateKey = '请填写开发者私钥去头去尾去回车，一行字符串';
$aop->format = "json";
$aop->charset = "UTF-8";
$aop->signType = "RSA2";
$aop->alipayrsaPublicKey = '请填写支付宝公钥，一行字符串';
//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
$request = new AlipayTradeAppPayRequest();
//SDK已经封装掉了公共参数，这里只需要传入业务参数
$bizcontent = "{\"body\":\"我是测试数据\","
        . "\"subject\": \"App支付测试\","
        . "\"out_trade_no\": \"20170125test01\","
        . "\"timeout_express\": \"30m\","
        . "\"total_amount\": \"0.01\","
        . "\"product_code\":\"QUICK_MSECURITY_PAY\""
        . "}";
$request->setNotifyUrl("商户外网可以访问的异步地址");
$request->setBizContent($bizcontent);
//这里和普通的接口调用不同，使用的是sdkExecute
$response = $aop->sdkExecute($request);
//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
echo htmlspecialchars($response); //就是orderString 可以直接给客户端请求，无需再做处理。