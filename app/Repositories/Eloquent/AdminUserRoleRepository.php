<?php

namespace App\Repositories\Eloquent;

use App\Models\AdminUser;
use App\Repositories\AdminUserRoleRepositoryInterface;
use App\Models\AdminUserRole;

class AdminUserRoleRepository extends SingleKeyModelRepository implements AdminUserRoleRepositoryInterface
{
    public function getBlankModel()
    {
        return new AdminUserRole();
    }

    public function rules()
    {
        return [
        ];
    }

    public function create($input)
    {
        $role = array_get($input, 'role', '');
        if (!array_key_exists($role, config('admin_user.roles', []))) {
            return;
        }

        return parent::create($input);
    }

    public function deleteByAdminUserId($id)
    {
        $records = $this->getByAdminUserId($id);
        if( count($records) ) {
            foreach( $records as $record ) {
                $this->delete($record);
            }
        }
        
        return true;
    }

    public function setAdminUserRoles($adminUserId, $roles)
    {
        $this->deleteByAdminUserId($adminUserId);
        foreach ($roles as $role) {
            $this->create(
                [
                    'admin_user_id' => $adminUserId,
                    'role'          => $role,
                ]
            );
        }
    }

    public function getAllAdminUserByRoleWithFilter($role,$startDate,$endDate)
    {
        if($startDate == null and $endDate == null)
        {
            $listAdminId = AdminUserRole::where('role',$role)->pluck('admin_user_id');
            return AdminUser::whereIn('id',$listAdminId)->get();
        }
        else
        {
            $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
            $listAdminId = AdminUserRole::where('role',$role)->pluck('admin_user_id');
            return AdminUser::whereIn('id',$listAdminId)
                ->where('created_at','>=',$startDate)->where('created_at','<=',$endDate)->get();
        }

    }

    public function countAllAdminUserByRoleWithFilter($role,$startDate,$endDate)
    {
        if($startDate == null and $endDate == null)
        {
            $listAdminId = AdminUserRole::where('role',$role)->pluck('admin_user_id');
            return AdminUser::whereIn('id',$listAdminId)->count();
        }
        else
        {
            $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
            $listAdminId = AdminUserRole::where('role',$role)->pluck('admin_user_id');
            return AdminUser::whereIn('id',$listAdminId)
                ->where('created_at','>=',$startDate)->where('created_at','<=',$endDate)->count();
        }

    }
}
