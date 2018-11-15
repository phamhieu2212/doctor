<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\ClinicRepositoryInterface;

class ClinicRequest extends BaseRequest
{

    /** @var \App\Repositories\ClinicRepositoryInterface */
    protected $clinicRepository;

    public function __construct(ClinicRepositoryInterface $clinicRepository)
    {
        $this->clinicRepository = $clinicRepository;
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
        return $this->clinicRepository->rules();
    }

    public function messages()
    {
        return $this->clinicRepository->messages();
    }

}
