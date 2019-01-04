<?php namespace App\Models;



class Patient extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'patients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'birth_day',
        'gender',
        'identification',
        'country',
        'nation',
        'job',
        'phone_number',
        'email',
        'province',
        'district',
        'ward',
        'address',
        'name_of_relatives',
        'relationship',
        'phone_of_relatives',
        'profile_image_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\PatientPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\PatientObserver);
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function profileImage()
    {
        return $this->belongsTo(\App\Models\Image::class, 'profile_image_id', 'id');
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
            'full_name' => $this->full_name,
            'birth_day' => $this->birth_day,
            'gender' => $this->gender,
            'identification' => $this->identification,
            'country' => $this->country,
            'nation' => $this->nation,
            'job' => $this->job,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'province' => $this->province,
            'district' => $this->district,
            'ward' => $this->ward,
            'address' => $this->address,
            'name_of_relatives' => $this->name_of_relatives,
            'relationship' => $this->relationship,
            'phone_of_relatives' => $this->phone_of_relatives,
            'cover_image'    => !empty($this->profileImage) ? $this->profileImage->present()->url : null,
        ];
    }

}
