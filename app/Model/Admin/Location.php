<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Location extends Model
{
    use SoftDeletes;
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

    public $sortable = ['id', 'name', 'status', 'created_at'];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'organization_id',
        'premise_id',
        'created_by',
        'updated_by',
        'status',
    ];

    /**
     * getAllRecord
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllRecord() {
        $pageSize = \Config::get('constants.AdminPageSize');
        
        $organisationId = !empty(request()->organization_id) ? \Crypt::decryptString(request()->organization_id) : '';
        $strSearchTerm = !empty(request()->search) ? request()->search : '';

        $premiseId = \DB::table('premises')->select('id', 'name')->where('name', 'LIKE', "%$strSearchTerm%")->get();

        $id = [];
        if (@count($premiseId)) {
            foreach ($premiseId as $premise) {
                $id[] = $premise->id;
            }
        }


        if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN') ) {

            $query = self::select('id', 'name','organization_id', 'premise_id', 'status')->with(['getOrganizationName' => function($q1) {
                $q1->select('id', 'name');
            },

            'getPremiseName' => function($q1) {
                $q1->select('id', 'name');
            }
        ]);

            if(!empty($organisationId)){
                $query->where('organization_id', $organisationId);
            }

            if(!empty($strSearchTerm)){
                $query->Where('name', 'like', '%' . $strSearchTerm . '%');
                $query->orwhereIn('premise_id', $id);
            }

            $arrData = $query->sortable(['id' => 'desc'])->paginate($pageSize);


        } else {

            $query = self::select('id', 'name','organization_id', 'premise_id', 'status')->with(['getOrganizationName' => function($q1) {
                $q1->select('id', 'name');
            }, 


            'getPremiseName' => function($q1) {
                $q1->select('id', 'name');
            }]);

            if(!empty($strSearchTerm)){
                $query->Where('name', 'like', '%' . $strSearchTerm . '%');
                $query->orwhereIn('premise_id', $id);
            }

            $arrData = $query->where('locations.organization_id', \Auth::user()->id)->sortable(['id' => 'desc'])->paginate($pageSize);
            

        }
        
        return ($arrData ? $arrData : []);  

    }

    
    /**
     * storeOrUpdateData
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function storeOrUpdateData($input, $user_id = false) {

        $user = Self::updateOrCreate(['id' => (int) $user_id], $input);
        return $user;
    }

    
    /**
     * getRecordByID
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getRecordByID($varID)
    {
        $arrRecord = Self::select('*')
        ->where("id", "=", $varID)
        ->first();
        return ($arrRecord ? $arrRecord : []);
    }

    /**
     * deleteRecord
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function deleteRecord($id)
    {
        $varDeleteID = Self::where('id', '=', $id)->delete();
        return ($varDeleteID ?: false);
    }

    /**
     * getAllLocationByPremiseId
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllLocationByPremiseId($varID)
    {
        $where_condition = array('premise_id' => $varID, 'status' => 'Active');

        $arrRecord = Self::select('id', 'name')
        ->where($where_condition)
        ->get();
        return ($arrRecord ? $arrRecord : []);
    }

    /**
     * getLocationsCountByOrganizationId
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getLocationsCountByOrganizationId($orgId)
    {
        $where_condition = array('organization_id' => $orgId, 'status' => 'Active');

        $arrRecord = Self::select('id')
        ->where($where_condition)
        ->get()->count();
        return ($arrRecord ? $arrRecord : 0);
    }

    /**
     * getOrganizationName
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function getOrganizationName(){
        return $this->belongsTo('App\Admin', 'organization_id');
    }

    /**
     * getPremiseName
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function getPremiseName(){
        return $this->belongsTo('App\Model\Admin\Premise', 'premise_id');
    }

    /**
     * becons
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function becons()
    {
        return $this->hasMany('App\Model\Admin\Becon', 'location_id');
        
    }
}
