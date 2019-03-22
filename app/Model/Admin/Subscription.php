<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Subscription extends Model
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

    public $sortable = ['id', 'plan_name', 'people_allow', 'premises_allow', 'duration', 'type', 'status', 'price'];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'plan_name',
        'people_allow',
        'premises_allow',
        'duration',
        'type',
        'price',
        'status',
    ];

    /**
     * getAllRecord
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllRecord($arr = array())
    {
        if(!empty($arr) && $arr['type']){

            if(!empty($arr['plan_id'])){

              $whereCondtion = array(
                  array('status', $arr['type']),
                  array('id', '!=', $arr['plan_id']),
              );

          }else {

              $whereCondtion = array(
                  array('status', $arr['type'])

              );

          }

          $pageSize = \Config::get('constants.AdminPageSize');
          $arrData = self::select('id', 'plan_name', 'people_allow', 'premises_allow', 'duration', 'type', 'price', 'status')
          ->where($whereCondtion)
          ->sortable(['id' => 'desc'])
          ->paginate($pageSize);

      }else {

        $pageSize = \Config::get('constants.AdminPageSize');
        $arrData = self::select('id', 'plan_name', 'people_allow', 'premises_allow', 'duration', 'type', 'price', 'status')
        ->sortable(['id' => 'desc'])
        ->paginate($pageSize);

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

        $user = self::updateOrCreate(['id' => (int) $user_id], $input);
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
     * organizationSubscription
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function organizationSubscriptions()
    {
        return $this->hasMany('App\Model\OrganizationSubscription', 'subscription_id');
        
    }
}
