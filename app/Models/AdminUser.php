<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Laravel\Passport\HasApiTokens;

/**
 * App\Models\AdminUser.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $last_notification_id
 * @property string $api_access_token
 * @property int $profile_image_id
 * @property string $remember_token
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Image $profileImage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AdminUserRole[] $roles
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereLastNotificationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereApiAccessToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereProfileImageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property string $locale
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereLocale($value)
 */
class AdminUser extends AuthenticatableBase
{
    use SoftDeletes,HasApiTokens;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_users';

    protected $presenter = \App\Presenters\AdminUserPresenter::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','username','phone',
        'email',
        'password',
        'locale',
        'remember_token',
        'api_access_token',
        'profile_image_id',
        'last_notification_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'facebook_token'];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\AdminUserObserver);
    }

    // Relation

    public function profileImage()
    {
        return $this->belongsTo('App\Models\Image', 'profile_image_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany('App\Models\AdminUserRole', 'admin_user_id', 'id');
    }

    public function doctor()
    {
        return $this->hasOne('App\Models\Doctor','admin_user_id');
    }
    public function specialties()
    {
        return $this->belongsToMany(Specialty::class,'doctor_specialties');
    }

    // Utility Functions

    /**
     * @param string $targetRole
     * @param bool   $checkSubRoles
     *
     * @return bool
     */
    public function hasRole($targetRole, $checkSubRoles = true)
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role->role;
        }
        if (in_array($targetRole, $roles)) {
            return true;
        }
        if (!$checkSubRoles) {
            return false;
        }
        $roleConfigs = config('admin_user.roles', []);
        foreach ($roles as $role) {
            $subRoles = array_get($roleConfigs, "$role.sub_roles", []);
            if (in_array($targetRole, $subRoles)) {
                return true;
            }
        }

        return false;
    }
    public function toAPIArray()
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'email' => $this->email,
            'hospital_id' => $this->doctor->hospital_id,
            'hospital_name' => $this->doctor->hospital->name,
            'address' => $this->doctor->address,
            'gender' => ($this->doctor->gender == 1)?'Nam':'Nữ',
            'birthday' => $this->doctor->birthday,
            'city' => $this->doctor->city,
            'position' => $this->doctor->position,
            'experience' => $this->doctor->experience,
            'description' => $this->doctor->description,
            'profile_image_id' => $this->profile_image_id,
            'image_link' => (!empty($this->present()->profileImage()))?$this->present()->profileImage()->present()->url: \URLHelper::asset('img/no_image.jpg', 'common'),
        ];
    }

    public function toAPIArrayLogin()
    {
        return [
            'name' => $this->name,
            'vote' => 4,
            'rate' => 100,
            'money' => 1000000,
            'status' => 0,
            'profile_image_id' => $this->profile_image_id,
            'image_link' => (!empty($this->present()->profileImage()))?$this->present()->profileImage()->present()->url: \URLHelper::asset('img/no_image.jpg', 'common'),
        ];
    }

    public function toAPIArrayLoginDoctor()
    {
        return [
            'name' => $this->name,
            'hospital'=>$this->doctor->hospital->name,
            'place'=>'Khoa chấn thương chỉnh hình',
            'price_chat'=>300000,
            'price_call'=>300000,
            'vote' => 4,
            'rate' => 100,
            'money' => 1000000,
            'status' => 0,
            'profile_image_id' => $this->profile_image_id,
            'image_link' => (!empty($this->present()->profileImage()))?$this->present()->profileImage()->present()->url: \URLHelper::asset('img/no_image.jpg', 'common'),
        ];
    }
}
