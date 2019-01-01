<?php namespace App\Repositories\Eloquent;

use \App\Repositories\PointPatientRepositoryInterface;
use \App\Models\PointPatient;
use DB;

class PointPatientRepository extends SingleKeyModelRepository implements PointPatientRepositoryInterface
{

    public function getBlankModel()
    {
        return new PointPatient();
    }

    public function prepareForStart($currentPatient, $doctor)
    {
        try {
            DB::beginTransaction();

            $currentPatientPoint = $currentPatient->patientPoint;
            $usePoint = $doctor->price_chat;
        
            DB::table('point_patients')->update(["point" => $currentPatientPoint->point - $usePoint]);
            DB::table('chat_histories')->insert(["user_id" => $currentPatient->id, "admin_user_id" => $doctor->admin_user_id]);

            DB::commit();

            return true;

        } catch (\Exception $ex) {
            DB::rollBack();

            return false;
        }
    }
}
