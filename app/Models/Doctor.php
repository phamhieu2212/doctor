<?php namespace App\Models;



use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Doctor extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'doctors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_user_id','name',
        'hospital_id',
        'gender',
        'telephone',
        'birthday',
        'address','price_chat','price_call',
        'city','sub_phone','bank_name','bank_address','bank_number','bank_owner',
        'position','level_id',
        'experience',
        'description',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\DoctorPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\DoctorObserver);
    }

    // Relations
    public function adminUser()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'admin_user_id', 'id');
    }

    public function hospital()
    {
        return $this->belongsTo(\App\Models\Hospital::class, 'hospital_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(\App\Models\Level::class, 'level_id', 'id');
    }

    

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'hospital_id' => $this->hospital_id,
            'hospital_name' => $this->hospital->name,
            'gender' => $this->gender,
            'phone' => $this->adminUser->phone,
            'birthday' => $this->birthday,
            'address' => $this->address,
            'city' => $this->city,
            'position' => $this->position,
            'experience' => $this->experience,
            'description' => $this->description,
        ];
    }

    public function toAPIArraySearch()
    {
        if(!empty($this->level->name))
        {
            $level = $this->level->name;
        }
        else
        {
            $level = "";
        }

        return [
            'id' => $this->adminUser['id'],
            'name' => ($this->name)?$level.' '.$this->name:"",
            'hospital_name' => $this->hospital['name'],
            'position' => ($this->position)?$this->position:"",
            'image_link' => ($this->adminUser->profile_image_id != 0)?$this->adminUser->present()->profileImage()->present()->url: \URLHelper::asset('img/no_image.jpg', 'common'),
        ];
    }

    public function toAPIArrayDetail()
    {
        $countRateChat = ChatHistory::where('admin_user_id',$this->adminUser->id)->where('rate','>',0)->count();
        $countRateCall = CallHistory::where('admin_user_id',$this->adminUser->id)->where('rate','>',0)->count();
        $rateChat = ChatHistory::where('admin_user_id',$this->adminUser->id)->where('rate','>=',0)->sum('rate');
        $rateCall = CallHistory::where('admin_user_id',$this->adminUser->id)->where('rate','>=',0)->sum('rate');
        $specialties = $this->adminUser->specialties;
        foreach($specialties as $key=>$specialty)
        {
            $specialties[$key] = $specialty->toAPIArray();
        }
        return [
            'id' => $this->adminUser->id,
            'idQuickBlox' => $this->adminUser->quick_id,
            'vote' => $countRateCall + $countRateChat,
            'rate' => ($countRateChat + $countRateCall != 0)?(int)floor(($rateCall + $rateChat)/($countRateCall + $countRateChat)):0,
            'specialties'=>$specialties,
            'gender' => $this->gender,
            'price_chat' => $this->price_chat,
            'price_call' => $this->price_call,
            'experience' => (int)$this->experience,
            'address'=>($this->address)?$this->address:"",
            'birthday'=>$this->birthday?$this->birthday:"",
            'position' => $this->position,
            'description' => $this->description,
            'status'=> $this->adminUser->status
        ];
    }

    public function getPlan($idDoctor)
    {
        $dateStart = date("Y-m-d 00:00:00", strtotime('monday this week'));
        $dateEnd = date("Y-m-d 23:59:59", strtotime('sunday this week'));

        $plans =  Plan::where('admin_user_id',$idDoctor)->where('started_at','>=',$dateStart)
            ->where('started_at','<=',$dateEnd)
            ->groupBy(DB::raw('Date(started_at)'))->get();
        foreach( $plans as $key => $plan ) {
            $plans[$key] = $plan->toAPIArraySearch();
        }
        return $plans;
    }

}
