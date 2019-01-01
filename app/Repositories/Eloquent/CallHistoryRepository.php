<?php namespace App\Repositories\Eloquent;

use \App\Repositories\CallHistoryRepositoryInterface;
use \App\Models\CallHistory;

class CallHistoryRepository extends SingleKeyModelRepository implements CallHistoryRepositoryInterface
{

    public function getBlankModel()
    {
        return new CallHistory();
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
