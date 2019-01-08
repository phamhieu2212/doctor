<?php namespace App\Repositories\Eloquent;

use \App\Repositories\LevelRepositoryInterface;
use \App\Models\Level;

class LevelRepository extends SingleKeyModelRepository implements LevelRepositoryInterface
{

    public function getBlankModel()
    {
        return new Level();
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
