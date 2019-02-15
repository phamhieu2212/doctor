<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Repositories\LevelRepositoryInterface;
use App\Repositories\SpecialtyRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialtyController extends Controller
{
    protected $specialtyRepository;
    protected $levelRepository;

    public function __construct
    (
        SpecialtyRepositoryInterface $specialtyRepository,
        LevelRepositoryInterface $levelRepository
    )
    {
        $this->specialtyRepository = $specialtyRepository;
        $this->levelRepository     = $levelRepository;
    }
    public function index()
    {

        $specialties = $this->specialtyRepository->all();
        foreach($specialties as $key=>$specialty)
        {
            $specialties[$key] = $specialty->toAPIArray();
        }
        $levels = $this->levelRepository->all();
        foreach($levels as $key=>$level)
        {
            $levels[$key] = $level->toAPIArray();
        }

        return Response::response(200,[
                'levels'=>$levels,
                'specialties'=>$specialties
            ]
        );
    }
}
