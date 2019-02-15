<?php namespace App\Models;



class ChatHistory extends Base
{

    const NEWCHAT = 1;
    const CONTINUECHAT = 2;
    const FINISHEDCHAT = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'chat_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'admin_user_id','file_patient_id','rate','content','rate_time'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\ChatHistoryPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\ChatHistoryObserver);
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

    public function filePatient()
    {
        return $this->belongsTo(\App\Models\FilePatient::class, 'file_patient_id', 'id');
    }

    

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'admin_user_id' => $this->admin_user_id,
        ];
    }

    public function toAPIArrayDetailPatient()
    {
        $timeNow = strtotime(date('Y-m-d H:i:s'));
        return [
            'label' => 'chat',
            'file_patient_id'=> ($this->filePatient['id'] != null)?$this->filePatient['id']:0,
            'duration'=> (($timeNow - $this->created_at->timestamp) <= 180)?$timeNow-$this->created_at->timestamp:0,
            'start_time'=>(($timeNow - $this->created_at->timestamp) > 180)?date('Y-m-d H:i:s',strtotime($this->created_at)):"",
            'end_time'=>(($timeNow - $this->created_at->timestamp) > 180)?date('Y-m-d H:i:s',strtotime($this->created_at. ' + 3 days')):"",


        ];
    }

    public function toAPIArrayDetailDoctor()
    {
        if(!empty($this->adminUser->doctor->level->name))
        {
            $level = $this->adminUser->doctor->level->name;
        }
        else
        {
            $level = "";
        }
        $timeNow = strtotime(date('Y-m-d H:i:s'));
        return [
            'id'=>$this->id,
            'doctor_name'=>$level.' '.$this->adminUser->name,
            'level_name'=>($this->adminUser->doctor->level_id != 0)?$this->adminUser->doctor->level->name:"",
            'label' => 'chat',
            'file_patient_id'=> ($this->filePatient['id'] != null)?$this->filePatient['id']:0,
            'duration'=> (($timeNow - $this->created_at->timestamp) <= 180)?$timeNow-$this->created_at->timestamp:0,
            'start_time'=>(($timeNow - $this->created_at->timestamp) > 180)?date('Y-m-d H:i:s',strtotime($this->created_at)):"",
            'end_time'=>(($timeNow - $this->created_at->timestamp) > 180)?date('Y-m-d H:i:s',strtotime($this->created_at. ' + 3 days')):"",
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
