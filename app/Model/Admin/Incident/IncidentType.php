<?php

namespace App\Model\Admin\Incident;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class IncidentType extends Model
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

    public $sortable = ['id', 'name', 'status', 'created_at'];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
    	'name',
    	'status',
    	'created_by',
    	'updated_by',
    	'created_at',
    	'updated_at',
    ];

    /**
     * @author Sandeep Kumar
     * @function: getIncidentType
     * @param $id
     * @desc: get incident by id.
     */
    public static function getIncidentType($id = false)
    {

    	$whereData = array(
    		array('status', 'Active')
    	);
    	
        //\DB::enableQueryLog();
    	if(isset($id) && !empty($id)){

    		$arr_record = self::select('id', 'name', 'created_by', 'updated_by', 'created_at', 'updated_at')

    		->Where('id', '=', $id)->first();

    	} else {

    		$arr_record = self::select('id', 'name', 'created_by', 'updated_by', 'created_at', 'updated_at')

    		->Where($whereData)->get();

    	}

        //dd(\DB::getQueryLog());
    	return ($arr_record ? $arr_record : []);
    }
}
