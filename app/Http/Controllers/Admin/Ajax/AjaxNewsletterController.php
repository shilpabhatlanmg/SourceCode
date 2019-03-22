<?php

namespace App\Http\Controllers\Admin\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\NewsLetter;

class AjaxNewsletterController extends Controller {

    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * @author Sandeep Kumar
     * @function: getNewsletterList
     * @desc: get Newletter list.
     */
    public function getNewsletterList() {
        try {

            //$data = Newsletter::getAllNewsLetters();
            //return $this->dataProvider->getNewsletters($this->request, $data);
            $arr = array();
            $col = array();
            $arr['draw'] = 1;
            $arr['recordsTotal'] = 57;
            $arr['recordsFiltered'] = 57;
            $arr['searching'] = true;
            $arr['paging'] = true;
            $arr['info'] = true;
            $arr['ordering'] = true;
            $arr['dom'] = 'Bfrtip';
            $arr['buttons'] = [
                [
                    'extend' => 'collection',
                    'text' => 'All Export',
                    'buttons' => [
                        'copy',
                        'excel',
                        'csv',
                        'pdf',
                        'print'
                    ],
                ],
                    /* [
                      'extend' => 'copy',
                      'text' => 'Copy',
                      'exportOptions' => ['modifier' => ['page' => 'current']]
                      ],
                      [
                      'extend' => 'excel',
                      'text' => 'export current page in excel',
                      'exportOptions' => ['modifier' => ['page' => 'current']]
                      ],
                      [
                      'extend' => 'csv',
                      'text' => 'export current page in csv',
                      'exportOptions' => ['modifier' => ['page' => 'current']]
                      ],
                      [
                      'extend' => 'pdfHtml5',
                      'text' => 'export current page in pdf',
                      'orientation' => 'landscape',
                      'pageSize' => 'LEGAL',
                      'exportOptions' => ['modifier' => ['page' => 'current']]
                      ],
                      [
                      'extend' => 'excel',
                      'text' => 'export current page in excel',
                      'exportOptions' => ['modifier' => ['page' => 'current']]
                      ], */
            ];



            foreach ($_POST['columnarray'] as $val) {
                $col[] = array($val);
            }
            $arr['columns'] = $col;

            $arr_data = NewsLetter::getAllNewsLetters();

            if (isset($arr_data) && is_object($arr_data) && !empty($arr_data)) {
                $i = 1;
                foreach ($arr_data as $val) {
                    if ($val->is_verified == 1) {
                        $lbl = 'Verified';
                        $cls = 'success';
                    } else {
                        $lbl = 'Not verfied';
                        $cls = 'danger';
                    }
                    $arr['data'][] = array(
                        ('<input class="deleteRow" type="checkbox" value="' . $val->id . '">'),
                        $i,
                        ((isset($val) && !empty($val) && is_object($val)) ? $val->email : ''),
                        ((isset($val) && !empty($val) && is_object($val)) ? '<span class="label label-' . $cls . '">' . $lbl . '<span>' : ''),
                        ((isset($val) && !empty($val) && is_object($val)) ? $val->created_at->diffForHumans() : ''),
                        '<a title="Delete" href="javascript:void(0);" class="delete-record" id="' . $val->id . '"><span class="glyphicon glyphicon-trash" style="color:#cc0000"></span></a>&nbsp;<!--<a title="Send" href="javascript:void(0);" class="" id="' . $val->id . '"><span class="glyphicon glyphicon-send" onclick="sendNewsLetter(this);" style="color:#cc0000"></span></a>-->',
                    );

                    $i++;
                }
            }
            echo json_encode($arr);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: deleteNewsletter
     * @param \Illuminate\Http\Request  $request
     * @desc: Delete Newletter list.
     */
    public function deleteNewsletter(Request $request) {
        $request = $request->all();
        $data_ids = explode(',', $request['data_ids']);
        $data = NewsLetter::deleteData($data_ids);
        if (isset($data) && !empty($data)) {
            $msg['type'] = 'success';
        } else {
            $msg['type'] = 'error';
        }
        echo json_encode($msg);
        exit;
    }

    /**
     * @author Sandeep Kumar
     * @function: sendNewsletter
     * @param \Illuminate\Http\Request  $request
     * @desc: Send Newletter to user.
     */
    public function sendNewsletter(Request $request) {
        $request_all = $request->all();
        $data_ids = json_encode(explode(',', $request_all['data_ids']));
        // Set newsletter ids into Session
        $request->session()->put('newsletter_ids', $data_ids);
        $data['type'] = 'success';
        return json_encode($data);
    }

}
