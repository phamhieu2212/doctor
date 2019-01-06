<?php namespace App\Models;



class DoctorSpecialty extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'doctor_specialties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_user_id',
        'specialty_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\DoctorSpecialtyPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\DoctorSpecialtyObserver);
    }

    // Relations
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
        return [
            'id' => $this->id,
            'admin_user_id' => $this->admin_user_id,
            'specialty_id' => $this->specialty_id,
        ];
    }

}
