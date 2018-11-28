<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Controllers\API\V1\QuickbloxController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\PsrServerRequest;
use App\Http\Requests\API\V1\RefreshTokenRequest;
use App\Http\Requests\API\V1\SignInRequest;
use App\Http\Requests\API\V1\SignUpRequest;
use App\Models\AdminUser;
use App\Models\User;
use App\Services\AdminUserServiceInterface;
use App\Services\UserServiceInterface;
use App\Services\APIUserServiceInterface;
use App\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\AuthorizationServer;
use Zend\Diactoros\Response as Psr7Response;
use App\Http\Responses\API\V1\Response;

class AuthController extends Controller
{
    /** @var \App\Services\UserServiceInterface */
    protected $userService;
    protected $quickblox;

    /** @var \App\Repositories\UserRepositoryInterface */
    protected $userRepository;

    /** @var AuthorizationServer */
    protected $server;

    public function __construct(
        UserServiceInterface        $userService,
        UserRepositoryInterface     $userRepository,
        AuthorizationServer         $server,
        QuickbloxController $quickblox

    )
    {
        $this->userService          = $userService;
        $this->userRepository       = $userRepository;
        $this->server               = $server;
        $this->quickblox            = $quickblox;

    }

    public function signIn(SignInRequest $request)
    {
        $data = $request->only(
            [
                'username',
                'password',
                'grant_type',
                'client_id',
                'client_secret'
            ]
        );

        $check = $this->userService->checkClient($request);
        if( !$check ) {
            return Response::response(40101);
        }
        $user = $this->userService->signIn($data);
        if (empty($user)) {
            return Response::response(40101);
        }
        $data['username'] = $user->email;
        $data['email'] = $user->email;

        $dataUser = [
            'patient' => $user,
            'accountQuick' => [
                'username' => $user->telephone,
                'password' => $data['password']
            ]
        ];


        $serverRequest = PsrServerRequest::createFromRequest($request, $data);

        return $this->server->respondToAccessTokenRequest($serverRequest, new Psr7Response,$dataUser);
    }
    public function signUp(SignUpRequest $request)
    {
        $data = $request->only(
            [
                'username',
                'password',
                'grant_type',
                'client_id',
                'client_secret',
            ]
        );

        $check = $this->userService->checkClient($request);
        if( !$check ) {
            return Response::response(40101);
        }
        $checkUser = User::where('telephone',$data['username'])->count();

        if ($checkUser > 0) {
            return Response::response(40002);
        }
        else
        {
            $input = [
            'username' => $data['username'],
            'password' => $data['password'],
            'email'    => $data['username'].'@gmail.com' ,
            'external_user_id' => '',
            'facebook_id' => '',
            'twitter_id' => '',
            'full_name'=> '' ,
            'phone'    => $data['username'],
            'website' => '',
        ];

        $userQuickblox = $this->quickblox->signUp($input);
        if(isset($userQuickblox['errors']))
        {
            return [
                'code' => 503,
                'status'=> $userQuickblox['errors'],
                'data'=>''

            ];
        }
            $dataPatient['telephone'] = $data['username'];
            $dataPatient['password'] = $data['password'];
            $dataPatient['email'] = $data['username'].'@gmail.com';
            $user = $this->userRepository->create($dataPatient);
            $dataUser = [
                'user' => $user,

                'accountQuick' => [
                    'username' => $user->telephone,
                    'password' => $data['password']
                ]
            ];
            $data['email'] = $user->email;
            $data['username'] = $user->email;

            $serverRequest = PsrServerRequest::createFromRequest($request, $data);

            return $this->server->respondToAccessTokenRequest($serverRequest, new Psr7Response,$dataUser);
        }
    }

}
