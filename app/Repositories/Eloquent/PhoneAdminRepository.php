<?php namespace App\Repositories\Eloquent;

use \App\Repositories\PhoneAdminRepositoryInterface;
use \App\Models\PhoneAdmin;

class PhoneAdminRepository extends SingleKeyModelRepository implements PhoneAdminRepositoryInterface
{

    public function getBlankModel()
    {
        return new PhoneAdmin();
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

}
