<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Models\CallHistory;
use App\Models\ChatHistory;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoryController extends Controller
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

        $idPatient  = $this->userService->getUser()->id;
        $callHistories = CallHistory::where('start_time','!=',null)
            ->where('user_id',$idPatient)->get();
        $chatHistories = ChatHistory::where('user_id',$idPatient)->get();

        $histories = $callHistories->concat($chatHistories);
        $histories = $histories->sortByDesc('created_at')->values()->all();
        foreach($histories as $key=>$history)
        {
            $histories[$key] = $history->toAPIArrayDetailDoctor();

        }

        return Response::response(200,$histories
        );
    }
}
