<?php namespace App\Models;



class AdminStatistic extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_statistics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_user_id',
        'conversation_id',
        'total',
        'price',
        'date',
        'time_call',
        'type',
        'is_patient_new',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\AdminStatisticPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\AdminStatisticObserver);
    }

    // Relations
    public function adminUser()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'admin_user_id', 'id');
    }

    public function conversation()
    {
        return $this->belongsTo(\App\Models\Conversation::class, 'conversation_id', 'id');
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
            'conversation_id' => $this->conversation_id,
            'total' => $this->total,
            'price' => $this->price,
            'date' => $this->date,
            'time_call' => $this->time_call,
            'type' => $this->type,
            'is_patient_new' => $this->is_patient_new,
        ];
    }

}
