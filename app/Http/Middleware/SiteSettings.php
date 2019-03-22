<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Admin\SiteSetting\SiteSetting;

class SiteSettings {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $arrSettingData = SiteSetting::getAllSettings();
        $arrData = [];
        if (isset($arrSettingData) && !empty($arrSettingData) && is_object($arrSettingData) && count($arrSettingData) > 0) {


            foreach ($arrSettingData as $settingdata) {
                $arrData[$settingdata->option_name] = $settingdata->option_value;
            }

            //$view->with('arrSiteSettings', $arrData);

            if ($arrData['site_offline'] == "0") {
                return redirect('site-offline')->with('offline_message', $arrData['site_offline_message']);
            }
        }
        return $next($request);
    }

}
