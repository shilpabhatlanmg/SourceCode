<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentLogOld extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['txn_id', 'organization_id', 'response', 'type'];

    public static function storeOrUpdateData($input, $user_id = false)
    {
        $user = self::updateOrCreate(['id' => (int) $user_id], $input);
        return $user;
    }
    
    /**
     * getOrganizationName
     * @param 
     * @return array
     * @author RahulMehta
     */
    public function getOrganizationName(){
        return $this->belongsTo('App\Model\Admin\Payment', 'organization_id');
    }
    
    /**
     * getSubscriptionName
     * @param 
     * @return array
     * @author RahulMehta
     */
    public function getSubscriptionName(){
        return $this->belongsTo('App\Model\Admin\Subscription', 'subscription_id');
    }
    
    /**
     * getAllRecord
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllRecord($roleId=false) {
        $pageSize = \Config::get('constants.AdminPageSize');
        $strSearchTerm = !empty(request()->search) ? request()->search : '';

        //\DB::enableQueryLog();
        $from = date('Y-m-d', strtotime(request()->from_date));
        $to = !empty(request()->to_date) ? date('Y-m-d', strtotime(request()->to_date)) : date('Y-m-d') ;
      

        $query = self::with('subscriptionDetail')
                ->where(function($query) use ($strSearchTerm) {

            $query->Where('name', 'like', '%' . $strSearchTerm . '%');
            $query->orWhere('email', 'like', '%' . $strSearchTerm . '%');
            if(!empty(request()->from_date)){
            $query->whereBetween($convert, [$from, $to]);

        }
            $query->orWhere('phone', 'like', '%' . $strSearchTerm . '%');
        });

        $arr_record = $query->sortable(['name' => 'asc'])->paginate($pageSize);
        //dd(\DB::getQueryLog());
        return ($arr_record ? $arr_record : []);
    }
}
