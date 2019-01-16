<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Responses\API\V1\Response;
use App\Models\PhoneAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function phone()
    {
        $phoneAdmin = PhoneAdmin::first();
        if( empty($phoneAdmin) ) {
            return Response::response(20004);
        }
        return Response::response(200,$phoneAdmin['phone']);
    }
}
