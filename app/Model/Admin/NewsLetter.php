<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
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
    protected $fillable = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function storeEmailSubscription($input)
    {
        $data = self::create($input);
        return $data;
    }

    public static function findEmailSubscription($email)
    {
        $data = self::where(['email' => $email])->get();
        return $data;
    }

    public static function activateEmailSubscription($whr)
    {
        $data = self::where($whr)->first();
        if (!empty($data)) {
            $upd['is_verified'] = '1';
            $upd['token'] = '';
            self::where($whr)->update($upd);
        }
        return $data;
    }

    public static function getEmailSubscriptionData($email)
    {
        $data = self::where(['email' => $email])->first();
        return $data;
    }

    public static function getAllNewsLetters()
    {
        try {
            $data = self::select('id', 'email', 'is_verified', 'created_at')->orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $data['errors'] = $e->getMessage();
        }
        return $data;
    }

    public static function deleteData($data_ids)
    {
        try {
            $data = self::destroy($data_ids);
        } catch (\Exception $e) {
            $data = $e->getMessage();
        }
        return $data;
    }

    public static function getNewsletterEmailsById($ids)
    {
        try {
            $data = self::whereIn('id', $ids)->get();
        } catch (\Exception $ex) {
            $data = $ex->getMessage();
        }
        return $data;
    }
}
