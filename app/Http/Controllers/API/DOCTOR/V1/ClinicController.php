<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\API\DOCTOR\V1\ClinicRequest;
use App\Http\Requests\APIRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Clinic;
use App\Repositories\ClinicRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Responses\API\V1\Response;
use App\Http\Controllers\Controller;

class ClinicController extends Controller
{
    protected $userService;
    protected $clinicRepository;
    protected $planController;
    public function __construct(
        APIUserServiceInterface $userService,
        ClinicRepositoryInterface $clinicRepository,
        PlanController $planController
    )
    {
        $this->userService = $userService;
        $this->clinicRepository = $clinicRepository;
        $this->planController = $planController;
    }
    public function index()
    {
        $now =  Carbon::now();
        $endDate =  date('Y-m-d 23:59:59', strtotime($now));
        $startDate =  date('Y-m-d 00:00:00', strtotime($now));
        $clinics = Clinic::where('admin_user_id',$this->userService->getUser()->id)
            ->where('status',1)->get();
        foreach($clinics as $key=>$clinic)
        {
            $clinics[$key] = $clinic->toAPIArrayListPlanDoctor($this->userService->getUser()->id,$startDate,$endDate);
        }

        return Response::response(200,
            $clinics
        );
    }
    public function store(APIRequest $request)
    {
        $data = array();
        $bodyRequests = $request->all();
        $data['name'] = $bodyRequests['name'];
        $data['address'] = $bodyRequests['address'];
        $data['price'] = $bodyRequests['price'];
        $data['admin_user_id'] = $this->userService->getUser()->id;
        $data['status'] = 1;

        try {
            $clinic = $this->clinicRepository->create($data);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        if( empty( $clinic ) ) {
            return Response::response(50002);
        }
        $plan = $this->planController->store($data['admin_user_id'],$clinic->id,$bodyRequests['plans']);

        return Response::response(200
        );

    }
    public function edit($id, $timestamp)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $clinic = $this->clinicRepository->find($id);
        if( empty($clinic) ) {
            return Response::response(20004);
        }
        $month =  date( 'Y-m', $timestamp);
        $endDateOfMonth =  date('Y-m-t 23:59:59', strtotime($month));
        $startDateOfMonth =  date('Y-m-01 00:00:00', strtotime($month));


        return Response::response(200, $clinic->toAPIArrayEditClinic($this->userService->getUser()->id,$startDateOfMonth,$endDateOfMonth));
    }

    public function update($id,APIRequest $request)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $clinic = $this->clinicRepository->find($id);
        if( empty($clinic) ) {
            return Response::response(20004);
        }

        $data = array();
        $bodyRequests = $request->all();
        $data['name'] = $bodyRequests['name'];
        $data['address'] = $bodyRequests['address'];
        $data['price'] = $bodyRequests['price'];
        $data['admin_user_id'] = $this->userService->getUser()->id;

        try {
            $this->clinicRepository->update($clinic, $data);
        } catch (\Exception $e) {
            return Response::response(50002);
        }
        $plan = $this->planController->update($data['admin_user_id'],$clinic->id,$bodyRequests['plans']);


        return Response::response(200);
    }

    public function delete($id)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $clinic = $this->clinicRepository->find($id);
        if( empty($clinic) ) {
            return Response::response(20004);
        }

        $data = [
            'status'=>2
        ];

        try {
            $this->clinicRepository->update($clinic, $data);
        } catch (\Exception $e) {
            return Response::response(50002);
        }


        return Response::response(200, $clinic->toAPIArray());
    }
}
