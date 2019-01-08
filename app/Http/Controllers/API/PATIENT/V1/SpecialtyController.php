<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Repositories\SpecialtyRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialtyController extends Controller
{
    protected $specialtyRepository;

    public function __construct
    (
        SpecialtyRepositoryInterface $specialtyRepository
    )
    {
        $this->specialtyRepository = $specialtyRepository;
    }
    public function index()
    {

        $specialties = $this->specialtyRepository->all();
        foreach($specialties as $key=>$specialty)
        {
            $specialties[$key] = $specialty->toAPIArray();
        }

        return Response::response(200,$specialties
        );
    }
}
