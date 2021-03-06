<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\PhoneAdminRepositoryInterface;

class PhoneAdminRequest extends BaseRequest
{

    /** @var \App\Repositories\PhoneAdminRepositoryInterface */
    protected $phoneAdminRepository;

    public function __construct(PhoneAdminRepositoryInterface $phoneAdminRepository)
    {
        $this->phoneAdminRepository = $phoneAdminRepository;
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
        return $this->phoneAdminRepository->rules();
    }

    public function messages()
    {
        return $this->phoneAdminRepository->messages();
    }

}
