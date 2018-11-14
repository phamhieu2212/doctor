<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\AdminUserRepositoryInterface;

class AdminUserRequest extends BaseRequest
{

    /** @var \App\Repositories\AdminUserRepositoryInterface */
    protected $adminUserRepository;

    public function __construct(AdminUserRepositoryInterface $adminUserRepository)
    {
        $this->adminUserRepository = $adminUserRepository;
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
        if ($this->method() == 'PUT')
        {
            return [
                'username'=>'required|unique:admin_users,username,'.$this->route()->parameter('admin_user'),
                'name'=>'required',
                'password' => 'confirmed',
            ];


        }
        else
        {
            return [
                'username'=>'required|unique:admin_users',
                'name'=>'required',
                'password' => 'required|min:6|confirmed',
            ];
        }

    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên người dùng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 kí tự',
            'password.confirmed' => 'Mật khẩu không trùng khớp',
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.unique' => 'Tên đăng nhập này đã tồn tại! Vui lòng nhập tên khác',
        ];
    }

}
