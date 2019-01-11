<?php namespace App\Services\Production;

use \App\Services\AccountKitServiceInterface;

class AccountKitService extends BaseService implements AccountKitServiceInterface
{
    public function getNumber($token)
    {

        $ch = curl_init('https://graph.accountkit.com/v1.3/me/?access_token='.$token);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $response = \GuzzleHttp\json_decode($result,true);

        if(isset($response['error']))
        {
            return false;
        }
        else
        {
            return $response['phone']['number'];
        }
    }
}
