<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Controllers\API\V1\QuickbloxController;
use App\Http\Responses\API\V1\Response;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Repositories\DoctorRepositoryInterface;
use App\Repositories\SpecialtyRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;

class DoctorController extends Controller
{
    protected $doctorRepository;

    protected $specialtyRepository;

    protected $quickblox;

    public function __construct(
        DoctorRepositoryInterface $doctorRepository,
        SpecialtyRepositoryInterface $specialtyRepository,
        QuickbloxController $quickblox
    ){
        $this->doctorRepository = $doctorRepository;
        $this->specialtyRepository = $specialtyRepository;
        $this->quickblox = $quickblox;
    }
    public function index(PaginationRequest $request)
    {
        $date = ['sunday','monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->get('offset',$request->offset());
        $paginate['order']      = 'id';
        $paginate['direction']  = 'desc';
        $filter = [];
        $keyword = $request->get('keyword');
        $day = $request->get('day');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }
        if (!empty($day)) {
            $filter['day_start'] = date("Y-m-d 00:00:00", strtotime($date[$day].' this week'));
            $filter['day_end'] = date("Y-m-d 23:59:59", strtotime($date[$day].' this week'));
        }
        if($request->has('gender') and $request->get('specialty_id') != null)
        {
            $filter['gender'] = $request->get('gender');
        }
        if($request->has('specialty_id') and $request->get('specialty_id') != null)
        {
            $filter['specialty_id'] = $request->get('specialty_id');
        }
        $doctors = $this->doctorRepository->getByFilter($filter,$paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']); // change get() to geEnabled as requirement
        foreach( $doctors as $key => $doctor ) {
            $doctors[$key] = $doctor->toAPIArraySearch();
        }
        $specialties = $this->specialtyRepository->all();

        return Response::response(200,$doctors
            );
    }

    public function detail($idDoctor)
    {
        $now =  Carbon::now();
        $endDate =  date('Y-m-d 23:59:59', strtotime($now));
        $startDate =  date('Y-m-d 00:00:00', strtotime($now));
        $clinics = Clinic::where('admin_user_id',$idDoctor)
            ->where('status',1)->get();
        $doctor = Doctor::where('admin_user_id',$idDoctor)->first();
        foreach($clinics as $key=>$clinic)
        {
            $clinics[$key] = $clinic->toAPIArrayListPlanDoctor($idDoctor,$startDate,$endDate);
        }
        $data= [
            'doctor'=>$doctor->toAPIArrayDetail(),
            'clinics'=> $clinics
        ];

        return Response::response(200,
            $data
        );

    }
}
