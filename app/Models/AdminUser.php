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
        'last_notification_id','quick_id','status'
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

    public function point()
    {
        return $this->hasOne('App\Models\PointDoctor','admin_user_id');
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
            'experience' => (int)$this->doctor->experience,
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
            'money' => $this->point['point'],
            'price_chat'=>$this->doctor->price_chat,
            'price_call'=>$this->doctor->price_call,
            'status' => 0,
            'profile_image_id' => $this->profile_image_id,
            'image_link' => (!empty($this->present()->profileImage()))?$this->present()->profileImage()->present()->url: \URLHelper::asset('img/no_image.jpg', 'common'),
        ];
    }

    public function toAPIArrayProfile()
    {
        $specialties = $this->specialties;
        foreach($specialties as $key=>$specialty)
        {
            $specialties[$key] = $specialty->toAPIArray();
        }
        $hospital = $this->doctor->hospital->toAPIArrayListDataForProfileDoctor();
        return [
            'name' => ($this->name != null)?$this->name:"",
            'position' => ($this->doctor->position != null)?$this->doctor->position:"",
            'hospital'=>$hospital,
            'birthday' => ($this->doctor->birthday != null)?$this->doctor->birthday:"",
            'gender' => $this->doctor->gender,
            'address' => ($this->doctor->address != null)?$this->doctor->address:"",
            'phone' => ($this->phone != null)?$this->phone:"",
            'sub_phone' => ($this->doctor->sub_phone != null)?$this->doctor->sub_phone:"",
            'email' => ($this->email != null)?$this->email:"",
            'specialties'=>$specialties,
            'levels' => $this->doctor->level->toAPIArray(),
            'experience' => ($this->doctor->experience != null)?(int)$this->doctor->experience:0,
            'bank_name'=>($this->doctor->bank_name != null)?$this->doctor->bank_name:"",
            'bank_address'=>($this->doctor->bank_address != null)?$this->doctor->bank_address:"",
            'bank_number'=>($this->doctor->bank_number != null)?$this->doctor->bank_number:"",
            'bank_owner'=>($this->doctor->bank_owner != null)?$this->doctor->bank_owner:"",
            'description'=>($this->doctor->description != null)?$this->doctor->description:"",
            'quick_username' => $this->username,
            'quick_password' => $this->username
        ];
    }

    public function toAPIArrayLoginDoctor()
    {
        if(!empty($this->doctor->level->name))
        {
            $level = $this->doctor->level->name;
        }
        else
        {
            $level = "";
        }
        $countRateChat = ChatHistory::where('admin_user_id',$this->id)->where('rate','>',0)->count();
        $countRateCall = CallHistory::where('admin_user_id',$this->id)->where('rate','>',0)->count();
        $rateChat = ChatHistory::where('admin_user_id',$this->id)->where('rate','>',0)->sum('rate');
        $rateCall = CallHistory::where('admin_user_id',$this->id)->where('rate','>',0)->sum('rate');

        return [
            'name' => $level.' '.$this->name,
            'hospital'=>$this->doctor->hospital->name,
            'position'=>$this->doctor->position,
            'price_chat'=>$this->doctor->price_chat,
            'price_call'=>$this->doctor->price_call,
            'vote' => $countRateCall + $countRateChat,
            'rate' => ($countRateChat + $countRateCall != 0)?(int)floor(($rateCall + $rateChat)/($countRateCall + $countRateChat)):0,
            'money' => $this->point['point'],
            'status' => $this->status,
            'profile_image_id' => $this->profile_image_id,
            'image_link' => (!empty($this->present()->profileImage()))?$this->present()->profileImage()->present()->url: \URLHelper::asset('img/no_image.jpg', 'common'),
        ];
    }

    public function toAPIArrayUploadAvatar()
    {
        return [
            'image_link' => (!empty($this->present()->profileImage()))?$this->present()->profileImage()->present()->url: \URLHelper::asset('img/no_image.jpg', 'common'),
        ];

    }
}
