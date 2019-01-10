<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Repositories\PointPatientRepositoryInterface;
use App\Services\APIUserServiceInterface;
use App\Services\PaymentServiceInterface;
use App\Services\UserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $userService;
    protected $pointPatientRepository;

    public function __construct
    (
        PaymentServiceInterface $paymentService,
        APIUserServiceInterface $APIUserService,
        PointPatientRepositoryInterface $pointPatientRepository
    )
    {
        $this->paymentService = $paymentService;
        $this->userService    = $APIUserService;
        $this->pointPatientRepository = $pointPatientRepository;
    }

    public function index(Request $request)
    {
        $input = $request->only([
            'point'
        ]);
        $user = $this->userService->getUser();
        $receiver=RECEIVER;
        //Mã đơn hàng
        $order_code='NL_'.time();
        //Khai báo url trả về
        $return_url= "http://doctor.dev.vn/api/patient/v1/payment/success";
        // Link nut hủy đơn hàng
        $cancel_url= "";
        //Giá của cả giỏ hàng
        $txh_name =$user->name;
        $txt_email =$user->email;
        $txt_phone =$user->telephone;
        $price =(int)$input['point'];
        //Thông tin giao dịch
        $transaction_info="Thong tin giao dich";
        $currency= "vnd";
        $quantity=1;
        $tax=0;
        $discount=0;
        $fee_cal=0;
        $fee_shipping=0;
        $order_description="Thong tin don hang: ".$order_code;
        $buyer_info=$txh_name."*|*".$txt_email."*|*".$txt_phone;
        $affiliate_code="";

        //Tạo link thanh toán đến nganluong.vn
        $url= $this->paymentService->buildCheckoutUrlExpand($return_url, $receiver, $transaction_info, $order_code, $price, $currency, $quantity, $tax, $discount , $fee_cal,    $fee_shipping, $order_description, $buyer_info , $affiliate_code);
        //$url= $nl->buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price);


        //echo $url; die;
//        if ($order_code != "") {
//            //một số tham số lưu ý
//            //&cancel_url=http://yourdomain.com --> Link bấm nút hủy giao dịch
//            //&option_payment=bank_online --> Mặc định forcus vào phương thức Ngân Hàng
//            $url .='&cancel_url='. $cancel_url;dd($url);
//            //$url .='&option_payment=bank_online';
//
//            echo '<meta http-equiv="refresh" content="0; url='.$url.'" >';
//            //&lang=en --> Ngôn ngữ hiển thị google translate
//        }
         return Response::response(200, [
                'url'=>$url
                ]);
    }


    public function success(Request $request)
    {
        $data = $request->only([
            'transaction_info',
            'order_code',
            'price',
            'payment_id',
            'payment_type',
            'error_text',
            'secure_code'
        ]);
        $transaction_info =$data['transaction_info'];
        $order_code =$data['order_code'];
        $price =$data['price'];
        $payment_id =$data['payment_id'];
        $payment_type =$data['payment_type'];
        $error_text =$data['error_text'];
        $secure_code =$data['secure_code'];
        //Khai báo đối tượng của lớp NL_Checkout

        //Tạo link thanh toán đến nganluong.vn
        $checkpay= $this->paymentService->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);

        if ($checkpay) {
            $point = $price;
            $currentPatient = $this->userService->getUser();
            $patientPoint  = $this->pointPatientRepository->findByUserId($currentPatient->id);
            if (empty($patientPoint)) {
                $patientPoint = $this->pointPatientRepository->create(["user_id" => $currentPatient->id, "point" => $point]);
            } else {
                $this->pointPatientRepository->update($patientPoint, ["point" => $patientPoint->point + $point]);
            }

            return Response::response(200, [
                'point'=>$patientPoint->point,
                'status' => true
            ]);
        }else{
            return Response::response(200, [
                'status' => false
            ]);
        }
    }



    public function test()
    {
        $receiver=RECEIVER;
        //Mã đơn hàng
        $order_code='NL_'.time();
        //Khai báo url trả về
        $return_url= "http://doctor.dev.vn/api/patient/v1/payment/success";
        // Link nut hủy đơn hàng
        $cancel_url= "";
        //Giá của cả giỏ hàng
        $txh_name ='hieu';
        $txt_email ='hieu@gmail.com';
        $txt_phone ='0976221294';
        $price =(int)'5000000';
        //Thông tin giao dịch
        $transaction_info="Thong tin giao dich";
        $currency= "vnd";
        $quantity=1;
        $tax=0;
        $discount=0;
        $fee_cal=0;
        $fee_shipping=0;
        $order_description="Thong tin don hang: ".$order_code;
        $buyer_info=$txh_name."*|*".$txt_email."*|*".$txt_phone;
        $affiliate_code="";

        //Tạo link thanh toán đến nganluong.vn
        $url= $this->paymentService->buildCheckoutUrlExpand($return_url, $receiver, $transaction_info, $order_code, $price, $currency, $quantity, $tax, $discount , $fee_cal,    $fee_shipping, $order_description, $buyer_info , $affiliate_code);
        //$url= $nl->buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price);


        //echo $url; die;
//        if ($order_code != "") {
//            //một số tham số lưu ý
//            //&cancel_url=http://yourdomain.com --> Link bấm nút hủy giao dịch
//            //&option_payment=bank_online --> Mặc định forcus vào phương thức Ngân Hàng
//            $url .='&cancel_url='. $cancel_url;dd($url);
//            //$url .='&option_payment=bank_online';
//
//            echo '<meta http-equiv="refresh" content="0; url='.$url.'" >';
//            //&lang=en --> Ngôn ngữ hiển thị google translate
//        }
        return Redirect::to($url);
    }
}