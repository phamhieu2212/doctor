<?php namespace App\Models;



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
        'admin_user_id',
        'hospital_id',
        'gender',
        'telephone',
        'birthday',
        'address',
        'city',
        'position',
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

    

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'admin_user_id' => $this->admin_user_id,
            'hospital_id' => $this->hospital_id,
            'gender' => $this->gender,
            'telephone' => $this->telephone,
            'birthday' => $this->birthday,
            'address' => $this->address,
            'city' => $this->city,
            'position' => $this->position,
            'experience' => $this->experience,
            'description' => $this->description,
        ];
    }

}
