<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AidRequestResponse extends Model {

    use SoftDeletes;

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
    protected $fillable = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * @author Sandeep Kumar
     * @function: getRecordByID
     * @param: $varID
     * @param: $userId
     * @desc: get record by id.
     */
    public static function getRecordByID($varID, $userId =false) {
        $arrRecord = Self::with('getUser')
                ->where(["aid_request_id" => $varID, 'status' => 'Active'])
                ->whereIn('user_id', $userId)
                ->get();
        return ($arrRecord ? $arrRecord : []);
    }

    public function getAidRequest() {
        return $this->belongsTo('App\Model\AidRequest', 'aid_request_id');
    }

    public function getUser() {
        return $this->belongsTo('App\User', 'user_id');
    }

    
}
