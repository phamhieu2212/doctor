<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Controllers\API\V1\QuickbloxController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\PsrServerRequest;
use App\Http\Requests\API\V1\RefreshTokenRequest;
use App\Http\Requests\API\V1\Request;
use App\Http\Requests\API\V1\SignInRequest;
use App\Http\Requests\API\V1\SignUpRequest;
use App\Http\Requests\BaseRequest;
use App\Models\AdminUser;
use App\Models\OauthAccessToken;
use App\Models\User;
use App\Repositories\PatientRepositoryInterface;
use App\Repositories\PointPatientRepositoryInterface;
use App\Services\AccountKitServiceInterface;
use App\Services\AdminUserServiceInterface;
use App\Services\UserServiceInterface;
use App\Services\APIUserServiceInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use League\OAuth2\Server\AuthorizationServer;
use Zend\Diactoros\Response as Psr7Response;
use App\Http\Responses\API\V1\Response;

class AuthController extends Controller
{
    /** @var \App\Services\UserServiceInterface */
    protected $userService;
    protected $quickblox;
    protected $accountKitService;

    /** @var \App\Repositories\UserRepositoryInterface */
    protected $userRepository;
    protected $patientRepository;

    /** @var AuthorizationServer */
    protected $server;
    protected $pointPatientRepository;

    public function __construct(
        UserServiceInterface        $userService,
        UserRepositoryInterface     $userRepository,
        AuthorizationServer         $server,
        QuickbloxController $quickblox,
        PatientRepositoryInterface $patientRepository,
        AccountKitServiceInterface $accountKitService,
        PointPatientRepositoryInterface $pointPatientRepository

    )
    {
        $this->userService          = $userService;
        $this->userRepository       = $userRepository;
        $this->server               = $server;
        $this->quickblox            = $quickblox;
        $this->patientRepository    = $patientRepository;
        $this->accountKitService    = $accountKitService;
        $this->pointPatientRepository = $pointPatientRepository;

    }

    public function signIn(Request $request)
    {
        $data = $request->only(
            [
                'username',
                'account_kit_token',
                'grant_type',
                'client_id',
                'client_secret','device_token','device_type'
            ]
        );
        $check = $this->userService->checkClient($request);
        if( !$check ) {
            return Response::response(40003);
        }
        $checkAccountKit = $this->accountKitService->getNumber($data['account_kit_token']);
        if($checkAccountKit == false)
        {
            return Response::response(40004);
        }
        else
        {
            if($data['username'] != $checkAccountKit)
            {
                return Response::response(40004);
            }
            else
            {
                $user = $this->userRepository->findByTelephone($data['username']);
                if (empty($user)) {
                    $user = $this->signUp($data['username']);
                    if($user == false)
                    {
                        return Response::response(40005);
                    }
                }

                $data['username'] = $user->email;
                $data['email'] = $user->email;
                $data['password'] = "12345678";

                $dataUser = [
                    'user' => $user->toAPIArrayLogin(),
                    'accountQuick' => [
                        'username' => "BN".$user->telephone,
                        'password' => $user->telephone
                    ]
                ];


                $serverRequest = PsrServerRequest::createFromRequest($request, $data);
                $tokens = OauthAccessToken::where('user_id',$user->id)
                    ->where('client_id',$data['client_id'])->get();
                if(!empty($tokens))
                {
                    foreach($tokens as $token)
                    {
                        $token->revoked = 1;
                        $token->save();
                    }

                }

                return $this->server->respondToAccessTokenRequest($serverRequest, new Psr7Response,$dataUser);

            }
        }
    }
    public function signUp($username)
    {
        $input = [
            'username' => 'BN'.$username,
            'password' => $username,
            'external_user_id' => '',
            'facebook_id' => '',
            'twitter_id' => '',
            'full_name'=> $username ,
            'phone'    => $username,
            'website' => '',
            ];

        $userQuickblox = $this->quickblox->signUp($input);
        if(isset($userQuickblox['message']))
        {
            return false;
        }
        else
        {

            $dataPatient['telephone'] = $username;
            $dataPatient['name'] = $username;
            $dataPatient['password'] = '12345678';
            $dataPatient['email'] = $username.'@gmail.com';
            $dataPatient['quick_id'] = $userQuickblox['user']['id'];
            try {
                DB::beginTransaction();

                $user = $this->userRepository->create($dataPatient);
                $this->patientRepository->create(['user_id'=>$user['id']]);
                $this->pointPatientRepository->create(["user_id" => $user->id, "point" => 0]);


                DB::commit();

                return $user;

            } catch (\Exception $ex) {
                DB::rollBack();

                return false;
            }

        }

        }


}
