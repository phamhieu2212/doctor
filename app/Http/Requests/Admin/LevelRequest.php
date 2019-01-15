<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\LevelRepositoryInterface;

class LevelRequest extends BaseRequest
{

    /** @var \App\Repositories\LevelRepositoryInterface */
    protected $levelRepository;

    public function __construct(LevelRepositoryInterface $levelRepository)
    {
        $this->levelRepository = $levelRepository;
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
        return $this->levelRepository->rules();
    }

    public function messages()
    {
        return $this->levelRepository->messages();
    }

}
