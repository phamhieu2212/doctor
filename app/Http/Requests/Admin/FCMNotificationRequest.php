<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\FCMNotificationRepositoryInterface;

class FCMNotificationRequest extends BaseRequest
{

    /** @var \App\Repositories\FCMNotificationRepositoryInterface */
    protected $fCMNotificationRepository;

    public function __construct(FCMNotificationRepositoryInterface $fCMNotificationRepository)
    {
        $this->fCMNotificationRepository = $fCMNotificationRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->fCMNotificationRepository->rules();
    }

    public function messages()
    {
        return $this->fCMNotificationRepository->messages();
    }

}
