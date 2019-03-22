<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Becon extends Model
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

    public $sortable = ['id', 'created_at'];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'premise_id',
        'location_id',
        'minor_id',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * getAllRecord
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllRecord()
    {

        $pageSize = \Config::get('constants.AdminPageSize');

        if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN') ) {

            $arrData = self::select('id', 'organization_id','premise_id','location_id', 'minor_id','status')->with(['getOrganizationName' => function($q1) {
                $q1->select('id', 'name', 'becon_major_id');
            },

            'getPremiseName' => function($q1) {
                $q1->select('id', 'name');
            },

            'getLocationName' => function($q1) {
                $q1->select('id', 'name');
            },


        ])
            ->sortable(['id' => 'desc'])
            ->paginate($pageSize);


        } else {

            $arrData = self::select('id', 'organization_id','premise_id','location_id', 'minor_id','status')->with(['getOrganizationName' => function($q1) {
                $q1->select('id', 'name', 'becon_major_id');
            },

            'getPremiseName' => function($q1) {
                $q1->select('id', 'name');
            },

            'getLocationName' => function($q1) {
                $q1->select('id', 'name');
            },


        ])
            ->where('organization_id', \Auth::user()->id)
            ->sortable(['id' => 'desc'])
            ->paginate($pageSize);


        }
        
        

        return ($arrData ? $arrData : []);
    }

    /**
     * storeRecord
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function storeRecord($arr)
    {
        
        $var = Self::create($arr);
        return ($var->id ?: false);
    }

    /**
     * updateRecord
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function updateRecord($id, $arrData)
    {
        $varID = Self::where('id', '=', (int) $id)->update($arrData);
        return ($varID ?: false);
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

    
    public function getOrganizationName(){
        return $this->belongsTo('App\Admin', 'organization_id');
    }

    
    public function getPremiseName(){
        return $this->belongsTo('App\Model\Admin\Premise', 'premise_id');
    }

    
    public function getLocationName(){
        return $this->belongsTo('App\Model\Admin\Location', 'location_id');
    }
}
