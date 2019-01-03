<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\V1\QuickBloxRequest;
use App\Http\Requests\APIRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// (sac.doctor.oicsoft@gmail.com/oicsoft@123)
class QuickbloxController extends Controller
{

    public function getTokenAuth()
    {

        // Application credentials
        DEFINE('APPLICATION_ID', config('quickblox.auth.application_id'));
        DEFINE('AUTH_KEY', config('quickblox.auth.auth_key'));
        DEFINE('AUTH_SECRET', config('quickblox.auth.auth_secret'));

        // User credentials
        DEFINE('USER_LOGIN', "sacdoctor");
        DEFINE('USER_PASSWORD', "oicsoft@123");

        // Quickblox endpoints
        DEFINE('QB_API_ENDPOINT', "https://api.quickblox.com");
        DEFINE('QB_PATH_SESSION', "session.json");

        // Generate signature
        $nonce = rand();
        $timestamp = time(); // time() method must return current timestamp in UTC but seems like hi is return timestamp in current time zone
        $signature_string = "application_id=".APPLICATION_ID."&auth_key=".AUTH_KEY."&nonce=".$nonce."&timestamp=".$timestamp."&user[login]=".USER_LOGIN."&user[password]=".USER_PASSWORD;

        $signature = hash_hmac('sha1', $signature_string , AUTH_SECRET);

        // Build post body
        $post_body = http_build_query(array(
            'application_id' => APPLICATION_ID,
            'auth_key' => AUTH_KEY,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'signature' => $signature,
            'user[login]' => USER_LOGIN,
            'user[password]' => USER_PASSWORD
        ));


        // $post_body = "application_id=" . APPLICATION_ID . "&auth_key=" . AUTH_KEY . "&timestamp=" . $timestamp . "&nonce=" . $nonce . "&signature=" . $signature . "&user[login]=" . USER_LOGIN . "&user[password]=" . USER_PASSWORD;


        // Configure cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, QB_API_ENDPOINT . '/' . QB_PATH_SESSION); // Full path is - https://api.quickblox.com/session.json
        curl_setopt($curl, CURLOPT_POST, true); // Use POST
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response

        // Execute request and read responce
        $responce = curl_exec($curl);

        // Check errors
        curl_close($curl);
        if ($responce) {


            return $responce;
        } else {
            $error = curl_error($curl). '(' .curl_errno($curl). ')';
            return $error . "\n";
        }


    }

    public function signUp($input)
    {
        $dataToken = json_decode($this->getTokenAuth(),true);
        $token = $dataToken['session']['token'];

        // Quickblox endpoints
        DEFINE('QB_API_USER', "https://api.quickblox.com/users.json");

        // Build post body
        $post_body = [
            'user'=>[
                'login' => $input['username'],
                'password' => $input['password'],
                'email' => $input['email'],
                'external_user_id' => $input['external_user_id'],
                'facebook_id' => $input['facebook_id'],
                'twitter_id' => $input['twitter_id'],
                'full_name' => $input['full_name'],
                'phone' => $input['phone'],
                'website' => $input['website'],
                'tag_list'=>'name'
            ]
        ];
        $post_body = json_encode($post_body);
        DEFINE('QB_TOKEN', "Qb-Token:".$token);


        // Configure cURL
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, QB_API_USER);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($curl, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Quickblox-Rest-Api-Version: 0.1.0";
        $headers[] = QB_TOKEN;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


        // Execute request and read responce
        $responce = curl_exec($curl);

        // Check errors
        curl_close($curl);
        if ($responce) {

             $user = json_decode($responce,true);

            return $user;
        } else {
            $error = curl_error($curl). '(' .curl_errno($curl). ')';
            return \GuzzleHttp\json_decode($error,true);
        }
    }

    public function signIn(QuickBloxRequest $request)
    {
        $dataToken = json_decode($this->getTokenAuth(),true);
        $token = $dataToken['session']['token'];
        $input = $request->only(
            [
                'username',
                'password',
            ]
        );
        // Quickblox endpoints
        DEFINE('QB_API_LOGIN', "https://api.quickblox.com/login.json");

        // Build post body
        $post_body = [
            'login' => $input['username'],
            'password' => $input['password'],
        ];
        $post_body = json_encode($post_body);
        DEFINE('QB_TOKEN', "Qb-Token:".$token);



        // Configure cURL
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, QB_API_LOGIN);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($curl, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Quickblox-Rest-Api-Version: 0.1.0";
        $headers[] = QB_TOKEN;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


        // Execute request and read responce
        $responce = curl_exec($curl);

        // Check errors
        curl_close($curl);
        if ($responce) {

            $user = json_decode($responce,true);

            return [
                'status'=> 200,
                'message'=>'success',
                'data'=>$user,

            ];
        } else {
            $error = curl_error($curl). '(' .curl_errno($curl). ')';
            return $error . "\n";
        }

    }
    public function getUser($username)
    {
        $dataToken = json_decode($this->getTokenAuth(),true);
        $token = $dataToken['session']['token'];
        DEFINE('QB_TOKEN', "Qb-Token:".$token);
        // Quickblox endpoints


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.quickblox.com/users/by_login.json?login=".$username);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


        $headers = array();
        $headers[] = "Quickblox-Rest-Api-Version: 0.1.0";
        $headers[] = QB_TOKEN;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        return json_decode($result,true);

    }

    public function getUserById($idQuick)
    {
        $dataToken = json_decode($this->getTokenAuth(),true);
        $token = $dataToken['session']['token'];
        DEFINE('QB_TOKEN', "Qb-Token:".$token);
        // Quickblox endpoints


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.quickblox.com/users/".$idQuick.".json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


        $headers = array();
        $headers[] = "Quickblox-Rest-Api-Version: 0.1.0";
        $headers[] = QB_TOKEN;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        return json_decode($result,true);

    }
}
