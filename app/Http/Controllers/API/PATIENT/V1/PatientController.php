<?php
namespace App\Http\Controllers\API\PATIENT\V1;
use App\Http\Controllers\Controller;
use \App\Repositories\PointPatientRepositoryInterface;
use App\Http\Requests\BaseRequest;
use App\Http\Responses\API\V1\Response;
use App\Services\APIUserServiceInterface;
use \App\Repositories\ChatHistoryRepositoryInterface;

class PatientController extends Controller {
    protected $pointPatientRepository;
    protected $userService;
    protected $chatHistoryRepository;

    public function __construct(
        PointPatientRepositoryInterface $pointPatientRepository,
        APIUserServiceInterface $userService,
        ChatHistoryRepositoryInterface $chatHistoryRepository
    ){
        $this->pointPatientRepository = $pointPatientRepository;  
        $this->userService = $userService;
        $this->chatHistoryRepository = $chatHistoryRepository;  
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
        
        return Response::response(200, $patientPoint->point); 
    }

    public function checkChatState($doctorId)
    {
        $currentPatient = $this->userService->getUser();
        $lastSession = $this->chatHistoryRepository->getLastSession($doctorId, $currentPatient->id);
        if (!empty($lastSession)) {
            $timeNow = date('Y-m-d H:i:s');
            $deltaTimeStamp = 3 * 24 *60 * 60; // via seconds
            $compareDate =  $lastSession->created_at;
            if ((strtotime($compareDate) + $deltaTimeStamp) >= strtotime($timeNow)) {
                return Response::response(200, true); 
            } 
        }

        return Response::response(200, false);
    }
}
