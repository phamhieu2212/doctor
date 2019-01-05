<?php
namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\Controller;
use App\Http\Responses\API\V1\Response;
use App\Http\Requests\BaseRequest;

class StaticDataController extends Controller {
    public function provinces()
    {
        $public_path = public_path();
        $file = $public_path .'/static/location.json';
        $masterData = json_decode(file_get_contents($file), true);

        foreach ($masterData as $key => $loc) {
            unset($masterData[$key]["districts"]);
        }

        return Response::response(200, $masterData);
    }

    public function districts($provinceId)
    {
        $public_path = public_path();
        $file = $public_path .'/static/location.json';
        $masterData = json_decode(file_get_contents($file), true);
        $districts = [];
        foreach($masterData as $key => $loc) {
            if ($masterData[$key]["id"] == $provinceId) {
                $districts = $masterData[$key]["districts"];
                break;
            }
        }

        if ( empty($districts)) {
            return Response::response(20004);
        }

        return Response::response(200, $districts);
    }
}
