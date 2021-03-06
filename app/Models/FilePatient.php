<?php namespace App\Models;



class FilePatient extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'file_patients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'user_id',
        'started_at',
        'description','status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\FilePatientPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\FilePatientObserver);
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class,'file_patient_images');
    }


    

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'user_id' => $this->user_id,
            'started_at' => $this->started_at,
            'description' => $this->description,
        ];
    }

    public function toAPIArrayList()
    {
        $fileImages = FilePatientImage::where('file_patient_id',$this->id)->get();
        foreach($fileImages as $key=>$fileImage )
        {
            $fileImages[$key] = $fileImage->toAPIArrayList();
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'started_at' => ($this->started_at != "0000-00-00")?$this->started_at:"",
            'description' => $this->description,
            'images'=>$fileImages
        ];
    }

    public function toAPIArrayDetail()
    {
        $fileImages = FilePatientImage::where('file_patient_id',$this->id)->get();
        foreach($fileImages as $key=>$fileImage )
        {
            $fileImages[$key] = $fileImage->toAPIArrayList();
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'started_at' => $this->started_at,
            'description' => $this->description,
            'images'=>$fileImages
        ];
    }

}
