<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Controllers\API\V1\QuickbloxController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\PsrServerRequest;
use App\Http\Requests\API\V1\RefreshTokenRequest;
use App\Http\Requests\API\V1\Request;
use App\Models\AdminUser;
use App\Models\OauthAccessToken;
use App\Services\AdminUserServiceInterface;
use App\Services\UserServiceInterface;
use App\Services\APIUserServiceInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;
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
    protected $adminUserService;

    public function __construct(
        UserServiceInterface        $userService,
        UserRepositoryInterface     $userRepository,
        AuthorizationServer         $server,
        AdminUserServiceInterface $adminUserService,
        QuickbloxController $quickblox
    )
    {
        $this->userService          = $userService;
        $this->userRepository       = $userRepository;
        $this->server               = $server;
        $this->adminUserService     = $adminUserService;
        $this->quickblox            = $quickblox;
    }

    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'         => 'required',
            'password'      => 'required|min:8',
            'grant_type'    => 'required',
            'client_id'     => 'required',
            'client_secret' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 400, 'message' => 'Mật khẩu hoặc tên đăng nhập sai', 'data' => null]);
        }
        $data = $request->only(
            [
                'username',
                'password',
                'device_token',
                'device_type','client_secret','grant_type','client_id'
            ]
        );

        $check = $this->userService->checkClient($request);
        if( !$check ) {
            return response()->json(['code' => 400, 'message' => 'Sai thông tin Client', 'data' => null]);
        }
        $adminUser = $this->adminUserService->signIn($data);
        if (empty($adminUser)) {
            return response()->json(['code' => 400, 'message' => 'Mật khẩu hoặc tên đăng nhập sai', 'data' => null]);
        }
        $data['email'] = $adminUser->email;
        $data['username'] = $adminUser->email;

//        $input = [
//            'username' => $adminUser->username,
//            'password' => $data['password'],
//            'email'    => $adminUser->email ,
//            'external_user_id' => '',
//            'facebook_id' => '',
//            'twitter_id' => '',
//            'full_name'=> $adminUser->name ,
//            'phone'    => $adminUser->phone,
//            'website' => '',
//        ];
//
//        $userQuickblox = $this->quickblox->signUp($input);
//        if(isset($userQuickblox['errors']))
//        {
//            return [
//                'code' => 503,
//                'status'=> $userQuickblox['errors'],
//                'data'=>''
//
//            ];
//        }
        $dataUser = [
            'user' => $adminUser->toAPIArrayLoginDoctor(),
            'accountQuick' => [
                'username' => $adminUser->username,
                'password' => $adminUser->username
            ]
        ];

        $serverRequest = PsrServerRequest::createFromRequest($request, $data);
        $tokens = OauthAccessToken::where('user_id',$adminUser->id)
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
