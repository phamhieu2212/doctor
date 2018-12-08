<?php namespace App\Models;



class Clinic extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clinics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_user_id',
        'name',
        'price',
        'address',
        'status',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\ClinicPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\ClinicObserver);
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
            'name' => $this->name,
            'price'=>$this->price,
            'address' => $this->address,
            'status' => $this->status,
        ];
    }

}
