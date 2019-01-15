<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Request;
use App\Http\Responses\API\V1\Response;
use App\Repositories\CallHistoryRepositoryInterface;
use App\Repositories\ChatHistoryRepositoryInterface;
use Carbon\Carbon;

class RateController extends Controller
{
    protected $chatHistoryRepository;

    protected $callHistoryRepository;

    public function __construct
    (
        ChatHistoryRepositoryInterface $chatHistoryRepository,
        CallHistoryRepositoryInterface $callHistoryRepository
    )
    {
        $this->chatHistoryRepository = $chatHistoryRepository;
        $this->callHistoryRepository = $callHistoryRepository;
    }

    public function update(Request $request)
    {

        $data = $request->only([
            'id','type'
        ]);
        $dataInput = $request->only([
            'rate','content',
        ]);
        $dataInput['rate_time'] = Carbon::now();

        if($data['type'] == 'chat' and $dataInput['rate'] <= 5)
        {
            $chatHistory = $this->chatHistoryRepository->findById($data['id']);
            $chatHistory = $this->chatHistoryRepository->update($chatHistory,$dataInput);
            return Response::response(200, $chatHistory->toAPIArrayRate());
        }
        elseif($data['type'] == 'call' and $dataInput['rate'] <= 5)
        {
            $callHistory = $this->callHistoryRepository->findById($data['id']);
            $callHistory = $this->callHistoryRepository->update($callHistory,$dataInput);
            return Response::response(200, $callHistory->toAPIArrayRate());
        }
        else
        {
            return Response::response(200,['status'=>false]);
        }

    }
}
