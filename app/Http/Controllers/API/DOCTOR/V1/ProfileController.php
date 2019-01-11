<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Responses\API\V1\Response;
use App\Repositories\AdminUserRepositoryInterface;
use App\Repositories\DoctorRepositoryInterface;
use App\Repositories\HospitalRepositoryInterface;
use App\Repositories\LevelRepositoryInterface;
use App\Repositories\SpecialtyRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    protected  $adminUserService;
    protected  $adminUserRepository;
    protected  $doctorRepository;
    protected  $hospitalRepository;
    protected  $levelRepository;
    protected  $specialtyRepository;

    public function __construct
    (
        APIUserServiceInterface $APIUserService,
        AdminUserRepositoryInterface $adminUserRepository,
        DoctorRepositoryInterface $doctorRepository,
        HospitalRepositoryInterface $hospitalRepository,
        LevelRepositoryInterface $levelRepository,
        SpecialtyRepositoryInterface $specialtyRepository
    )
    {
        $this->adminUserService = $APIUserService;
        $this->adminUserRepository = $adminUserRepository;
        $this->doctorRepository    = $doctorRepository;
        $this->hospitalRepository = $hospitalRepository;
        $this->levelRepository     = $levelRepository;
        $this->specialtyRepository  = $specialtyRepository;

    }
    public function index()
    {
        $doctor = $this->adminUserService->getUser();
        $data = [
            'user' => $doctor->toAPIArrayProfile(),
            'accountQuick' => [
                'username' => $doctor->username,
                'password' => $doctor->username
            ]
        ];

        return Response::response(200,$data);
    }

    public function update(Request $request)
    {
        $inputAdminUser = $request->only([
            'name','phone','email'
        ]);

        $inputDoctor = $request->only([
            'name','position','hospital_id','birthday',
            'gender','sub_phone','address',"level_id",
            "experience",
            "bank_name",
            "bank_address",
            "bank_number",
            "bank_owner",
            "description"
        ]);
        $adminUser = $this->adminUserService->getUser();
        $doctor = $this->doctorRepository->findByAdminUserId($adminUser->id);
        try {
            DB::beginTransaction();

            $adminUser = $this->adminUserRepository->update($adminUser,$inputAdminUser);

            $doctor = $this->doctorRepository->update($doctor,$inputDoctor);
            $adminUser->specialties()->sync($request->input('specialties_id'));
            DB::commit();
            $data = $adminUser->toAPIArrayProfile();

            return Response::response(200,$data);

        } catch (\Exception $ex) {
            DB::rollBack();

            return Response::response(200,['status'=>false]);
        }
    }

    public function listData()
    {
        $hospitals = $this->hospitalRepository->all();
        $specialties = $this->specialtyRepository->all();
        $levels     = $this->levelRepository->all();

        foreach($hospitals as $key=>$hospital)
        {
            $hospitals[$key] = $hospital->toAPIArrayListDataForProfileDoctor();
        }

        foreach($specialties as $key=>$specialty)
        {
            $specialties[$key] = $specialty->toAPIArrayListDataForProfileDoctor();
        }

        foreach($levels as $key=>$level)
        {
            $levels[$key] = $level->toAPIArrayListDataForProfileDoctor();
        }

        return Response::response(200,[
            'hospitals'=>$hospitals,
            'levels'=>$levels,
            'specialties' => $specialties
        ]);


    }
}
