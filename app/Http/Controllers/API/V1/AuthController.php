<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\PsrServerRequest;
use App\Http\Requests\API\V1\RefreshTokenRequest;
use App\Http\Requests\API\V1\SignInRequest;
use App\Http\Requests\API\V1\SignUpRequest;
use App\Models\AdminUser;
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
    protected $adminUserService;

    public function __construct(
        UserServiceInterface        $userService,
        UserRepositoryInterface     $userRepository,
        AuthorizationServer         $server,
        AdminUserServiceInterface $adminUserService
    )
    {
        $this->userService          = $userService;
        $this->userRepository       = $userRepository;
        $this->server               = $server;
        $this->adminUserService     = $adminUserService;
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
        $adminUser = $this->adminUserService->signIn($data);
        if (empty($adminUser)) {
            return Response::response(40101);
        }
        $data['email'] = $adminUser->email;
        $data['username'] = $adminUser->email;


        $serverRequest = PsrServerRequest::createFromRequest($request, $data);

        return $this->server->respondToAccessTokenRequest($serverRequest, new Psr7Response);
    }

    public function signUp(SignUpRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'email',
                'password',
                'grant_type',
                'client_id',
                'client_secret',
                'telephone',
                'birthday',
                'locale',
            ]
        );

        $check = $this->userService->checkClient($request);
        if( !$check ) {
            return Response::response(40101);
        }

        $userDeleted = $this->userRepository->findByEmail($data['email'], true);
        if (!empty($userDeleted)) {
            return Response::response(40002);
        }

        $serverRequest = PsrServerRequest::createFromRequest($request, $data);

        $response = $this->server->respondToAccessTokenRequest($serverRequest, new Psr7Response);
        return $response->withStatus(201);
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        $this->userService->checkClient($request);
        $serverRequest = PsrServerRequest::createFromRequest($request);

        return $this->server->respondToAccessTokenRequest($serverRequest, new Psr7Response);
    }
}
