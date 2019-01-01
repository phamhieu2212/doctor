<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Models\Doctor;
use App\Models\PointPatient;
use App\Repositories\CallHistoryRepositoryInterface;
use App\Repositories\PointPatientRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class CallHistoryController extends Controller
{
    protected $callHistoryRepository;
    protected $userService;
    protected $pointPatientRepository;
    public function __construct
    (
        CallHistoryRepositoryInterface $callHistoryRepository,
        APIUserServiceInterface $APIUserService,
        PointPatientRepositoryInterface $pointPatientRepository
    )
    {
        $this->callHistoryRepository = $callHistoryRepository;
        $this->userService = $APIUserService;
        $this->pointPatientRepository = $pointPatientRepository;
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


        return Response::response(200,[
            'call_id'=>$callHistory->id
        ]);
    }

    public function updateEndtime(\App\Http\Requests\API\V1\Request $request)
    {
        $input = $request->only(
            [
                'call_id'
            ]
        );
        $callHistory = $this->callHistoryRepository->find($input['call_id']);
        if( empty( $callHistory ) ) {
            return Response::response(50002);
        }
        $timeNow = Carbon::now()->timestamp;
        $dataCallHistory = ['end_time'=>$timeNow];
        $timeCall = (int)date('i',$timeNow - $callHistory['end_time']->timestamp);
        $callHistory = $this->callHistoryRepository->update($callHistory,$dataCallHistory);
        $pointPatient = PointPatient::where('user_id',$this->userService->getUser()->id)->first();
        $doctor = Doctor::where('admin_user_id',$callHistory['admin_user_id'])->first();

        $dataPointPatient = [
            'point'=>$pointPatient['point']-$doctor['price_call']*$timeCall
        ];

        $pointPatient = $this->pointPatientRepository->update($pointPatient,$dataPointPatient);
        return Response::response(200,['point'=>$pointPatient['point']]);

    }

    public function updateType(\App\Http\Requests\API\V1\Request $request)
    {
        $input = $request->only(
            [
                'call_id',
                'type'
            ]
        );
        $dataCallHistory = ['type'=>$input['type']];
        $callHistory = $this->callHistoryRepository->find($input['call_id']);
        if( empty( $callHistory ) ) {
            return Response::response(50002);
        }
        try {
            $this->callHistoryRepository->update($callHistory,$dataCallHistory);
        } catch (\Exception $e) {
            return Response::response(50002);
        }
        return Response::response(200);
    }
}
