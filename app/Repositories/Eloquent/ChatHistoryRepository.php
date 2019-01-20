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
    public function countAllWithFilter($startDate,$endDate)
    {
        if($startDate == null and $endDate == null)
        {
            return ChatHistory::count();
        }
        else
        {
            $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
            return ChatHistory::where('created_at','>=',$startDate)
                ->where('created_at','<=',$endDate)->count();
        }
    }

}
