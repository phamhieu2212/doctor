<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\AdminUserRole;
use App\Models\User;
use App\Repositories\FCMNotificationRepositoryInterface;
use App\Http\Requests\Admin\FCMNotificationRequest;
use App\Http\Requests\PaginationRequest;
use Illuminate\Support\Facades\DB;

class FCMNotificationController extends Controller
{
    /** @var  \App\Repositories\FCMNotificationRepositoryInterface */
    protected $fCMNotificationRepository;

    public function __construct(
        FCMNotificationRepositoryInterface $fCMNotificationRepository
    ) {
        $this->fCMNotificationRepository = $fCMNotificationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param    \App\Http\Requests\PaginationRequest $request
     * @return  \Response
     */
    public function index(PaginationRequest $request)
    {
        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->offset();
        $paginate['order']      = $request->order();
        $paginate['direction']  = $request->direction();
        $paginate['baseUrl']    = action('Admin\FCMNotificationController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->fCMNotificationRepository->countByFilter($filter);
        $fCMNotifications = $this->fCMNotificationRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.fcm-notifications.index',
            [
                'fCMNotifications'    => $fCMNotifications,
                'count'         => $count,
                'paginate'      => $paginate,
                'keyword'       => $keyword
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Response
     */
    public function create()
    {
        return view(
            'pages.admin.' . config('view.admin') . '.fcm-notifications.edit',
            [
                'isNew'     => true,
                'fCMNotification' => $this->fCMNotificationRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    $request
     * @return  \Response
     */
    public function store(FCMNotificationRequest $request)
    {
        $input = $request->only(
            [

                            'user_type',
                            'content',
                            'title',
                        ]
        );
        if($input['user_type'] == 2)
        {
            $listIdAdmins = AdminUserRole::where('role','admin')->pluck('admin_user_id');
            try {
                DB::beginTransaction();

                foreach($listIdAdmins as $listIdAdmin)
                {
                    $input['user_id'] = $listIdAdmin;
                    $this->fCMNotificationRepository->create($input);
                }
                DB::commit();


            } catch (\Exception $ex) {
                DB::rollBack();

                return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
            }

        }
        elseif($input['user_type'] == 1)
        {
            $users = User::all();
            try {
                DB::beginTransaction();

                foreach($users as $user)
                {
                    $input['user_id'] = $user->id;
                    $this->fCMNotificationRepository->create($input);
                }
                DB::commit();


            } catch (\Exception $ex) {
                DB::rollBack();

                return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
            }
        }
        else
        {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }


        return redirect()->action('Admin\FCMNotificationController@index')
            ->with('message-success', trans('admin.messages.general.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param    int $id
     * @return  \Response
     */
    public function show($id)
    {
        $fCMNotification = $this->fCMNotificationRepository->find($id);
        if( empty($fCMNotification) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.fcm-notifications.edit',
            [
                'isNew' => false,
                'fCMNotification' => $fCMNotification,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int $id
     * @return  \Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    int $id
     * @param            $request
     * @return  \Response
     */
    public function update($id, FCMNotificationRequest $request)
    {
        return redirect()->action('Admin\FCMNotificationController@show', [$id])
            ->with('message-success', trans('admin.messages.general.update_success'));
        /** @var  \App\Models\FCMNotification $fCMNotification */
        $fCMNotification = $this->fCMNotificationRepository->find($id);
        if( empty($fCMNotification) ) {
            abort(404);
        }

        $input = $request->only(
            [
                            'user_id',
                            'user_type',
                            'content',
                            'sent_at',
                            'is_read',
                            'title',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $this->fCMNotificationRepository->update($fCMNotification, $input);

        return redirect()->action('Admin\FCMNotificationController@show', [$id])
                    ->with('message-success', trans('admin.messages.general.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Response
     */
    public function destroy($id)
    {
        /** @var  \App\Models\FCMNotification $fCMNotification */
        $fCMNotification = $this->fCMNotificationRepository->find($id);
        if( empty($fCMNotification) ) {
            abort(404);
        }
        $this->fCMNotificationRepository->delete($fCMNotification);

        return redirect()->action('Admin\FCMNotificationController@index')
                    ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
