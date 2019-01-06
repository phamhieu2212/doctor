<?php
namespace App\Http\Controllers\API\PATIENT\V1;
use App\Http\Controllers\API\V1\QuickbloxController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Request;
use App\Models\AdminUser;
use App\Repositories\AdminUserRepositoryInterface;
use \App\Repositories\PointPatientRepositoryInterface;
use App\Http\Requests\BaseRequest;
use App\Http\Responses\API\V1\Response;
use App\Services\APIUserServiceInterface;
use \App\Repositories\ChatHistoryRepositoryInterface;
use \App\Repositories\DoctorRepositoryInterface;
use App\Models\ChatHistory;

class ChatController extends Controller {
    protected $pointPatientRepository;
    protected $userService;
    protected $chatHistoryRepository;
    protected $doctorRepository;
    protected $quickBlox;
    protected $adminUserRepository;

    public function __construct(
        PointPatientRepositoryInterface $pointPatientRepository,
        APIUserServiceInterface $userService,
        ChatHistoryRepositoryInterface $chatHistoryRepository,
        DoctorRepositoryInterface $doctorRepository,
        QuickbloxController $quickbloxController,
        AdminUserRepositoryInterface $adminUserRepository
    ){
        $this->pointPatientRepository = $pointPatientRepository;  
        $this->userService = $userService;
        $this->chatHistoryRepository = $chatHistoryRepository;
        $this->doctorRepository = $doctorRepository;
        $this->quickBlox        = $quickbloxController;
        $this->adminUserRepository = $adminUserRepository;
    }

    public function addPoint(BaseRequest $request)
    {
        $point = $request->get('point', 0);
        if ($point <= 0) {
            return Response::response(40001);
        }

        $currentPatient = $this->userService->getUser();
        $patientPoint  = $this->pointPatientRepository->findByUserId($currentPatient->id);
        if (empty($patientPoint)) {
            $patientPoint = $this->pointPatientRepository->create(["user_id" => $currentPatient->id, "point" => $point]);
        } else {
            $this->pointPatientRepository->update($patientPoint, ["point" => $patientPoint->point + $point]);
        }
        
        return Response::response(200, [
            'point'=>$patientPoint->point
        ]);
    }

    public function usePoint(BaseRequest $request)
    {
        $point = $request->get('point', 0);
        if ($point <= 0) {
            return Response::response(40001);
        }

        $currentPatient = $this->userService->getUser();
        $patientPoint  = $this->pointPatientRepository->findByUserId($currentPatient->id);
        if (empty($patientPoint)) {
            $patientPoint = $this->pointPatientRepository->create(["user_id" => $currentPatient->id, "point" => 0]);
        } else {
            $this->pointPatientRepository->update($patientPoint, ["point" => $patientPoint->point - $point]);
        }

        return Response::response(200, [
            'point'=>$patientPoint->point
        ]);
    }

    public function checkChatState(Request $request)
    {
        $idDoctor = $request->get('idDoctor');
        if (!empty($idDoctor)) {
            $adminUserId = $idDoctor;
        }
        else
        {
            $idQuickDoctor = $request->get('idQuickDoctor');
            $userQuick = $this->quickBlox->getUserById($idQuickDoctor);
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
                $adminUser = $this->adminUserRepository->findByUsername($username);
                if( empty($adminUser) ) {
                    return Response::response(20004);
                }
                $adminUserId = $adminUser['id'];
            }
        }
        $currentPatient = $this->userService->getUser();
        $doctor = $this->doctorRepository->findByAdminUserId($adminUserId);
        $lastSession = $this->chatHistoryRepository->getLastSession($adminUserId, $currentPatient->id);
        $data = [
            'user_point' => $currentPatient->patientPoint->point,
            'is_enough' => $currentPatient->patientPoint->point >= $doctor->price_chat ? true : false
        ];
        
        if (!empty($lastSession)) {
            $timeNow = date('Y-m-d H:i:s');
            $deltaTimeStamp = 3 * 60; // via seconds
            $compareDate =  $lastSession->created_at;
            if ((strtotime($compareDate) + $deltaTimeStamp) >= strtotime($timeNow)) {
                $data['state'] = ChatHistory::CONTINUECHAT;
            } else {
                $data['state'] = ChatHistory::FINISHEDCHAT;
            } 
        } else {
            $data['state'] = ChatHistory::NEWCHAT;
        }

        return Response::response(200, $data);
    }

    public function startChat(Request $request)
    {
        $idDoctor = $request->get('idDoctor');
        if (!empty($idDoctor)) {
            $adminUserId = $idDoctor;
        }
        else
        {
            $idQuickDoctor = $request->get('idQuickDoctor');
            $userQuick = $this->quickBlox->getUserById($idQuickDoctor);
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
                $adminUser = $this->adminUserRepository->findByUsername($username);
                if( empty($adminUser) ) {
                    return Response::response(20004);
                }
                $adminUserId = $adminUser['id'];
            }
        }
        $currentPatient = $this->userService->getUser();
        $doctor = $this->doctorRepository->findByAdminUserId($adminUserId);

        if ($currentPatient->patientPoint->point < $doctor->price_chat) {
            return Response::response(200, ['status'=>false]);
        }

        // create row on table patient_point and minus point user
        if ($this->pointPatientRepository->prepareForStart($currentPatient, $doctor)) {
            return Response::response(200, ['status'=>true]);
        }

        return Response::response(200, ['status'=>false]);
    }

    public function sendFile(Request $request )
    {
        $input = $request->only(
            [
                'chat_id','file_patient_id'
            ]
        );
        if( !is_numeric($input['chat_id']) || ($input['chat_id'] <= 0) ) {
            return Response::response(40001);
        }

        $chatHistory = $this->chatHistoryRepository->find($input['chat_id']);
        if( empty($chatHistory) ) {
            return Response::response(20004);
        }

        try {
            $this->chatHistoryRepository->update($chatHistory, ['file_patient_id'=>$input['file_patient_id']]);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        return Response::response(200, [
            'status'=>true
        ]);
    }
}
