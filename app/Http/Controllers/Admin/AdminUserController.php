<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\V1\QuickbloxController;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Doctor;
use App\Models\DoctorSpecialty;
use App\Repositories\AdminUserRepositoryInterface;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Requests\PaginationRequest;
use App\Repositories\DoctorRepositoryInterface;
use App\Repositories\HospitalRepositoryInterface;
use App\Repositories\LevelRepositoryInterface;
use App\Repositories\PointDoctorRepositoryInterface;
use App\Repositories\SpecialtyRepositoryInterface;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\AdminUserRoleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{

    /** @var \App\Repositories\AdminUserRepositoryInterface */
    protected $adminUserRepository;
    protected $doctorRepository;
    protected $pointDoctorRepository;

    protected $quickblox;

    /** @var \App\Repositories\AdminUserRoleRepositoryInterface */
    protected $adminUserRoleRepository;

    /** @var FileUploadServiceInterface $fileUploadService */
    protected $fileUploadService;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    protected $hospitalRepository;
    protected $levelRepository;
    protected $specialtyRepository;

    public function __construct(
        AdminUserRepositoryInterface $adminUserRepository,
        FileUploadServiceInterface $fileUploadService,
        ImageRepositoryInterface $imageRepository,
        AdminUserRoleRepositoryInterface $adminUserRoleRepository,
        HospitalRepositoryInterface $hospitalRepository,
        SpecialtyRepositoryInterface $specialtyRepository,
        DoctorRepositoryInterface $doctorRepository,
        QuickbloxController $quickblox,
        PointDoctorRepositoryInterface $pointDoctorRepository,
        LevelRepositoryInterface $levelRepository
    )
    {
        $this->adminUserRepository = $adminUserRepository;
        $this->fileUploadService = $fileUploadService;
        $this->imageRepository = $imageRepository;
        $this->adminUserRoleRepository = $adminUserRoleRepository;
        $this->hospitalRepository = $hospitalRepository;
        $this->specialtyRepository = $specialtyRepository;
        $this->doctorRepository = $doctorRepository;
        $this->quickblox        = $quickblox;
        $this->pointDoctorRepository = $pointDoctorRepository;
        $this->levelRepository = $levelRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\PaginationRequest $request
     *
     * @return \Response
     */
    public function index(PaginationRequest $request)
    {
        $paginate['offset']     = $request->offset();
        $paginate['limit']      = $request->limit();
        $paginate['order']      = $request->order();
        $paginate['direction']  = $request->direction();
        $paginate['baseUrl']    = action('Admin\AdminUserController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->adminUserRepository->countByFilter($filter);
        $adminUsers = $this->adminUserRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);
        
        return view(
            'pages.admin.' . config('view.admin') . '.admin-users.index',
            [
                'adminUsers' => $adminUsers,
                'count'      => $count,
                'paginate'   => $paginate,
                'keyword'    => $keyword
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        return view(
            'pages.admin.' . config('view.admin') . '.admin-users.edit',
            [
                'levels' => $this->levelRepository->all(),
                'hospitals' => $this->hospitalRepository->all(),
                'specialties' => $this->specialtyRepository->all(),
                'isNew'     => true,
                'adminUser' => $this->adminUserRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Response
     */
    public function store(AdminUserRequest $request)
    {
        $input = $request->only(
            [
                'username',
                'name',
                'email',
                'password',
                're_password',
                'role','phone'
            ]
        );
        $inputQuickBlox = [
            'username' => $input['username'],
            'password' => $input['username'],
            'email'    => $input['email'] ,
            'external_user_id' => '',
            'facebook_id' => '',
            'twitter_id' => '',
            'full_name'=> $input['name'] ,
            'phone'    => $input['phone'],
            'website' => '',
        ];
        $userQuick = $this->quickblox->signUp($inputQuickBlox);
        if(isset($userQuick['errors']))
        {
            return redirect()
                ->back()
                ->withErrors(trans('admin.errors.general.save_failed'));
        }
        $idQuick = $userQuick['user']['id'];
        try {
            DB::beginTransaction();
            $input['status'] = 2;

            $adminUser = $this->adminUserRepository->create($input);
            if (empty($adminUser)) {
                return redirect()
                    ->back()
                    ->withErrors(trans('admin.errors.general.save_failed'));
            }

            if($input['role'][0] == 'admin')
            {
                $inputDoctor = $request->only(
                    [
                        'level_id','experience','description','gender','city','address','hospital_id','position','birthday','name'
                    ]
                );
                $inputDoctor['admin_user_id'] = $adminUser->id;
                $this->doctorRepository->create($inputDoctor);

            }
            $pointDoctor = $this->pointDoctorRepository->create(['admin_user_id'=>$adminUser->id,'point'=>0]);

            $this->adminUserRoleRepository->setAdminUserRoles($adminUser->id, $request->input('role', []));
            $adminUser->specialties()->sync($request->input('specialty_id'));


            if ($request->hasFile('profile_image')) {
                $file       = $request->file('profile_image');

                $image = $this->fileUploadService->upload(
                    'user_profile_image',
                    $file,
                    [
                        'entity_type' => 'user_profile_image',
                        'entity_id'   => $adminUser->id,
                        'title'       => $request->input('name', ''),
                    ]
                );

                if (!empty($image)) {
                    $this->adminUserRepository->update($adminUser, ['profile_image_id' => $image->id]);
                }
            }

            $this->adminUserRepository->update($adminUser, ['quick_id' => $idQuick]);
            DB::commit();

            return redirect()
                ->action('Admin\AdminUserController@index')
                ->with('message-success', trans('admin.messages.general.create_success'));


        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors(trans('admin.errors.general.save_failed'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function show($id)
    {
        $adminUser = $this->adminUserRepository->find($id);
        if (empty($adminUser)) {
            \App::abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.admin-users.edit',
            [
                'levels' => $this->levelRepository->all(),
                'hospitals' => $this->hospitalRepository->all(),
                'specialties' => $this->specialtyRepository->all(),
                'isNew'     => false,
                'adminUser' => $adminUser,
                'adminUserSpecialty' => $adminUser->specialties->keyBy('id'),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param      $request
     *
     * @return \Response
     */
    public function update($id, AdminUserRequest $request)
    {
        /** @var \App\Models\AdminUser $adminUser */
        $adminUser = $this->adminUserRepository->find($id);
        if (empty($adminUser)) {
            \App::abort(404);
        }
        $doctor = Doctor::where('admin_user_id',$id)->first();
        $input = $request->only(
            [
                'username',
                'name',
                'email',
                'password',
                're_password',
                'role','phone'
            ]
        );
        if($input['password'] == null)
        {
            unset($input['password']);
            unset($input['re_password']);
        }
        $inputDoctor = [
            'name' => $input['name']
        ];
        try {
            DB::beginTransaction();

        $adminUser = $this->adminUserRepository->update($adminUser, $input);

        if($input['role'][0] == 'super_user')
        {
            if(!empty($doctor))
            {
                $doctor->delete();
                $doctor->save();
            }

            $specialties = DoctorSpecialty::where('admin_user_id',$adminUser)->get();
            if(!empty($specialties))
            {
                foreach($specialties as $row)
                {
                    $row->delete();
                    $row->save();
                }
            }

        }
        if($input['role'][0] == 'admin')
        {
            $doctor = $this->doctorRepository->update($doctor,$inputDoctor);
            $inputDoctor = $request->only(
                [
                    'level_id','experience','description','gender','city','address','hospital_id','position','birthday'
                ]
            );
            $inputDoctor['admin_user_id'] = $adminUser->id;
            $doctor = $this->doctorRepository->update($doctor,$inputDoctor);
            $adminUser->specialties()->sync($request->input('specialty_id'));


        }
        $this->adminUserRoleRepository->setAdminUserRoles($id, $request->input('role', []));

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');

            $newImage = $this->fileUploadService->upload(
                'user_profile_image',
                $file,
                [
                    'entity_type' => 'user_profile_image',
                    'entity_id'   => $adminUser->id,
                    'title'       => $request->input('name', ''),
                ]
            );

            if (!empty($newImage)) {
                $oldImage = $adminUser->coverImage;
                if (!empty($oldImage)) {
                    $this->fileUploadService->delete($oldImage);
                }

                $this->adminUserRepository->update($adminUser, ['profile_image_id' => $newImage->id]);
            }
        }
            DB::commit();

        return redirect()
            ->action('Admin\AdminUserController@show', [$id])
            ->with('message-success', trans('admin.messages.general.update_success'));
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors(trans('admin.errors.general.save_failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function destroy($id)
    {
        /** @var \App\Models\AdminUser $adminUser */
        $adminUser = $this->adminUserRepository->find($id);
        $doctor = $adminUser->doctor;
        if (empty($adminUser)) {
            \App::abort(404);
        }
        $this->adminUserRepository->delete($adminUser);
        $doctor->delete();
        $doctor->save();
        $this->quickblox->deleteUser($adminUser->quick_id);

        return redirect()
            ->action('Admin\AdminUserController@index')
            ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
