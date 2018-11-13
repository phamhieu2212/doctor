<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\HospitalRepositoryInterface;

class HospitalRequest extends BaseRequest
{

    /** @var \App\Repositories\HospitalRepositoryInterface */
    protected $hospitalRepository;

    public function __construct(HospitalRepositoryInterface $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
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
        return $this->hospitalRepository->rules();
    }

    public function messages()
    {
        return $this->hospitalRepository->messages();
    }

}
