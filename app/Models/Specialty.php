<?php namespace App\Models;



class Specialty extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'specialties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\SpecialtyPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\SpecialtyObserver);
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
            'name' => $this->name,
        ];
    }

}
