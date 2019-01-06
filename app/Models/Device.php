<?php namespace App\Models;



class Device extends Base
{

    const PATIENT = 1;
    const DOCTOR = 2;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id',
        'type',
        'user_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\DevicePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\DeviceObserver);
    }

    // Relations
    public function device()
    {
        return $this->belongsTo(\App\Models\Device::class, 'device_id', 'id');
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
            'device_id' => $this->device_id,
            'type' => $this->type,
            'user_id' => $this->user_id,
        ];
    }

}
