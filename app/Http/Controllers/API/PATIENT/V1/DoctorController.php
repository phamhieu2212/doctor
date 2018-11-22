<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Repositories\DoctorRepositoryInterface;
use App\Repositories\SpecialtyRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;

class DoctorController extends Controller
{
    protected $doctorRepository;

    protected $specialtyRepository;

    public function __construct(
        DoctorRepositoryInterface $doctorRepository,
        SpecialtyRepositoryInterface $specialtyRepository
    ){
        $this->doctorRepository = $doctorRepository;
        $this->specialtyRepository = $specialtyRepository;
    }
    public function index(PaginationRequest $request)
    {
        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->offset();
        $paginate['order']      = 'id';
        $paginate['direction']  = 'desc';
        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }
        if($request->has('gender'))
        {
            $filter['gender'] = $request->get('gender');
        }
        if($request->has('specialty_id'))
        {
            $filter['specialty_id'] = $request->get('specialty_id');
        }
        $doctors = $this->doctorRepository->getByFilter($filter,$paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']); // change get() to geEnabled as requirement
        foreach( $doctors as $key => $doctor ) {
            $doctors[$key] = $doctor->toAPIArray();
        }
        $specialties = $this->specialtyRepository->all();

        return Response::response(200,
            [
                'doctors'=> $doctors,
                'specialties' => $specialties
            ]
            );
    }
}
