<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index()
    {
        $params = array(
            'func' => 'sendOrder',
            'version' => '1.0',
            'merchant_id' => MERCHANT_ID,
            'merchant_account' => RECEIVER,
            'order_code' => 'NL_'.time(),
            'total_amount' => 1,
            'currency' => 'vnd',
            'language' => 'en',
            'return_url' => 'http://localhost/pay_success.html',
            'cancel_url' => 'http://localhost/halo/pay_fail.html',
            'notify_url' => 'http://localhost/notify/page',
            'buyer_fullname' => 'Test name',
            'buyer_email' => 'test@gmail.com',
            'buyer_mobile' => '0987654321',
            'buyer_address' => 'address tesst',
            'checksum' => '',
        );

        $params['checksum'] = $this->makeChecksum($params);

        $result = $this->callURL(NGANLUONG_URL, $params);
        if ($result['response_code'] == '00') {
            $checkout_url = $result['checkout_url'];
            header('Location:'.$checkout_url);
            //echo $checkout_url;
        } else {
            var_dump($result);die();
        }
    }

    public  function makeChecksum($params) {
        $md5 = '';
        foreach ($params as $key=>$value) {
            if ($key != 'checksum') {
                $md5.= $value.'|';
            }
        }
        $md5.= MERCHANT_PASS;
        return md5($md5);
    }

    public function callURL($url, $params) {
        $params_str = http_build_query($params);
        $ch = curl_init ();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45); //timeout in seconds
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/10.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, true);
    }
}
