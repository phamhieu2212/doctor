<?php namespace App\Models;



class Plan extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_user_id',
        'user_id','clinic_id',
        'price',
        'status',
        'started_at',
        'ended_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['started_at','ended_at'];

    protected $presenter = \App\Presenters\PlanPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\PlanObserver);
    }

    // Relations
    public function adminUser()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'admin_user_id', 'id');
    }

    public function clinic()
    {
        return $this->belongsTo(\App\Models\Clinic::class, 'clinic_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'admin_user_id' => $this->admin_user_id,
            'user_id' => $this->user_id,
            'patient_name' => ($this->user_id != 0)?$this->user->name:'',
            'patient_address' => ($this->user_id != 0)?$this->user->address:'',
            'clinic_id' => $this->clinic_id,
            'clinic_name' => $this->clinic->name,
            'price' => $this->price,
            'status' => $this->status,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'day'      =>  date("w", strtotime($this->started_at)),
            'startHour' => date("H:i", strtotime($this->started_at)),
            'endHour' => date("H:i", strtotime($this->ended_at))
        ];
    }

    public function toAPIArraySearch()
    {
        return [
            intval(date("w", strtotime($this->started_at))),
        ];
    }

}
