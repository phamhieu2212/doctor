<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\API\DOCTOR\V1\ClinicRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Clinic;
use App\Repositories\ClinicRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;

use App\Http\Responses\API\V1\Response;
use App\Http\Controllers\Controller;

class ClinicController extends Controller
{
    protected $userService;
    protected $clinicRepository;
    public function __construct(
        APIUserServiceInterface $userService,
        ClinicRepositoryInterface $clinicRepository
    )
    {
        $this->userService = $userService;
        $this->clinicRepository = $clinicRepository;
    }
    public function index()
    {
        $clinics = Clinic::where('admin_user_id',$this->userService->getUser()->id)->where('status','!=',3)->get();
        foreach( $clinics as $key => $clinic ) {
            $clinics[$key] = $clinic->toAPIArray();
        }

        return Response::response(200, $clinics);
    }
    public function store(ClinicRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'address'
            ]
        );
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
        return Response::response(200, $clinic->toAPIArray());

    }

    public function update($id, ClinicRequest $request)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $clinic = $this->clinicRepository->find($id);
        if( empty($clinic) ) {
            return Response::response(20004);
        }

        $data = $request->only(
            [
                'name',
                'address'
            ]
        );

        try {
            $this->clinicRepository->update($clinic, $data);
        } catch (\Exception $e) {
            return Response::response(50002);
        }


        return Response::response(200, $clinic->toAPIArray());
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
