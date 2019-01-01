<?php
namespace App\Http\Controllers\API\PATIENT\V1;
use App\Http\Controllers\Controller;
use \App\Repositories\PointPatientRepositoryInterface;
use App\Http\Requests\BaseRequest;
use App\Http\Responses\API\V1\Response;
use App\Services\APIUserServiceInterface;

class PatientController extends Controller {
    protected $pointPatientRepository;
    protected $userService;

    public function __construct(
        PointPatientRepositoryInterface $pointPatientRepository,
        APIUserServiceInterface $userService
    ){
        $this->pointPatientRepository = $pointPatientRepository;  
        $this->userService = $userService;  
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
            $this->pointPatientRepository->update($patientPoint, ["point" => $point]);
        }
        
        return Response::response(200, $point); 
    }
}
