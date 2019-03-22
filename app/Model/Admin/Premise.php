<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Premise extends Model
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

    public $sortable = ['id', 'name', 'organization_id', 'status', 'created_at'];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'organization_id',
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

        if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN') ) {


            $query = self::select('id', 'name','organization_id','status')->with(['getOrganizationName' => function($q1) {
                $q1->select('id', 'name');
            }]);

            if(!empty($organisationId)){
                $query->where('organization_id', $organisationId);
            }

            if(!empty($strSearchTerm)){
                $query->Where('name', 'like', '%' . $strSearchTerm . '%');
            }

            $arrData = $query->sortable(['id' => 'desc'])->paginate($pageSize);


        } else {

            $query = self::select('id', 'name','organization_id','status')->with(['getOrganizationName' => function($q1) {
                $q1->select('id', 'name', 'becon_major_id');
            }
        ]);

            if(!empty($strSearchTerm)){
                $query->Where('name', 'like', '%' . $strSearchTerm . '%');
            }

            $arrData = $query->where('premises.organization_id', \Auth::user()->id)->sortable(['id' => 'desc'])->paginate($pageSize);
            

        }
        
        return ($arrData ? $arrData : []);  

    }

    /**
     * getPremise
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getPremise($org_id = false)
    {

        $whereData = array(
            array('organization_id', '=', $org_id),
            array('status', 'Active')
        );

        $arr_record = self::select('id', 'name')

        ->Where($whereData)->get();

        //dd(\DB::getQueryLog());
        return ($arr_record ? $arr_record : []);
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
     * getAllPremiseByOrgId
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllPremiseByOrgId($varID)
    {
        if(!empty($varID) && isset($varID)){

            $where_condition = array('organization_id' => $varID, 'status' => 'Active');

        }else {
            $where_condition = array('organization_id' => \Auth::user()->id, 'status' => 'Active');            
        }
        

        $arrRecord = Self::select('id', 'name')
        ->where($where_condition)
        ->get();
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
     * getLocationnName
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function getLocationnName(){
        return $this->belongsTo('App\Model\Admin\Location', 'location_id');
    }

    /**
     * locations
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function locations()
    {
        return $this->hasMany('App\Model\Admin\Location', 'premise_id');
        
    }
}
