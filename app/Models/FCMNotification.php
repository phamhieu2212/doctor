<?php namespace App\Models;



class FCMNotification extends Base
{

    const PATIENT = 1;
    const DOCTOR = 2;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fcm_notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'content',
        'sent_at',
        'is_read','title'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['sent_at'];

    protected $presenter = \App\Presenters\FCMNotificationPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\FCMNotificationObserver);
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
            'title' => ($this->title != null)?$this->title:"",
            'content' => ($this->content != null)?$this->content:"",
            'sent_at' => ($this->sent_at != null)?date('Y-m-d H:i:s',strtotime($this->sent_at)):'',
            'is_read' => $this->is_read,
        ];
    }

}
