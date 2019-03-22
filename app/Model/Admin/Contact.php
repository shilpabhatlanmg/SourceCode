<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contact extends Model
{

    /**
     * Custom primary key is set for the table
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
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
     * @function: storeData
     * @param $arrData
     * @desc: store record into database.
     */
    public static function storeData($arrData)
    {
        $data = self::create($arrData);
        return $data;
    }

    /**
     * @author Sandeep Kumar
     * @function: getAllContactUsRequest
     * @desc: get all contact us list.
     */
    public static function getAllContactUsRequest()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data = self::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), "contacts.*")
        ->orderBy('contacts.id', 'desc')->get();
        return $data;
    }

    /**
     * @author Sandeep Kumar
     * @function: deleteData
     * @param $data_ids
     * @desc: delete contact us record.
     */
    public static function deleteData($data_ids)
    {
        self::destroy($data_ids);
    }


    /**
     * @author Sandeep Kumar
     * @function: getContactUsEmailsById
     * @param $ids
     * @desc: get contact us email by id.
     */
    public static function getContactUsEmailsById($ids)
    {
        try {
            $data = self::whereIn('id', $ids)->get();
        } catch (\Exception $ex) {
            $data = $ex->getMessage();
        }
        return $data;
    }


    /**
     * @author Sandeep Kumar
     * @function: storeOrUpdateData
     * @param $input
     * @param $user_id
     * @desc: store or update record into database.
     */
    public static function storeOrUpdateData($input, $user_id = false)
    {
        $user = self::updateOrCreate(['id' => (int) $user_id], $input);
        return $user;
    }
}
