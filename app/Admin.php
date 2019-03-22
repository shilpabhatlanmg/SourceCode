<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\AdminResetPasswordNotification;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

//use Laravel\Cashier\Billable;

class Admin extends Authenticatable {

    use Sortable;
    //use SoftDeletes;
    use Notifiable;
    //use Billable;

    /**
     * set the table name which is belong this model
     *
     * @var array
     */
    protected $table = 'organizations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'is_root_admin', 'is_admin','role_id', 'password', 'temp_password', 'becon_major_id', 'address', 'phone', 'emergency_contact', 'username', 'country_id', 'state_id', 'city_id', 'zip_code', 'status', 'reason', 'user_image', 'remember_token', 'token', 'timezone'
    ];
    public $sortable = ['id', 'name', 'email', 'address', 'phone', 'status', 'created_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public static function storeOrUpdateData($input, $user_id = false) {

        $user = self::updateOrCreate(['id' => (int) $user_id], $input);
        return $user;
    }

    /**
     * getAllRecord
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllRecord($roleId=false) {
        $pageSize = \Config::get('constants.AdminPageSize');
        
        
        
        
        $strSearchTerm = !empty(request()->search) ? request()->search : '';

        //\DB::enableQueryLog();

        $query = self::with('subscriptionDetail')
                ->where(function($query) use ($strSearchTerm) {

            $query->Where('name', 'like', '%' . $strSearchTerm . '%');
            $query->orWhere('email', 'like', '%' . $strSearchTerm . '%');
            $query->orWhere('phone', 'like', '%' . $strSearchTerm . '%');
        });

        $query->where('role_id', '=', $roleId);

        $arr_record = $query->sortable(['name' => 'asc'])->paginate($pageSize);
        //dd(\DB::getQueryLog());
        return ($arr_record ? $arr_record : []);
    }

    /**
     * getOrganization
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getOrganization($id = false) {
        
        
         $role_org_admin = Role::where('name', \Config::get('constants.ORGANIZATION_ADMIN'))->first();
        
        $whereData = array(
            array('role_id', '=', $role_org_admin->id),
            array('status', 'Active'),
             
            
        );

       // \DB::enableQueryLog();
        if (isset($id) && !empty($id)) {

            $arr_record = self::with(['pocName'])
                            ->Where('id', '=', $id)->first();
        } else {

            $arr_record = self::select('id', 'becon_major_id', 'name', 'token', 'username', 'email', 'gender', 'password', 'phone', 'address', 'country_id', 'state_id', 'city_id', 'zip_code', 'status','role_id', 'created_at')
                            ->Where($whereData)->orderBy('name', 'asc')->get();
        }

        //dd(\DB::getQueryLog());
        //dd($arr_record);
        return ($arr_record ? $arr_record : []);
    }

    /**
     * getAdminUsers
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAdminUsers($id = false) {

        $whereData = array(
            array('id', '!=', '1'),
            array('status', 'Active')
        );

        //\DB::enableQueryLog();
        if (isset($id) && !empty($id)) {

            $arr_record = self::with(['pocName'])
                            ->Where('id', '=', $id)->where('is_admin', '=', 1)->where('is_root_admin', '=', 0)->first();
        } else {

            $arr_record = self::select('id', 'becon_major_id', 'name', 'token', 'username', 'email', 'gender', 'password', 'phone', 'address', 'country_id', 'state_id', 'city_id', 'zip_code', 'status', 'created_at')
                            ->Where($whereData)->where('is_admin', '=', 1)->where('is_root_admin', '=', 0)->orderBy('name', 'asc')->get();
        }

        //dd(\DB::getQueryLog());
        return ($arr_record ? $arr_record : []);
    }

    /**
     * getAdminUserLists
     * @param
     * @return array
     * @author RahulMehta
     */
    public static function getAdminUserLists() {
        $pageSize = \Config::get('constants.AdminPageSize');
        $strSearchTerm = !empty(request()->search) ? request()->search : '';
        $whereData = array(
            array('id', '!=', '1')
        );

        //\DB::enableQueryLog();


        $query = self::select('id', 'name', 'username', 'email', 'gender', 'password', 'phone', 'address', 'status', 'created_at');
        if (!empty($strSearchTerm)) {
            $query->Where('name', 'like', '%' . $strSearchTerm . '%');
            $query->orWhere('username', 'like', '%' . $strSearchTerm . '%');
            $query->orWhere('email', 'like', '%' . $strSearchTerm . '%');
            $query->orWhere('phone', 'like', '%' . $strSearchTerm . '%');
        }
        $arr_record = $query->Where($whereData)->where('is_admin', '=', 1)->where('is_root_admin', '=', 0)->sortable(['id' => 'desc'])->paginate($pageSize);



        //dd(\DB::getQueryLog());
        return ($arr_record ? $arr_record : []);
    }

    /**
     * getOrganizationByEmail
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getOrganizationByEmail($email = false) {
        if (!empty($email)) {

            $arr_record = self::select('id', 'becon_major_id', 'name', 'token', 'username', 'email', 'gender', 'password', 'phone', 'address', 'country_id', 'state_id', 'city_id', 'zip_code', 'status', 'created_at')
                            ->Where('email', '=', $email)->first();
        }
        return ($arr_record ? $arr_record : []);
    }

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles) {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                    abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
                abort(401, 'This action is unauthorized.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles) {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role) {
        return null !== $this->roles()->where('name', $role)->first();
    }

    /**
     * countryName
     * @param
     * @return object from Area zip code table on behalf of zip_id
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function countryName() {
        return $this->belongsTo('App\Model\Country', 'country_id');
    }

    /**
     * stateName
     * @param
     * @return object from Area zip code table on behalf of zip_id
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function stateName() {
        return $this->belongsTo('App\Model\State', 'state_id');
    }

    /**
     * stateName
     * @param
     * @return object from Area zip code table on behalf of zip_id
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function cityName() {
        return $this->belongsTo('App\Model\City', 'city_id');
    }

    /**
     * stateName
     * @param
     * @return object from Area zip code table on behalf of zip_id
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function pocName() {
        return $this->hasOne('App\Model\Admin\PocDetail', 'organization_id');
    }

    /**
     * subscriptionDetail
     * @param
     * @return object from Area zip code table on behalf of zip_id
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function subscriptionDetail() {
        return $this->hasOne('App\Model\OrganizationSubscription', 'organization_id');
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

    /**
     * getRecordByID
     * @param
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getRecordByID($varID) {
        $arrRecord = Self::with('subscriptionDetail', 'subscriptionDetail.getSubscriptionDetail')
                ->where("id", "=", $varID)
                ->first();
        return ($arrRecord ? $arrRecord : []);
    }

    public static function activateUser($whr) {
        $data = self::where($whr)->first();
        if (!empty($data)) {
            $upd['status'] = '1';
            //$upd['token'] = '';
            self::where($whr)->update($upd);
        }
        return $data;
    }
    
     public function roleName() {
        return $this->belongsTo('App\Model\Role', 'role_id');
    }

}


