<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends AuthenticatableRepository implements UserRepositoryInterface
{
    protected $querySearchTargets = ['name', 'email', 'telephone', 'address', 'locale', 'birthday'];

    public function getBlankModel()
    {
        return new User();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }

    public function getByFilterWithListId($idListUserContact,$filter, $order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
        $query = $this->buildOrder($query, $filter, $order, $direction);

        return $query->whereIn('id',$idListUserContact)->skip($offset)->take($limit)->get();

    }

    public function countAllWithFilter($startDate,$endDate)
    {
        if($startDate == null and $endDate == null)
        {
            return User::count();
        }
        else
        {
            $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
            return User::where('created_at','>=',$startDate)
                ->where('created_at','<=',$endDate)->count();
        }

    }
}
