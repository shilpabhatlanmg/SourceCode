<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class AidRequest extends Model
{
    use Sortable;

    public $sortable = ['id', 'user_id', 'incident_type_id', 'minor_id', 'status', 'created_at'];

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * Maintain created_at and updated_at automatically
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * getAidRequest
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAidRequest()
    {

        $pageSize = \Config::get('constants.AdminPageSize');
        $organisationId = !empty(request()->organization_id) ? \Crypt::decryptString(request()->organization_id) : '';
        $incidentTypeId = !empty(request()->incident_type_id) ? \Crypt::decryptString(request()->incident_type_id) : '';
        $strSearchTerm = !empty(request()->search) ? request()->search : '';

        if (!empty($strSearchTerm)) {

            $userRecord = \DB::table('users')->select('id')->where('contact_number', 'LIKE', "%$strSearchTerm%")->get();

            $userId = [];

            if (@count($userRecord)) {
                foreach ($userRecord as $user) {
                    $userId[] = $user->id;
                }
            }
        }

        $convert = \DB::raw("cast(created_at as date)");
        $from = date('Y-m-d', strtotime(request()->from_date));
        $to = !empty(request()->to_date) ? date('Y-m-d', strtotime(request()->to_date)) : date('Y-m-d') ;
      
//  \DB::enableQueryLog();
        if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN') ) {

         $query = AidRequest::with([
           'getBeaconDetail',
           'getBeaconDetail.getPremiseName' => function($q){
             return $q->select('id', 'name');
           },
           'getBeaconDetail.getLocationName' => function($q) {
             return $q->select('id', 'name');
           },
           'getBeaconDetail.getOrganizationName' => function($q) {
             return $q->select('id', 'name', 'timezone');
           },
           'getIncidentDetail',
           'getUserDetail'
         ]);

         if (!empty($strSearchTerm)) {

            $query->whereHas('getBeaconDetail.getLocationName', function($q2) use($strSearchTerm) {
                $q2->where('name', 'LIKE', "%$strSearchTerm%");
            })->orWhereHas('getBeaconDetail.getPremiseName', function($q2) use($strSearchTerm) {
                $q2->where('name', 'LIKE', "%$strSearchTerm%");
            });


            if(!empty($userId)){
                $query->orWhereIn('user_id', $userId);
            }
        }

        if(!empty($organisationId)){

            $query->whereHas('getBeaconDetail', function($q1) use($organisationId) {
                $q1->where('organization_id', $organisationId);

            });

        }


        if(!empty($incidentTypeId)){

            $query->where('incident_type_id', $incidentTypeId);

        }

        if(!empty(request()->from_date)){
            $query->whereBetween($convert, [$from, $to]);

        }

        $arr_record = $query->sortable(['created_at' => 'desc'])->paginate($pageSize);

      //echo '<pre>'; print_r($arr_record->toArray());die;
      //echo '<pre>'; print_r(\DB::getQueryLog());die;

    } else {
        

      //  \DB::enableQueryLog();

        $query = AidRequest::with([
          'getBeaconDetail',
          'getBeaconDetail.getPremiseName' => function($q){
            return $q->select('id', 'name');
          },
          'getBeaconDetail.getLocationName' => function($q) {
            return $q->select('id', 'name');
          },
          'getBeaconDetail.getOrganizationName' => function($q) {
            return $q->select('id', 'name');
          },
          'getIncidentDetail',
          'getUserDetail'
          ])->where('organization_id', \Auth::user()->id);

        if (!empty($strSearchTerm)) {

          $query->whereHas('getBeaconDetail.getLocationName', function($q2) use($strSearchTerm) {
              $q2->where('name', 'LIKE', "%$strSearchTerm%");
          })->orWhereHas('getBeaconDetail.getPremiseName', function($q2) use($strSearchTerm) {
              $q2->where('name', 'LIKE', "%$strSearchTerm%");
          });

            if(!empty($userId)){

                $query->whereIn('user_id', $userId);
            }

        }


        if(!empty($incidentTypeId)){

            $query->where('incident_type_id', $incidentTypeId);

        }

        if(!empty(request()->from_date) && !empty(request()->to_date)){
            $query->whereBetween($convert, [$from, $to]);

        }

        $arr_record = $query->sortable(['created_at' => 'desc'])->paginate($pageSize);

        //dd(\DB::getQueryLog());
    }


    return ($arr_record ? $arr_record : []);


}

    /**
     * getBeaconDetail
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    // public function getBeaconDetail(){
    //     return $this->belongsTo('App\Model\Admin\BeconManagement', 'becon_major_id', 'organizations_becon_major_id')
    //     ->where('minor_id', '=', 'aid_requests.minor_id');
    // }

    public function getBeaconDetail(){
        return $this->belongsTo('App\Model\Admin\Becon', 'becon_id', 'id');
    }

    /**
     * getUserDetail
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function getUserDetail(){
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * getIncidentDetail
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function getIncidentDetail(){
        return $this->belongsTo('App\Model\Admin\Incident\IncidentType', 'incident_type_id');
    }

}
