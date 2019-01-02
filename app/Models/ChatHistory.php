<?php namespace App\Models;



class ChatHistory extends Base
{

    const NEW = 1;
    const CONTINUE = 2;
    const FINISHED = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'chat_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'admin_user_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\ChatHistoryPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\ChatHistoryObserver);
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

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
            'user_id' => $this->user_id,
            'admin_user_id' => $this->admin_user_id,
        ];
    }

}
