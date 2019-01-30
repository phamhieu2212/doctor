<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\PointPatientRepositoryInterface;

class PointPatientRequest extends BaseRequest
{

    /** @var \App\Repositories\PointPatientRepositoryInterface */
    protected $pointPatientRepository;

    public function __construct(PointPatientRepositoryInterface $pointPatientRepository)
    {
        $this->pointPatientRepository = $pointPatientRepository;
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
        return $this->pointPatientRepository->rules();
    }

    public function messages()
    {
        return $this->pointPatientRepository->messages();
    }

}
