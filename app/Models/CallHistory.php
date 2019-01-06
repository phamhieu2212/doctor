<?php namespace App\Models;



use App\Http\Controllers\API\V1\QuickbloxController;

class CallHistory extends Base
{

    

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
        $userQuick = $this->quickBlox->getUser($this->adminUser->username);
        dd($userQuick);
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'admin_user_id' => $this->admin_user_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'type' => $this->type,
            'is_read' => $this->is_read,
        ];
    }

    public function toAPIArrayList()
    {
        return [
            'quick_id'=> $this->adminUser->quick_id,
            'doctor_id'=> $this->adminUser->id,
            'doctor_name' =>$this->adminUser->name,
            'start_time' => date('Y-m-d H:i:s',strtotime($this->start_time)),
            'end_time' => date('Y-m-d H:i:s',strtotime($this->end_time)),
            'type' => ($this->caller == 'patient')?3:$this->type,
            'avatar' => (!empty($this->adminUser->present()->profileImage()))?$this->adminUser->present()->profileImage()->present()->url: \URLHelper::asset('img/no_image.jpg', 'common'),
        ];


    }

}
