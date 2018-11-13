<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\SpecialtyRepositoryInterface;

class SpecialtyRequest extends BaseRequest
{

    /** @var \App\Repositories\SpecialtyRepositoryInterface */
    protected $specialtyRepository;

    public function __construct(SpecialtyRepositoryInterface $specialtyRepository)
    {
        $this->specialtyRepository = $specialtyRepository;
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
        return $this->specialtyRepository->rules();
    }

    public function messages()
    {
        return $this->specialtyRepository->messages();
    }

}
