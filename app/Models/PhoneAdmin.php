<?php namespace App\Models;



class PhoneAdmin extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'phone_admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\PhoneAdminPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\PhoneAdminObserver);
    }

    // Relations
    

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
        ];
    }

}
