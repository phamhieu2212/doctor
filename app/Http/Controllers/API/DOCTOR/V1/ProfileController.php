<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Responses\API\V1\Response;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    protected  $userService;

    public function __construct
    (
        APIUserServiceInterface $APIUserService
    )
    {
        $this->userService = $APIUserService;
    }
    public function index()
    {
        $doctor = $this->userService->getUser();
        $data = [
            'user' => $doctor->toAPIArrayLogin(),
            'accountQuick' => [
                'username' => $doctor->username,
                'password' => $doctor->username
            ]
        ];

        return Response::response(200,$data);
    }
}
