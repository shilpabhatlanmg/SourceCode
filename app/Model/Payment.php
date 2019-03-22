<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['organization_id', 'pay_amount', 'transaction_id', 'customer_id', 'fingerprint', 'transaction_response', 'status', 'mode'];

    public static function storeOrUpdateData($input, $user_id = false)
    {
        $user = self::updateOrCreate(['id' => (int) $user_id], $input);
        return $user;
    }

    /**
     * getTransaction
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getTransaction($orgId=false) {
        //\DB::enableQueryLog();

        $query = self::with('getOrganizationName');

        $query->where('organization_id', '=', $orgId);

        $arr_record = $query->get();
        //dd(\DB::getQueryLog());
        return ($arr_record ? $arr_record : []);
    }

    
    public function getOrganizationName(){
        return $this->belongsTo('App\Admin', 'organization_id');
    }
}
