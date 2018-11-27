<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    public function detail()
    {
        return [
            'code'=>200,
            'status'=>'success',
            'data'=>
                [

                    "status"=> 0|1|2,
                    "avatar"=> "abc.com",
                    "rate"=> 2,
                    "count_rate"=> 100,
                    "doctor"=> [
                        "hospital_name"=> "Bệnh viện Vđ",
                        "gender"=> "Nam",
                        "experience"=> "30 năm kinh nghiệm",
                        "position"=> "Giám đốc",
                        "description"=> "Mô tả"
                    ],
                    "plans"=> [
                        [ "clinic_name"=> "tên phòng khám",
                            "price"=> 300000,
                            "status"=> 1,
                            "day"=> 0-6,
                            "startHour" => 8,
                            "endHour" => 9,

                        ],
                        [ "clinic_name"=> "tên phòng khám",
                            "price"=> 300000,
                            "status"=> 1,
                            "day"=> 0-6,
                            "startHour" => 8,
                            "endHour" => 9,

                        ],
                    ]
                ]

        ];
    }
}
