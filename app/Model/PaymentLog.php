<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class PaymentLog extends Model {

    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['txn_id', 'organization_id', 'subscription_id', 'response', 'status', 'type'];

    
    /**
     * storeOrUpdateData
     * @param 
     * @return array
     * @author RahulMehta
     */
    public static function storeOrUpdateData($input, $user_id = false) {
        $user = self::updateOrCreate(['id' => (int) $user_id], $input);
        return $user;
    }

    /**
     * getOrganizationName
     * @param 
     * @return array
     * @author RahulMehta
     */
    public function getOrganizationName() {
        return $this->belongsTo('App\Admin', 'organization_id');
    }

    /**
     * getSubscriptionName
     * @param 
     * @return array
     * @author RahulMehta
     */
    public function getSubscriptionName() {
        return $this->belongsTo('App\Model\Admin\Subscription', 'subscription_id');
    }

    /**
     * getAllRecord
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllRecord() {
       
        $pageSize = \Config::get('constants.AdminPageSize');
        $strSearchTerm = !empty(request()->search) ? request()->search : '';

        //\DB::enableQueryLog();
        $convert = \DB::raw("cast(created_at as date)");
        $from = date('Y-m-d', strtotime(request()->from_date));
        $to = !empty(request()->to_date) ? date('Y-m-d', strtotime(request()->to_date)) : date('Y-m-d');


        $query = self::with(['getSubscriptionName', 'getOrganizationName']);
                
        if (!empty(request()->from_date)) {
                $query->whereBetween($convert, [$from, $to]);
            }
            if (!empty($strSearchTerm)) {
            $query->where('txn_id', 'LIKE', "%$strSearchTerm%");
        }
        
        /*if (!empty($strSearchTerm)) {
            $query->whereHas('getOrganizationName', function($query) use ($strSearchTerm) {
                $query->orWhere('name', 'LIKE', "%$strSearchTerm%");
            });
        }*/
             if (!empty(request()->organization_id)) {
               $query->Where('organization_id', '=', request()->organization_id);
            }

        $arr_record = $query->sortable(['id' => 'asc'])->paginate($pageSize);
        //dd($arr_record);
        //dd(\DB::getQueryLog());
        return ($arr_record ? $arr_record : []);
    }

}
