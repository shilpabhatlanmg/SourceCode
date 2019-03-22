<?php

namespace App\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;


class SiteSetting extends Model
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
        'option_name',
        'option_value',
    ];
    
    /**
     * Get All Setting
     * @param 
     * @return boolean
     * @since version 0.1
     * @author Sandeep Kumar
     */
    public static function getAllSettings()
    {
        $arrPermission = SELF::select('id', 'option_name', 'option_value', 'status')->get();
        return ($arrPermission ? : []);
    }

    /**
     * updateSettings
     * @param $key
     * @param $value
     * @return boolean
     * @since version 0.1
     * @author Sandeep Kumar
     */
    public static function updateSettings($key, $value)
    {
        $check = SELF::checkSettingExist($key);
        Cache::forget('cacheSiteSetting');

        if ($check == 1) {
            $rowUpdate = SELF::where('option_name', $key)->update(array('option_value' => $value));
            return ($rowUpdate ? true : false );
        } else {
            $arrSetting = [];
            $arrSetting['option_name'] = $key;
            $arrSetting['option_value'] = $value;
            //dd($arrSetting);
            self::create($arrSetting);  
            return (true ?: false);
        }
    }
    
    /**
     * check setting exist
     * @param array $name
     * @return 
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function checkSettingExist($name)
    {
        $varCheckData = SELF::select('*')
            ->WHERE('option_name', '=', $name)
            ->count();
        return ($varCheckData ? : 0);
    }
}
