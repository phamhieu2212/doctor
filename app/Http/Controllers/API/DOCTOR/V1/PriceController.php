<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\API\DOCTOR\V1\PriceRequest;
use App\Http\Requests\APIRequest;
use App\Http\Responses\API\V1\Response;
use App\Models\Doctor;
use App\Repositories\DoctorRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PriceController extends Controller
{
    protected $userService;
    protected $doctorRepository;

    public function __construct
    (
        APIUserServiceInterface $APIUserService,
        DoctorRepositoryInterface $doctorRepository

    )
    {
        $this->userService = $APIUserService;
        $this->doctorRepository = $doctorRepository;
    }
    public function update(PriceRequest $request)
    {
        $idDoctor = $this->userService->getUser()->id;
        if( !is_numeric($idDoctor) || ($idDoctor <= 0) ) {
            return response()->json(['code' => 503, 'message' => 'ID không phải số nguyên', 'data' => null]);
        }

        $doctor = Doctor::where('admin_user_id',$idDoctor)->first();
        if( empty($doctor) ) {
            return Response::response(20004);
        }

        $data = $request->only(
            [
                'price_call',
                'price_chat'
            ]
        );

        try {
            $this->doctorRepository->update($doctor, $data);
        } catch (\Exception $e) {
            return response()->json(['code' => 503, 'message' => 'Không thể cập nhật dữ liệu', 'data' => null]);
        }


        return Response::response(200,[
            'price_chat'=> $data['price_chat'],
            'price_call' => $data['price_call']
        ]);
    }
}
