<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class VisitorLog extends Model
{
	use Sortable;

	public $sortable = ['id', 'location_id', 'created_at'];

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
     * @var array
     */
    protected $fillable = [
    	'location_id',
    	'user_id',
    	'status'
    ];

    
    /**
     * getVisiorLog
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getVisiorLog()
    {
    	$pageSize = \Config::get('constants.AdminPageSize');
    	$organisationId = !empty(request()->organization_id) ? \Crypt::decryptString(request()->organization_id) : '';
        $strSearchTerm = !empty(request()->search) ? request()->search : '';

        if (!empty($strSearchTerm)) {

            $userRecord = \DB::table('users')->select('id')->where('contact_number', 'LIKE', "%$strSearchTerm%")->get();

            $userId = [];
            if (@count($userRecord)) {
                foreach ($userRecord as $user) {
                    $userId[] = $user->id;
                }
            }

            $premiseRecord = \DB::table('premises')->select('id')->where('name', 'LIKE', "%$strSearchTerm%")->get();

            $premiseId = [];
            
            if (@count($premiseRecord)) {
                foreach ($premiseRecord as $premise) {
                    $premiseId[] = $premise->id;
                }
            }

            $locationRecord = \DB::table('locations')->select('id')->where('name', 'LIKE', "%$strSearchTerm%")->get();

            $locationId = [];
            
            if (@count($locationRecord)) {
                foreach ($locationRecord as $location) {
                    $locationId[] = $location->id;
                }
            }
        }

        $convert = \DB::raw("cast(created_at as date)");
        $from = date('Y-m-d', strtotime(request()->from_date));
        $to = date('Y-m-d', strtotime(request()->to_date));

        if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN') ) {

            $query = VisitorLog::with(['getLocationDetail.getPremiseName' => function($q1) {
                $q1->select('id', 'name');
            },

            'getLocationDetail.getOrganizationName' => function($q1) {
                $q1->select('id', 'name');
            },
            'getUserDetail'

        ]);

            if (!empty($strSearchTerm)) {


                $query->whereHas('getLocationDetail', function($q1) use($premiseId, $locationId) {

                    if(!empty($premiseId) || !empty($locationId)){
                        $q1->whereIn('premise_id', $premiseId);    
                        $q1->orWhereIn('location_id', $locationId);
                    }
                    

                });

                if(!empty($userId)){
                    $query->WhereIn('user_id', $userId);
                }
            }

            if(!empty($organisationId)){

                $query->whereHas('getLocationDetail', function($q1) use($organisationId) {
                    $q1->where('organization_id', $organisationId);

                });

            }

            if(!empty(request()->from_date) && !empty(request()->to_date)){
                $query->whereBetween($convert, [$from, $to]);

            }
            $arr_record = $query->sortable()->paginate($pageSize);


        }else {


            $query = VisitorLog::with(['getLocationDetail.getPremiseName' => function($q1) {
                $q1->select('id', 'name');
            },

            'getLocationDetail.getOrganizationName' => function($q1) {
                $q1->select('id', 'name');
            },
            'getUserDetail'

        ])->whereHas('getLocationDetail', function($q1) {
            $q1->Where('organization_id', \Auth::user()->id);

        });

        if (!empty($strSearchTerm)) {


            $query->whereHas('getLocationDetail', function($q1) use($premiseId, $locationId) {

                if(!empty($premiseId) || !empty($locationId)){
                    $q1->whereIn('premise_id', $premiseId);    
                    $q1->orWhereIn('location_id', $locationId);
                }


            });

            if(!empty($userId)){
                $query->WhereIn('user_id', $userId);
            }
        }

        if(!empty(request()->from_date) && !empty(request()->to_date)){
            $query->whereBetween($convert, [$from, $to]);

        }
        $arr_record = $query->sortable()->paginate($pageSize);

    }

    return ($arr_record ? $arr_record : []);
}

    /**
     * getLocationDetail
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function getLocationDetail(){
        return $this->belongsTo('App\Model\Admin\Location', 'location_id');
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
}
