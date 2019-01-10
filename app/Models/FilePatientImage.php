<?php namespace App\Models;



class FilePatientImage extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'file_patient_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_patient_id',
        'image_id',
        'type',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\FilePatientImagePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\FilePatientImageObserver);
    }

    // Relations
    public function filePatient()
    {
        return $this->belongsTo(\App\Models\FilePatient::class, 'file_patient_id', 'id');
    }

    public function image()
    {
        return $this->belongsTo(\App\Models\Image::class, 'image_id', 'id');
    }

    

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'file_patient_id' => $this->file_patient_id,
            'image_id' => $this->image_id,
            'type' => $this->type,
        ];
    }

    public function toAPIArrayList()
    {
        return [
            'url' => $this->present()->image()->present()->url,
            'image_id' => $this->image_id,
            'type' => $this->type,
        ];
    }

}
