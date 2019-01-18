<?php namespace App\Services\Production;

use \App\Services\PaymentServiceInterface;

class PaymentService extends BaseService implements PaymentServiceInterface
{
    public $nganluong_url = PAYMENT_URL;
    // Mã website của bạn đăng ký trong chức năng tích hợp thanh toán của NgânLượng.vn.
    public $merchant_site_code = MERCHANT_ID; //100001 chỉ là ví dụ, bạn hãy thay bằng mã của bạn
    // Mật khẩu giao tiếp giữa website của bạn và NgânLượng.vn.
    public $secure_pass= MERCHANT_PASS; //d685739bf1 chỉ là ví dụ, bạn hãy thay bằng mật khẩu của bạn
    // Nếu bạn thay đổi mật khẩu giao tiếp trong quản trị website của chức năng tích hợp thanh toán trên NgânLượng.vn, vui lòng update lại mật khẩu này trên website của bạn
    public $affiliate_code = ''; //Mã đối tác tham gia chương trình liên kết của NgânLượng.vn

    public function buildCheckoutUrlExpand($return_url, $receiver, $transaction_info, $order_code, $price, $currency = 'vnd', $quantity = 1, $tax = 0, $discount = 0, $fee_cal = 0, $fee_shipping = 0, $order_description = '', $buyer_info = '', $affiliate_code = '')
    {
        if ($affiliate_code == "") $affiliate_code = $this->affiliate_code;
        $arr_param = array(
            'merchant_site_code'=>	strval($this->merchant_site_code),
            'return_url'		=>	strval(strtolower($return_url)),
            'receiver'			=>	strval($receiver),
            'transaction_info'	=>	strval($transaction_info),
            'order_code'		=>	strval($order_code),
            'price'				=>	strval($price),
            'currency'			=>	strval($currency),
            'quantity'			=>	strval($quantity),
            'tax'				=>	strval($tax),
            'discount'			=>	strval($discount),
            'fee_cal'			=>	strval($fee_cal),
            'fee_shipping'		=>	strval($fee_shipping),
            'order_description'	=>	strval($order_description),
            'buyer_info'		=>	strval($buyer_info), //"Họ tên người mua *|* Địa chỉ Email *|* Điện thoại *|* Địa chỉ nhận hàng"
            'affiliate_code'	=>	strval($affiliate_code)
        );

        $secure_code ='';
        $secure_code = implode(' ', $arr_param) . ' ' . $this->secure_pass;
        //var_dump($secure_code). "<br/>";
        $arr_param['secure_code'] = md5($secure_code);
        //echo $arr_param['secure_code'];
        /* */
        $redirect_url = $this->nganluong_url;
        if (strpos($redirect_url, '?') === false) {
            $redirect_url .= '?';
        } else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false) {
            $redirect_url .= '&';
        }

        /* */
        $url = '';
        foreach ($arr_param as $key=>$value) {
            $value = urlencode($value);
            if ($url == '') {
                $url .= $key . '=' . $value;
            } else {
                $url .= '&' . $key . '=' . $value;
            }
        }
        //echo $url;
        // die;
        return $redirect_url.$url;
    }

    public function verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code)
    {
        // Tạo mã xác thực từ chủ web
        $str = '';
        $str .= ' ' . strval($transaction_info);
        $str .= ' ' . strval($order_code);
        $str .= ' ' . strval($price);
        $str .= ' ' . strval($payment_id);
        $str .= ' ' . strval($payment_type);
        $str .= ' ' . strval($error_text);
        $str .= ' ' . strval($this->merchant_site_code);
        $str .= ' ' . strval($this->secure_pass);

        // Mã hóa các tham số
        $verify_secure_code = '';
        $verify_secure_code = md5($str);

        // Xác thực mã của chủ web với mã trả về từ nganluong.vn
        if ($verify_secure_code === $secure_code) return true;
        else return false;
    }
}
