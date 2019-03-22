<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class PocDetail extends Model
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
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'poc_name',
        'poc_contact_no',
        'poc_email',
    ];


    /**
     * @author Sandeep Kumar
     * @function: getPocById
     * @param $id
     * @desc: get poc by id.
     */
    public static function getPocById($id) {

        $arrCond = array('organization_id' => $id);
        $sqlQuery = Self::select('*');
        $datas = $sqlQuery->where($arrCond)->get();
        return ($datas ? $datas : []);
    }

    /**
     * @author Sandeep Kumar
     * @function: deleteRecord
     * @param $id
     * @desc: delete record from database.
     */
    public static function deleteRecord($id)
    {
        $where = array('id' => $id);
        $varDeleteID = Self::where($where)->delete();
        return ($varDeleteID ?: false);
    }
}
