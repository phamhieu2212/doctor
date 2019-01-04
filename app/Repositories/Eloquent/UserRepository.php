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
}
