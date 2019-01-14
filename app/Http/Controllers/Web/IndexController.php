<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\PaymentRepositoryInterface;
use App\Repositories\PointPatientRepositoryInterface;
use App\Services\PaymentServiceInterface;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $paymentService;
    protected $paymentRepository;
    protected $pointPatientRepository;
    protected $userRepository;

    public function __construct
    (
        PaymentServiceInterface $paymentService,
        PointPatientRepositoryInterface $pointPatientRepository,
        PaymentRepositoryInterface $paymentRepository,
        UserRepository $userRepository
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->paymentService    = $paymentService;
        $this->pointPatientRepository = $pointPatientRepository;
        $this->userRepository         = $userRepository;
    }
    public function index()
    {
        return view('pages.web.default.index', [
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
            } else {
                $this->pointPatientRepository->update($patientPoint, ["point" => $patientPoint->point + $point]);
            }

            return view('pages.web.default.index', [
                'status'=>true
            ]);
        }else{
            return view('pages.web.default.index', [
                'status'=>false
            ]);
        }
    }
}
