<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminUserServiceInterface;
use App\Http\Requests\Admin\SignInRequest;

class AuthController extends Controller
{
    /** @var \App\Services\AdminUserServiceInterface AdminUserService */
    protected $adminUserService;

    public function __construct(AdminUserServiceInterface $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    public function getSignIn()
    {
        return view('pages.admin.' . config('view.admin') . '.auth.signin_v2', [
        ]);
    }

    public function postSignIn(SignInRequest $request)
    {
        $adminUser = $this->adminUserService->signIn($request->all());
        if (empty($adminUser)) {
            return redirect()->action('Admin\AuthController@getSignIn');
        }
        if($adminUser->roles[0]->role == 'super_user')
        {
            return \RedirectHelper::intended(action('Admin\IndexController@index'), $this->adminUserService->getGuardName());
        }
        else
        {
            return \RedirectHelper::intended(action('Admin\ClinicController@index'), $this->adminUserService->getGuardName());
        }


    }

    public function postSignOut()
    {
        $this->adminUserService->signOut();

        return redirect()->action('Admin\AuthController@getSignIn');
    }
}
