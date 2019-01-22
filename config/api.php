<?php

return [
    200 => [
        'message'        => 'Success !!!',
        'httpStatusCode' => 200
    ],
    20004 => [
        'message'        => 'Sorry, No data matching !!!',
        'httpStatusCode' => 200
    ],
    40001 => [
        'message'        => 'Mật khẩu hoặc tên đăng nhập sai',
        'httpStatusCode' => 400
    ],
    40003 => [
        'message'        => 'Sai thông tin Client',
        'httpStatusCode' => 400
    ],
    40002 => [
        'message'        => 'Error, This username is already used !!!',
        'httpStatusCode' => 400
    ],
    40004 => [
        'message'        => 'Tài khoản accountKit không đúng',
        'httpStatusCode' => 400
    ],
    40005 => [
        'message'        => 'Không thể đăng kí tài khoản quickBlox',
        'httpStatusCode' => 400
    ],
    40101 => [
        'message'        => 'Tài khoản của bạn đã đăng nhập trên thiết bị khác, vui lòng đăng nhập lại',
        'httpStatusCode' => 401
    ],
    40301 => [
        'message'        => 'Error, Access was denied !!!',
        'httpStatusCode' => 403
    ],
    40401 => [
        'message'        => 'Error, The route is not defined !!!',
        'httpStatusCode' => 404
    ],
    40501 => [
        'message'        => 'Error, The HTTP method not allowed !!!',
        'httpStatusCode' => 405
    ],
    50001 => [
        'message'        => 'Sorry, Internal Server Error !!!',
        'httpStatusCode' => 500
    ],
    50002 => [
        'message'        => 'Sorry, Can\'t working with Database !!!',
        'httpStatusCode' => 500
    ],
];
