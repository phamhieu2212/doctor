<?php namespace App\Repositories\Eloquent;

use \App\Repositories\ChatHistoryRepositoryInterface;
use \App\Models\ChatHistory;

class ChatHistoryRepository extends SingleKeyModelRepository implements ChatHistoryRepositoryInterface
{

    public function getBlankModel()
    {
        return new ChatHistory();
    }

    public function getLastSession($doctorId, $patientId)
    {
        $lastSession = $this->getBlankModel()->where('user_id', '=', $patientId)->where('admin_user_id', '=', $doctorId)
                            ->orderBy('id', 'desc')->first();

        return $lastSession;
    }

}
