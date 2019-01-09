<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticController extends Controller
{
    public function index()
    {
        return [
            'data'=>[
                [
                "date"=>'2019-1',
                "log"=>
                    [
                        "total_amount"=>150000,
                        "total_call"=>8,
                        "total_duration"=>1560,
                        "total_chat"=>12,
                        "new_patient"=>2
                    ]
                ],
                [
                    "date"=>'2018-12',
                    "log"=>
                        [
                            "total_amount"=>15000,
                            "total_call"=>4,
                            "total_duration"=>1560,
                            "total_chat"=>12,
                            "new_patient"=>2
                        ]
                ]
            ]
        ];
    }
}
