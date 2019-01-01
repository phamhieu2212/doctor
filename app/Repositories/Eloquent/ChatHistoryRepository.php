<?php namespace App\Repositories\Eloquent;

use \App\Repositories\ChatHistoryRepositoryInterface;
use \App\Models\ChatHistory;

class ChatHistoryRepository extends SingleKeyModelRepository implements ChatHistoryRepositoryInterface
{

    public function getBlankModel()
    {
        return new ChatHistory();
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
