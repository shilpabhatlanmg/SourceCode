<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;


class AidRequest extends Model
{
	use SoftDeletes;
    use Sortable;

    public $sortable = ['id', 'name', 'created_at'];

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
     * @function: getAidRequest
     * @desc: get aid request.
     */
    public static function getAidRequest()
    {
        $pageSize = \Config::get('constants.AdminPageSize');
        $search = !empty($request->search) ? $request->search : '';

        $query = AidRequest::with(['getPremiseName' => function($q1) {
            $q1->select('id', 'name');
        }, 'getAidRequestDetails.getUserName'])

        ->where('aid_requests.status', '=', 'Active');

        if (!empty($search)) {
            //$query->where('id', '=', $search);
            $query->where('name', 'LIKE', "%$search%");
        }

        $arr_record = $query->sortable()->paginate($pageSize);

        return ($arr_record ? $arr_record : []);
    }


    public function getAidRequestDetails(){
        return $this->hasOne('App\Model\Admin\AidRequestStatus');
        //return $this->hasMany('App\Model\Admin\AidRequestStatus');
    }

    public function responses(){
        return $this->hasMany('App\Model\Admin\AidRequestResponse');
    }

    
    public function getPremiseName(){
        return $this->belongsTo('App\Model\Admin\Premise', 'premises_id');
    }

    public function getLocationName(){
        return $this->belongsTo('App\Model\Admin\Location', 'location_id');
    }

    public function getOrganisationName(){
        return $this->belongsTo('App\Admin', 'becon_major_id');
    }

    public function getBeconsName(){
        return $this->belongsTo('App\Model\Admin\Becon', 'minor_id', 'minor_id');
    }
    public function getRequestUser() {
        return $this->belongsTo('App\User', 'user_id');
    }

}
