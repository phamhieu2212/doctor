<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Responses\API\V1\Response;
use App\Models\CallHistory;
use App\Models\ChatHistory;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RateController extends Controller
{
    protected $userService;

    public function __construct
    (
        APIUserServiceInterface $APIUserService
    )
    {
        $this->userService = $APIUserService;
    }

}
