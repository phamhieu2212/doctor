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

    public function point()
    {
        return $this->hasOne(\App\Models\PointPatient::class, 'user_id', 'user_id');
    }

    private function getProvince($id)
    {
        $public_path = public_path();
        $file = $public_path .'/static/location.json';
        $masterData = json_decode(file_get_contents($file), true);
        foreach($masterData as $p) {
            if ($p["id"] == $id) {
                unset($p["districts"]);
    
                return $p;
            }
        }

        return null;
    }

    private function getDistrict($id)
    {
        $public_path = public_path();
        $file = $public_path .'/static/location.json';
        $masterData = json_decode(file_get_contents($file), true);
        foreach($masterData as $p) {
            foreach($p['districts'] as $d) {
                if ($d["id"] == $id) {
                    unset($d["wards"]);
                    
                    return $d;
                }
            }
        }

        return null;
    }

    private function getWard($id)
    {
        $public_path = public_path();
        $file = $public_path .'/static/location.json';
        $masterData = json_decode(file_get_contents($file), true);
        foreach($masterData as $p) {
            foreach($p["districts"] as $d) {
                foreach($d["wards"] as $w) {
                    if ($w["id"] == $id) {
                        return $w;
                    }
                }
            }
        }

        return null;
    }

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'birth_day' => $this->birth_day,
            'gender' => $this->gender,
            'identification' => $this->identification,
            'country' => $this->country,
            'nation' => $this->nation,
            'job' => $this->job,
            'phone_number' => $this->user->telephone,
            'email' => $this->email,
            'province' => $this->getProvince($this->province),
            'district' => $this->getDistrict($this->district),
            'ward' => $this->getWard($this->ward),
            'address' => $this->address,
            'name_of_relatives' => $this->name_of_relatives,
            'relationship' => $this->relationship,
            'phone_of_relatives' => $this->phone_of_relatives,
            'point' => $this->point->point,
        ];
    }

    public function imageToAPIArray()
    {
        return [
            'url'    => !empty($this->profileImage) ? $this->profileImage->present()->url : null,
        ];
    }

}
