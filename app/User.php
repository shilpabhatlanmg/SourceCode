<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Auth;


class User extends Authenticatable
{
  //use SoftDeletes;
  use Notifiable;
  use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['organization_id',
       'name', 'email', 'contact_number', 'country_code', 'profile_image', 'user_type', 'otp', 'otp_created_at', 'invitation_status', 'status', 'last_login', 'created_by'
    ];

  public $sortable = ['id', 'organization_id', 'email', 'contact_number', 'profile_image', 'last_login', 'invitation_status', 'status', 'name', 'created_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password', 'remember_token',
    ];


    /**
     * storeOrUpdateData
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function storeOrUpdateData($input, $user_id = false)
    {

      $user = self::updateOrCreate(['id' => (int) $user_id], $input);
      return $user;
    }


    /**
     * activateUser
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function activateUser($whr)
    {
      $data = self::where($whr)->first();
      if (!empty($data)) {
        $upd['status'] = 'Active';
        $upd['token'] = '';
        self::where($whr)->update($upd);
      }
      return $data;
    }


    /**
     * getUserData
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getUserData($arr = array())
    {

      $pageSize = \Config::get('constants.AdminPageSize');
      $organisationId = !empty(request()->organization_id) ? \Crypt::decryptString(request()->organization_id) : '';

      $status = !empty(request()->filter) ? request()->filter : '';

      //\DB::enableQueryLog();

      if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN') ) {

        $whereCondtion = array(
                //array('users.invitation_status', '=', 'Singup'),
          array('users.user_type', 'Security')
        );

      } else {

        $whereCondtion = array(
            //array('users.invitation_status', '=', 'Singup'),
          array('users.organization_id', \Auth::user()->id)
        );

      }

      $query = self::select('id', 'organization_id', 'name', 'email', 'country_code', 'contact_number', 'profile_image', 'last_login', 'user_type', 'invitation_status', 'status', 'created_by', 'created_at')->with(['getOrganizationName' => function($q1) {
        $q1->select('id', 'name');
      }

    ])

      ->where(function($query) use ($arr) {

        $strSearchTerm = !empty(request()->search)  ? request()->search : '';

        $query->Where('users.name', 'like', '%' . $strSearchTerm . '%');
        $query->orWhere('users.email', 'like', '%' . $strSearchTerm . '%');
        $query->orWhere('contact_number', 'like', '%' . $strSearchTerm . '%');
      });

      if(!empty($organisationId)){
        $query->where('organization_id', $organisationId);
      }

      if ( !empty($status) ) {

        $query->Where('invitation_status', '=', $status);
      }

      $query->Where($whereCondtion);

      $arr_record = $query->sortable(['id' => 'desc'])->paginate($pageSize);
      //dd(\DB::getQueryLog());
      return ($arr_record ? $arr_record : []);
    }


    /**
     * getUserByOrgId
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getUserByOrgId($orgId)
    {

      $arrRecord = Self::select('id')

      ->where(['organization_id' => $orgId, 'user_type' => 'Security'])
      ->get();
      return ($arrRecord ? $arrRecord : []);
    }



    /**
     * getAllUserInvitationCron
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllUserInvitationCron(){

      $where_condition = array('invitation_status' => 'pending', 'status' => 'Inactive');

      $query = self::select('id', 'organization_id', 'otp_created_at', 'otp', 'name', 'email', 'country_code', 'contact_number', 'invitation_status', 'status', 'created_by', 'created_at')
      ->where($where_condition)
      ->where('email', '<>', '')
      ->orderBy('id', 'desc');

      $arr_record = $query->get();
      return ($arr_record ? $arr_record : []);


    }

    /**
     * getRecordByID
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getRecordByID($varID)
    {
      $arrRecord = Self::select('*')->with(['getOrganizationName' => function($q1) {
        $q1->select('id', 'name');
      }
    ])

      ->where("id", "=", $varID)
      ->first();
      return ($arrRecord ? $arrRecord : []);
    }

    /**
     * deleteRecord
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function deleteRecord($id) {
        $varDeleteID = Self::where('id', '=', $id)->delete();
        return ($varDeleteID ?: false);
    }


    public function getOrganizationName(){
      return $this->belongsTo('App\Admin', 'organization_id');
    }


    public function incidentResponses(){
        return $this->hasMany('App\Model\AidRequestResponse')->where('status','Inactive');
    }

    public function chatRecords(){
        return $this->hasMany('App\Model\ChatRecord');
    }

    public function devices(){
        return $this->hasMany('App\Model\UserDevices');
    }
}
