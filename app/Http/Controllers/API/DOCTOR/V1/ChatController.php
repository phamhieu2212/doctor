<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Controllers\API\V1\QuickbloxController;
use App\Http\Responses\API\V1\Response;
use App\Repositories\ChatHistoryRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    protected $pointPatientRepository;
    protected $adminUserService;
    protected $chatHistoryRepository;
    protected $doctorRepository;
    protected $quickBlox;
    protected $userRepository;

    public function __construct(
        APIUserServiceInterface $APIUserService,
        ChatHistoryRepositoryInterface $chatHistoryRepository,
        QuickbloxController $quickbloxController,
        UserRepositoryInterface $userRepository
    ){
        $this->adminUserService = $APIUserService;
        $this->chatHistoryRepository = $chatHistoryRepository;
        $this->quickBlox        = $quickbloxController;
        $this->userRepository = $userRepository;
    }
    public function startChat(Request $request)
    {
        $idPatient = $request->get('idPatient');
        if (!empty($idPatient)) {
            $userId = $idPatient;
        }
        else
        {
            $idQuickPatient = $request->get('idQuickPatient');
            $userQuick = $this->quickBlox->getUserById($idQuickPatient);
            if( isset($userQuick['message']) and $userQuick['code'] == null)
            {
                return [
                    'code' => 503,
                    'status'=> $userQuick['message'],
                    'data'=>''

                ];
            }
            else
            {
                $username = $userQuick['user']['login'];
                $user = $this->userRepository->findByTelephone($username);
                if( empty($user) ) {
                    return Response::response(20004);
                }
                $userId = $user['id'];
            }
        }
        $currentDoctor = $this->adminUserService->getUser();
        $dataInput = [
            'admin_user_id'=>$currentDoctor->id,
            'user_id'=>$userId
        ];
        try {
            $chatHistory = $this->chatHistoryRepository->create($dataInput);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        if( empty( $chatHistory ) ) {
            return Response::response(50002);
        }


        return Response::response(200, [
            'status'=>true,
            'chat_id' => $chatHistory->id
        ]);
    }
}
