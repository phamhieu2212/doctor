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
    public function index()
    {

        $idDoctor  = $this->userService->getUser()->id;
        $callHistories = CallHistory::where('rate','>',0)
            ->where('admin_user_id',$idDoctor)->get();
        $chatHistories = ChatHistory::where('rate','>',0)
        ->where('admin_user_id',$idDoctor)->get();

        $histories = $callHistories->concat($chatHistories);
        $histories = $histories->sortByDesc('rate_time')->values()->all();
        foreach($histories as $key=>$history)
        {
            $histories[$key] = $history->toAPIArrayListRateForDoctor();

        }

        return Response::response(200,$histories
        );
    }
}
