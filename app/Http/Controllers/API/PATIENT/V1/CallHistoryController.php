<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Repositories\CallHistoryRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class CallHistoryController extends Controller
{
    protected $callHistoryRepository;
    protected $userService;
    public function __construct
    (
        CallHistoryRepositoryInterface $callHistoryRepository,
        APIUserServiceInterface $APIUserService
    )
    {
        $this->callHistoryRepository = $callHistoryRepository;
        $this->userService = $APIUserService;
    }
    public function store(\App\Http\Requests\API\V1\Request $request)
    {
        $input = $request->only(
            [
                'doctor_id'
            ]
        );
        $timeNow = Carbon::now();
        $data = [
            'admin_user_id'=> $input['doctor_id'],
            'user_id' => $this->userService->getUser()->id,
            'start_time'=> $timeNow,
            'end_time'=>$timeNow,
            'type'=>0
        ];


        try {
            $callHistory = $this->callHistoryRepository->create($data);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        if( empty( $callHistory ) ) {
            return Response::response(50002);
        }


        return Response::response(200);
    }
}
