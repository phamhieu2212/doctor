<?php namespace App\Models;



use Illuminate\Support\Facades\DB;

class Clinic extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clinics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_user_id',
        'name',
        'price',
        'address',
        'status',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\ClinicPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\ClinicObserver);
    }

    // Relations
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
            'name' => $this->name,
            'price' => $this->price,
            'address' => $this->address,
            'status' => intval($this->status),
        ];
    }

    public function toAPIArrayListPlanDoctor($idDoctor,$startDateOfMonth,$endDateOfMonth)
    {

        $plans = Plan::where('admin_user_id',$idDoctor)->where('clinic_id',$this->id)->where('started_at','>=',$startDateOfMonth)
                ->where('ended_at','<=',$endDateOfMonth)->groupBy(DB::raw('Date(started_at)'))->get();
        foreach($plans as $key=>$plan)
        {
            $plans[$key] = $plan->toAPIArrayListHourPlan($this->id);
        }
        return [
            'clinic_id'=>$this->id,
            'clinic_name'=>$this->name,
            'clinic_price'=>$this->price,
            'plans'=>$plans
        ];

    }

}
