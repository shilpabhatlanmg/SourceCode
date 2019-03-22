<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;
use Laravel\Cashier\Billable;

class OrganizationSubscription extends Model
{
	use Sortable;
    use Billable;

    public $sortable = ['id', 'organization_id', 'subscription_id', 'payment_id', 'from_date', 'expiry_date', 'status', 'created_at'];

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
    protected $fillable = ['id', 'organization_id', 'subscription_id', 'payment_id', 'status', 'from_date', 'expiry_date'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * getAllRecord
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getPlanRecord($planType) {

    	$pageSize = \Config::get('constants.AdminPageSize');

    	if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN') ) {

    		$query = Self::select('id', 'organization_id','subscription_id', 'payment_id', 'from_date', 'expiry_date', 'created_at', 'status')->with(['getSubscriptionDetail' => function($q1) {
    			$q1->select('id', 'plan_name', 'people_allow', 'premises_allow', 'duration', 'type', 'price', 'status', 'created_at');
    		}
    	]);

    		$arrData = $query->sortable(['id' => 'desc'])->paginate($pageSize);


    	} else {

            $convert = \DB::raw("cast(from_date as date)");
            $t2 = Carbon::parse(date('Y-m-d'));

            $query = Self::select('id', 'organization_id','subscription_id', 'payment_id', 'from_date', 'expiry_date', 'created_at', 'status')->with(['getSubscriptionDetail' => function($q1) {
               $q1->select('id', 'plan_name', 'people_allow', 'premises_allow', 'duration', 'type', 'price', 'status', 'created_at');
           }
       ]);

            if($planType == 'current'){

                $where_condition = array(
                    array('organization_id', \Auth::user()->id),
                    array('status', 'Active')
                );

                $arrData = $query->where($where_condition)->first();

            } else if($planType == 'future'){

                $where_condition = array(
                    array('organization_id', \Auth::user()->id),
                    array('status', 'Inactive'),
                    array($convert, '>', $t2)
                );

                $arrData = $query->where($where_condition)->first();

            } else if($planType == 'old'){

                $where_condition = array(
                    array('organization_id', \Auth::user()->id),
                    array('status', 'Inactive'),
                    array($convert, '<', $t2)
                );

                $arrData = $query->where($where_condition)->sortable(['created_at' => 'desc'])->paginate($pageSize);
            }



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
    public static function storeOrUpdateData($input, $user_id = false)
    {
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
    public static function getRecordByID($varID = false) {
        if(!empty($varID)){

            $arrRecord = Self::with('getPaymentDetail')
            ->where("id", "=", $varID)
            ->first();
            return ($arrRecord ? $arrRecord : []);

        }else {

            $pageSize = \Config::get('constants.AdminPageSize');

            $query = Self::with('getPaymentDetail', 'getSubscriptionDetail');
            $query->where("organization_id", "=", \Auth::user()->id);
            $arr_record = $query->paginate($pageSize);

            return ($arr_record ? $arr_record : []);

        }
        
    }

    /**
     * getOrgSubscriptionDetail
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getOrgSubscriptionDetail($org) {

            $arrRecord = Self::with('getPaymentDetail')
            ->where("organization_id", "=", $org)
            ->where("status", "=", "Active")
            ->first();
            return ($arrRecord ? $arrRecord : []);
        }

    
    /**
     * getCurrentPlan
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getCurrentPlan($orgId)
    {
        
        $whereCondtion = array(
          array('organization_id', $orgId),
          array('status', '=', 'Active'),
        );

        $arrRecord = Self::with(['getPaymentDetail', 'getSubscriptionDetail'])
        ->where($whereCondtion)
        ->first();
        return ($arrRecord ? $arrRecord : []);
    }        


    public function getSubscriptionDetail(){
    	return $this->belongsTo('App\Model\Admin\Subscription', 'subscription_id');
    }

    public function getPaymentDetail(){
        return $this->belongsTo('App\Model\Payment', 'payment_id');
    }
}