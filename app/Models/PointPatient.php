<?php namespace App\Models;



class PointPatient extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'point_patients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'point',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\PointPatientPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\PointPatientObserver);
    }

    // Relations
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
            'user_id' => $this->user_id,
            'point' => $this->point,
        ];
    }

}
