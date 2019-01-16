<?php namespace App\Repositories\Eloquent;

use App\Models\ChatHistory;
use App\Models\PointDoctor;
use \App\Repositories\PointPatientRepositoryInterface;
use \App\Models\PointPatient;
use Carbon\Carbon;
use DB;

class PointPatientRepository extends SingleKeyModelRepository implements PointPatientRepositoryInterface
{

    public function getBlankModel()
    {
        return new PointPatient();
    }

    public function prepareForStart($adminUserId,$currentPatient, $doctor)
    {
        $checkIsNew = ChatHistory::where('admin_user_id',$adminUserId)
                        ->where('user_id',$currentPatient->id)->count();
        if($checkIsNew == 0)
        {
            $isNew = 1;
        }
        else
        {
            $isNew = 0;
        }

        $now = Carbon::now();
        try {

            DB::beginTransaction();

            $currentPatientPoint = $currentPatient->patientPoint;
            $pointDoctor = PointDoctor::where('admin_user_id',$adminUserId)->first();
            $usePoint = $doctor->price_chat;
            $currentPatientPoint->point = $currentPatientPoint->point - $usePoint;
            $currentPatientPoint->save();
            $pointDoctor->point = $pointDoctor->point + $usePoint;
            $pointDoctor->save();
            $chatId = DB::table('chat_histories')->insertGetId(["user_id" => $currentPatient->id, "admin_user_id" => $doctor->admin_user_id,"created_at"=>$now]);

            DB::table('admin_statistics')->insert(
                [
                    'admin_user_id' => $adminUserId,
                    'conversation_id' => $chatId,
                    'total'=>$usePoint,
                    'price'=>$usePoint,
                    'date' => date('Y-m-d',strtotime($now)),
                    'time_call'=>0,
                    'type'=>1,
                    'is_patient_new'=>$isNew
                ]
            );

            DB::commit();


            return [
                'chat_id'=>$chatId,
                'status'=>true
            ];

        } catch (\Exception $ex) {
            DB::rollBack();

            return [
                'chat'=>'',
                'status'=>false
            ];
        }
    }
}
