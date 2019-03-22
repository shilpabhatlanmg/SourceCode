<?php

namespace App\Http\Controllers\Admin\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\Location;
use App\Model\Admin\Premise;
use App\Admin;

class AjaxLocationController extends Controller {

    public function __construct() {
        $this->premise = new Premise();
        $this->location = new Location();
        $this->admin = new Admin();
        $this->middleware('auth:admin');
    }

    /**
     * @author Sandeep Kumar
     * @function: getPremise
     * @param \Illuminate\Http\Request  $request
     * @desc: get premise by id.
     */
    public function getPremise(Request $request) {
        $varStateID = (int) $request->query('varStateID');
        $result['premiselist'] = $this->premise->getAllPremiseByOrgId($varStateID);
        $result['locationCount'] = 0;
        $result['becaonId'] = $this->admin->getOrganization($varStateID);
        return ($result ? \Response::json($result) : (int) $result);
    }

    /**
     * @author Sandeep Kumar
     * @function: getLocation
     * @param \Illuminate\Http\Request  $request
     * @desc: get location by id.
     */
    public function getLocation(Request $request) {
        $varStateID = (int) $request->query('varStateID');
        $result = $this->location->getAllLocationByPremiseId($varStateID);
        return ($result ? \Response::json($result) : (int) $result);
    }

}
