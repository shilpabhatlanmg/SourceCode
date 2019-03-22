<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StripeCustomer extends Model
{
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
    protected $fillable = [
    	'customer_id',
    	'plan_id',
    	'payment_date'    
    ];


    /**
     * @author Sandeep Kumar
     * @function: storeOrUpdateData
     * @param : $input
     * @param : $user_id
     * @desc: store and update record into database.
     */
    public static function storeOrUpdateData($input, $user_id = false) {
        $user = self::updateOrCreate(['id' => (int) $user_id], $input);
        return $user;
    }

    /**
     * @author Sandeep Kumar
     * @function: getStripCustomer
     * @desc: get stripe customer.
     */
    public static function getStripCustomer()
    {
        $arrRecord = Self::select('customer_id', 'plan_id')
        ->get();
        return ($arrRecord ? $arrRecord : []);
    }
}
