<?php namespace App\Models;



use App\Http\Controllers\API\V1\QuickbloxController;

class CallHistory extends Base
{
    const DOCTOR = 'doctor';
    const PATIENT = 'patient';
    const CANCEL = 1;
    const MISS = 2;
    const FINISHED = 3;
    const NOT_YET_READ = 0;
    const IS_READ = 1;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'call_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'admin_user_id',
        'start_time',
        'end_time',
        'type',
        'is_read',
        'caller','rate','content','rate_time'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['start_time','end_time'];

    protected $presenter = \App\Presenters\CallHistoryPresenter::class;


    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\CallHistoryObserver);
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function adminUser()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'admin_user_id', 'id');
    }

    

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        // $userQuick = $this->quickBlox->getUser($this->adminUser->username);
        // dd($userQuick);
        return [
            'id' => $this->id,
            'caller' => $this->caller,
            'user_id' => $this->user_id,
            'admin_user_id' => $this->admin_user_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'type' => $this->type,
            'is_read' => $this->is_read,
        ];
    }

    public function toAPIArrayListForPatient()
    {
        if(!empty($this->adminUser->doctor->level->name))
        {
            $level = $this->adminUser->doctor->level->name;
        }
        else
        {
            $level = "";
        }
        return [
            'quick_id'=> $this->adminUser->quick_id,
            'doctor_id'=> $this->adminUser->id,
            'doctor_name' =>$level.' '.$this->adminUser->name,
            'start_time' => ($this->start_time == null)?date('Y-m-d H:i:s',strtotime($this->created_at)):date('Y-m-d H:i:s',strtotime($this->start_time)),
            'end_time' => ($this->end_time == null)?date('Y-m-d H:i:s',strtotime($this->created_at)):date('Y-m-d H:i:s',strtotime($this->end_time)),
            'type' => ($this->caller == 'patient')?3:$this->type,
            'avatar' => (!empty($this->adminUser->present()->profileImage()))?$this->adminUser->present()->profileImage()->present()->url: \URLHelper::asset('img/no_image.jpg', 'common'),
        ];


    }

    public function toAPIArrayListForDoctor()
    {
        return [
            'quick_id'=> $this->user->quick_id,
            'patient_id'=> $this->user->id,
            'patient_name' =>$this->user->name,
            'start_time' => ($this->start_time == null)?date('Y-m-d H:i:s',strtotime($this->created_at)):date('Y-m-d H:i:s',strtotime($this->start_time)),
            'end_time' => ($this->end_time == null)?date('Y-m-d H:i:s',strtotime($this->created_at)):date('Y-m-d H:i:s',strtotime($this->end_time)),
            'type' => ($this->caller == 'doctor')?3:$this->type,
            'avatar' => (!empty($this->user->present()->profileImage()))?$this->user->present()->profileImage()->present()->url: \URLHelper::asset('img/user_avatar.png', 'common'),
        ];


    }

    public function toAPIArrayDetailPatient()
    {
        return [
            'label' => 'call',
            'duration'=> (int)date('s',$this->end_time->timestamp - $this->start_time->timestamp),
            'start_time'=>date('Y-m-d H:i:s',strtotime($this->created_at)),

        ];
    }

    public function toAPIArrayDetailDoctor()
    {
        return [
            'id'=>$this->id,
            'doctor_name'=>$this->adminUser->name,
            'level_name'=>$this->adminUser->doctor->level->name,
            'label' => 'call',
            'duration'=> (int)date('s',$this->end_time->timestamp - $this->start_time->timestamp),
            'start_time'=>date('Y-m-d H:i:s',strtotime($this->created_at)),
            'rate'=>($this->rate)?(int)$this->rate:0,
            'rate_content'=>$this->content

        ];
    }

    public function toAPIArrayRate()
    {
        return [
            'rate'=>(int)$this->rate,
            'rate_content'=>$this->content


        ];
    }

    public function toAPIArrayListRateForDoctor()
    {
        return [
            'patient_name'=>$this->user->name,
            'rate_time'=>($this->rate_time != null)?date('Y-m-d H:i:s',strtotime($this->rate_time)):"",
            'rate'=>($this->rate)?(int)$this->rate:0,
            'rate_content'=>$this->content

        ];
    }

}
