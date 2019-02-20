<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FCMNotification;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\FCMNotificationRepositoryInterface;
use App\Repositories\PaymentRepositoryInterface;
use App\Repositories\PointPatientRepositoryInterface;
use App\Services\PaymentServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $paymentService;
    protected $paymentRepository;
    protected $pointPatientRepository;
    protected $userRepository;
    protected $FCMNotificationRepository;

    public function __construct
    (
        PaymentServiceInterface $paymentService,
        PointPatientRepositoryInterface $pointPatientRepository,
        PaymentRepositoryInterface $paymentRepository,
        UserRepository $userRepository,
        FCMNotificationRepositoryInterface $FCMNotificationRepository
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->paymentService    = $paymentService;
        $this->pointPatientRepository = $pointPatientRepository;
        $this->userRepository         = $userRepository;
        $this->FCMNotificationRepository = $FCMNotificationRepository;

    }
    public function index()
    {
        return view('pages.web.default.home', [
        ]);
    }
    public function rule()
    {
        return view('pages.web.default.rule', [
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
            $payment = Payment::where('order_code',$order_code)->where('status',0)->first();

            $currentPatient = $this->userRepository->findById($payment['user_id']);
            $payment->status = 1;
            $payment->save();
            $patientPoint  = $this->pointPatientRepository->findByUserId($currentPatient->id);
            if (empty($patientPoint)) {
                $patientPoint = $this->pointPatientRepository->create(["user_id" => $currentPatient->id, "point" => $point]);
                try {
                    $this->pointPatientRepository->update($patientPoint, ["point" => $patientPoint->point + $point]);
                } catch (\Exception $e) {
                    return view('pages.web.default.index', [
                        'status'=>false
                    ]);
                }
            } else {
                try {
                    $this->pointPatientRepository->update($patientPoint, ["point" => $patientPoint->point + $point]);
                } catch (\Exception $e) {
                    return view('pages.web.default.index', [
                        'status'=>false
                    ]);
                }
            }
            $this->FCMNotificationRepository->create([
                'user_id'=> $currentPatient->id,
                'user_type'=>FCMNotification::PATIENT,
                'content'=>'Quý khách vừa nạp thành công '.$point.' điểm vào tài khoản!',
                'title'=>'Hệ thống thanh toán',
                'sent_at'=> Carbon::now(),
                'is_read'=>0

            ]);

            return view('pages.web.default.index', [
                'status'=>true
            ]);
        }
        else{
            return view('pages.web.default.index', [
                'status'=>false
            ]);
        }
    }
}
