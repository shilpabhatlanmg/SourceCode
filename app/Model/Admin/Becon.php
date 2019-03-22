<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use DB;

class Becon extends Model {

    //use SoftDeletes;
    use Sortable;

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
    public $sortable = ['id', 'name', 'minor_id', 'status', 'created_at'];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'premise_id',
        'location_id',
        'name',
        'minor_id',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * @author Sandeep Kumar
     * @function: getAllRecord
     * @desc: get all record from database.
     */
    public static function getAllRecord() {


        $pageSize = \Config::get('constants.AdminPageSize');
        $strSearchTerm = !empty(request()->search) ? request()->search : '';
        $organisationId = !empty(request()->organization_id) ? \Crypt::decryptString(request()->organization_id) : '';

        if (!empty($strSearchTerm)) {

            $premiseId = \DB::table('premises')->select('id', 'name')->where('name', 'LIKE', "%$strSearchTerm%")->get();
            
            $id = [];
            if (@count($premiseId)) {
                foreach ($premiseId as $premise) {
                    $id[] = $premise->id;
                }
            }

            $locationId = \DB::table('locations')->select('id', 'name')->where('name', 'LIKE', "%$strSearchTerm%")->get();

            $location_id = [];
            if (@count($locationId)) {
                foreach ($locationId as $location) {
                    $location_id[] = $location->id;
                }
            }
        }

        if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN')) {
            

            $query = self::select('id', 'organization_id', 'premise_id', 'location_id', 'name', 'minor_id', 'status')->with(['getOrganizationName' => function($q1) {
                $q1->select('id', 'name', 'becon_major_id');
            },
            'getPremiseName' => function($q1) {
                $q1->select('id', 'name');
            },
            'getLocationName' => function($q1) {
                $q1->select('id', 'name');
            },
        ]);
            if (!empty($strSearchTerm)) {
                $query->orwhereIn('premise_id', $id);
                $query->orWhereIn('location_id', $location_id);
                $query->orWhere('name', 'like', '%' . $strSearchTerm . '%');
                
                
            }
            if(!empty($organisationId)){
                $query->where('organization_id', $organisationId);
            }
            $arrData = $query->sortable(['id' => 'desc'])->paginate($pageSize);

        } else {

            $query = self::select('id', 'organization_id', 'premise_id', 'location_id', 'name', 'minor_id', 'status')->with(['getOrganizationName' => function($q1) {
                $q1->select('id', 'name', 'becon_major_id');
            },
            'getPremiseName' => function($q1) {
                $q1->select('id', 'name');
            },
            'getLocationName' => function($q1) {
                $q1->select('id', 'name');
            },
        ]);

            if (!empty($strSearchTerm)) {
                $query->orwhereIn('premise_id', $id);
                $query->orWhereIn('location_id', $location_id);
                $query->orWhere('name', 'like', '%' . $strSearchTerm . '%');
            }

            if(!empty($organisationId)){
                $query->where('organization_id', $organisationId);
            }

            $arrData = $query->where('becons.organization_id', \Auth::user()->id)->sortable(['id' => 'desc'])->paginate($pageSize);
        }
        return ($arrData ? $arrData : []);
    }

    
    /**
     * @author Sandeep Kumar
     * @function: storeOrUpdateData
     * @param $input
     * @param $user_id
     * @desc: store or udpate record into database.
     */
    public static function storeOrUpdateData($input, $user_id = false) {

        $user = self::updateOrCreate(['id' => (int) $user_id], $input);
        return $user;
    }

    
    /**
     * @author Sandeep Kumar
     * @function: getRecordByID
     * @param $varID
     * @desc: get record by id.
     */
    public static function getRecordByID($varID) {
        $arrRecord = Self::select('*')
        ->where("id", "=", $varID)
        ->first();
        return ($arrRecord ? $arrRecord : []);
    }

    /**
     * @author Sandeep Kumar
     * @function: deleteRecord
     * @param $id
     * @desc: delete record.
     */
    public static function deleteRecord($id) {
        $varDeleteID = Self::where('id', '=', $id)->delete();
        return ($varDeleteID ?: false);
    }
    
    public function getOrganizationName() {
        return $this->belongsTo('App\Admin', 'organization_id');
    }

    public function getPremiseName() {
        return $this->belongsTo('App\Model\Admin\Premise', 'premise_id');
    }

    public function getLocationName() {
        return $this->belongsTo('App\Model\Admin\Location', 'location_id');
    }

}
