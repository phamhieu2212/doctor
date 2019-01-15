<?php

namespace App\Http\Controllers\APi\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MeController extends Controller
{
    protected $userService;


    public function __construct(
        APIUserServiceInterface $userService

    )
    {
        $this->userService = $userService;
    }

    public function getPoint()
    {
        $user = $this->userService->getUser();
        return Response::response(200,
            [
                'point' => $user->patientPoint->point,

            ]);
    }

    public function getMe()
    {
        $user = $this->userService->getUser();

        return Response::response(200,
            [
                'user' => $user->toAPIArrayLogin(),
                'accountQuick' => [
                    'username' => 'BN'.$user->telephone,
                    'password' => $user->telephone
                ]

            ]);
    }

    public function logout() {
        $accessToken = $this->userService->getUser()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();
        return Response::response(200,[
            'status'=>true
        ]);
    }
}
