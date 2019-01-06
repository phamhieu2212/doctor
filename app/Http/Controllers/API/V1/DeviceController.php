<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Repositories\DeviceRepositoryInterface;
use App\Services\APIUserServiceInterface;
use App\Http\Responses\API\V1\Response;


class DeviceController extends Controller
{
    protected $deviceRepository;
    protected $adminUserService;
    
    public function __construct(DeviceRepositoryInterface $deviceRepository, APIUserServiceInterface $APIUserService)
    {
        $this->deviceRepository = $deviceRepository;
        $this->adminUserService = $APIUserService;
    }

    public function register(Request $request)
    {
        $currentUser = $this->adminUserService->getUser();
        $data = $request->only(['type', 'device_id']);
        $data['user_id'] = $currentUser->id;

        $device = $this->deviceRepository->create($data);

        if (empty($device)) {
            return Response::response(50002);
        }

        return Response::response(200, $device->toAPIArray()); 
    }
}
