<?php namespace App\Models;



class Level extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'levels';

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

    protected $presenter = \App\Presenters\LevelPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\LevelObserver);
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

    public function toAPIArrayListDataForProfileDoctor()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

}
