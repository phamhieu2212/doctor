<?php

namespace App\Http\Controllers\API\PATIENT\V1;

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

    /** @var \App\Repositories\UserRepositoryInterface */
    protected $userRepository;

    /** @var AuthorizationServer */
    protected $server;

    public function __construct(
        UserServiceInterface        $userService,
        UserRepositoryInterface     $userRepository,
        AuthorizationServer         $server

    )
    {
        $this->userService          = $userService;
        $this->userRepository       = $userRepository;
        $this->server               = $server;

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
        $data['email'] = $user->email;
        $data['username'] = $user->email;


        $serverRequest = PsrServerRequest::createFromRequest($request, $data);

        return $this->server->respondToAccessTokenRequest($serverRequest, new Psr7Response);
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
            $dataUser['telephone'] = $data['username'];
            $dataUser['password'] = $data['password'];
            $user = $this->userRepository->create($dataUser);
            return Response::response(200,['user'=>$user]);
        }
    }

}
