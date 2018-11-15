<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\API\DOCTOR\V1\ClinicRequest;
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
    public function store(ClinicRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'address',
                'status',
            ]
        );
        $data['admin_user_id'] = $this->userService->getUser()->id;

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
}
