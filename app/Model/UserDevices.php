<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserDevices extends Model
{
  /**
   * set the table name which is belong this model
   *
   * @var array
   */
  protected $table = 'user_devices';
  /**
   * Custom primary key is set for the table
   *
   * @var integer
   */
  protected $primaryKey = 'id';


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'device_token',
  ];

  
  /**
     * @author Sandeep Kumar
     * @function: isExists
     * @desc: check user exist or not.
     */
  public static function isExists($userId, $token)
  {
    $device = self::where('user_id', $userId)->where('device_token', $token)->first();
    if (!empty($device)) {
      return $device->id;
    }
    return 0;
  }
}
