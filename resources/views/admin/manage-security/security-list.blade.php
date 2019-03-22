@extends('adminLayout')

@section('content')

@section('pageTitle')
{{ $title }}
@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Security Team Management</h4>

            <div class="bs-example widget-shadow">


                <div class="row">

                    {{ Form::open(array('method' => 'get')) }}
                    <div class="col-md-3 col-sm-3 col-xs-6 srch-left full-wid">                                        
                        {{ Form::text('search', app('request')->input('search'), ['placeholder'=>'Search...', 'class'=>'form-control']) }}                      
                    </div>

                    <div class=" col-md-3 col-sm-3 col-xs-6 col-sm-4 pd-btn full-wid2">  
                        {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
                        <a href="{{url('/admin/security')}}" class="btn btn-default reset">Reset</a>
                    </div>

                    @if($roles->name == \Config::get('constants.PLATFORM_ADMIN'))

                    <div class="col-md-2 col-sm-2 col-xs-6 mr-tp srch-left full-wid2">
                        <div class="flter" id="organization-filter">
                            <?php /*{{ Form::select('organization_id',['' => '--filter by organization--']+@$adminorganisation->pluck('name','id')->toArray(), (!empty(app('request')->input('organization_id')) ? app('request')->input('organization_id') : ''),['id'=> 'organization_id', 'onChange' => 'this.form.submit();', 'class'=>'form-control', 'style' => 'text-transform:capitalize;'])}}*/ 
                            ?>
                            <select name="organization_id" style = "text-transform:capitalize" class="form-control organization_id chosen-select" onChange = 'this.form.submit();'>
                                <option value=""> --filter by organization--</option>
                                @foreach($adminorganisation as $key => $val)
                                <option value="{{ \Crypt::encryptString($val->id) }}"<?php if (!empty(app('request')->input('organization_id')) && $val->id == \Crypt::decryptString(app('request')->input('organization_id'))) echo ' selected="selected"'; ?>>{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @endif
                    <div class="col-md-3 col-sm-3 col-xs-6 mr-tp srch-left full-wid">
                        <div class="flter">
                            @if(!isset($special))
                            {{ Form::select('filter', ['pending' => 'Pending', 'resend-invitation' => 'Resend Invitation', 'complete' => 'Complete'] ,null, ['placeholder'=>'- Filter By Invitation Status -', 'onChange' => 'this.form.submit();', 'class'=>'form-control pull-right']) }}
                            @endif
                        </div>
                    </div>
                    {{Form::close()}}
                    
                    <div class="col-md-1 col-sm-1 col-xs-6 srch-left full-wid">
                        <div class="invite_btn">
                            <button class="btn btn-default pull-right" data-toggle="modal" data-target="#securityModal">
                                Invite
                            </button>
                        </div>
                    </div>

                </div>

                @include('common.admin.flash-message')
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                @if($roles->name == \Config::get('constants.PLATFORM_ADMIN') && empty(app('request')->input('organization_id')))
                                <th>@sortablelink('getOrganizationName.name', 'Organization Name')</th>
                                @endif
                                <th>@sortablelink('name', 'Name')</th>
                                <th>@sortablelink('email', 'Email')</th>
                                <th>@sortablelink('invitation_status', 'Invitation Status')</th>
                                <th>@sortablelink('status', 'Account Status')</th>
                                <th width="200px;"><center>Action</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($userList)>0 && !empty($userList))
                            @foreach($userList as $key => $var)

                            @php
                            
                            if ($var->status == 'Inactive') {
                            $status = "Inactive";
                            $label = "danger";
                            $sts = 'Active';
                        } else if($var->status == 'Active') {
                        $status = "Active";
                        $label = "success";
                        $sts = 'Inactive';
                    }else {

                    $status = "Inactive";
                    $label = "danger";
                    $sts = 'Active';

                }

                
                if ($var->invitation_status == 'complete') {
                $invit_status = "Complete";
                $invit_label = "success";

            } else if ($var->invitation_status == 'resend-invitation') {
            $invit_status = "Resend Invitation";
            $invit_label = "primary";

        } else {
        $invit_status = "Pending";
        $invit_label = "danger";
    }

    @endphp

    <tr class="trCate">
        <td>{{ ($userList->currentpage()-1) * $userList->perpage() + $key + 1 }}</td>

        @if($roles->name == \Config::get('constants.PLATFORM_ADMIN') && empty(app('request')->input('organization_id')))
        <td>
            <div class ="nowrap">
                {{   (!empty($var) && is_object($var) && !empty($var->getOrganizationName->name) ? $var->getOrganizationName->name : '') }}
            </div>
        </td>
        @endif
        

        <td style="width:175px;">

            @php 

            $path = 'public/storage/admin_assets/images/profile_image/' . (!empty($var) && is_object($var) && !empty($var->profile_image) ? $var->profile_image : '');

            @endphp

            @if(!empty($var) && is_object($var) && !empty($var->profile_image) && file_exists($path))
            
            {{ Html::image($path, 'image', array('style' => 'width:30px; height:30px;float:left;', 'id' => 'headshot_preview', 'title' => (!empty($var) && is_object($var) && !empty($var->profile_image) ? $var->profile_image : '' ))) }}

            @else

            {{ Html::image(asset('public/admin_assets/images/user-profile.jpg'), 'no-image', array('style' => 'width:30px; height:30px;float:left;', 'id' => 'headshot_preview', 'title' => 'Image Preview')) }}
            
            @endif
            <div class ="nowrap" style="float:left;width:75px !important;padding-left:5px;word-break:break-word !important;">


                {{((!empty($var) && is_object($var)) && !empty($var->name) ? $var->name : '') }}
            </div>
        </td>

        <td>
            {{ ((!empty($var) && is_object($var)) && !empty($var->email) ? $var->email : '') }}
        </td>

        @php
        $encryptId = \Crypt::encryptString($var->id);
        @endphp

        <td>
            <span class="label label-{{ $invit_label }} {{ ($var->invitation_status == 'resend-invitation') ? 'reinvite' : '' }} invit_sts{{$var->id}}" data-id="{{ !empty($var) && is_object($var) && $var->invitation_status == 'resend-invitation' && !empty($var->id) ? $encryptId : '' }}" style="{{ !empty($var) && is_object($var) && $var->invitation_status == 'resend-invitation' ? 'cursor: pointer;' : '' }}" title= "{{ $invit_status }}">{{ $invit_status }}</span>
        </td>

        <td>
            <span class="label label-{{ $label }} sts" style="cursor: pointer;" data-sts="{{ $sts }}" title="click to make {{ $sts }}" rel-id = "{{$var->id}}"  data-id="{{ !empty($var) && is_object($var) && !empty($var->id) ? $encryptId : '' }}" data-type="security">{{ $status }}</span>
        </td>

        <td  class="actions fr-btn center action-center">

            
            <div style="width:90px;">
                <a href="{{ route('security.edit', ['id' => $encryptId, 'search' => !empty(request()->get('search')) ? request()->get('search') : '' ]) }}" class="btns br1 " title="Edit">
                    <i class="fa fa-pencil" aria-hidden="true">&nbsp;</i>
                </a>

                <a href="" id="{{ $encryptId }}" class="btns br2  delete-record" title="Delete">
                    <i class="fa fa-trash-o" aria-hidden="true">&nbsp;</i>
                </a>
                
                    <a href="{{ route('security.show', ['id' => $encryptId]) }}" class="btns" title="View">
                    <i class="fa fa-eye" aria-hidden="true">&nbsp;</i>
                </a>
            </div>
            
        </td>

    </tr>

    {!!
        Form::open(
        array(
        'name' => 'delete-form',
        'id' => 'delete-form-'.$encryptId,
        'url' => 'admin/security/'.(!empty($var) && is_object($var) && !empty($var->id) ? $encryptId : ''),
        'autocomplete' => 'off',
        'class' => 'form-horizontal',
        'style' => 'display:none',
        'files' => false
        )
        )
        !!}

        {{ Form::hidden('id', (!empty($var) && is_object($var) && !empty($var->id) ? $encryptId : '')) }}

        {{ method_field('DELETE') }}

        {!! Form::close() !!}

    @endforeach
    @else
    <tr>
        <td colspan="10" align='center'>
            <span class="label label-danger">No Record Found !!!</span>
        </td>
    </tr>
    @endif
</tbody>
</table>
</div>
</div>
{!! $userList->appends($_GET)->links() !!}
</div>
</div>
</div>

<!-- Modal -->
<div id="securityModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invite</h4>
    </div>

    <div class="modal-body">

        <div class="flash-message">  

            <div class="alert-msg" style="display:none;">
                <span class="alermsg"></span><!--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
            </div>

        </div>

        {{ Form::open(array('method' => 'post', 'id' => 'admin_invite')) }}


        @if($roles->name == \Config::get('constants.PLATFORM_ADMIN'))

        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Organization Name</label>

            {{ Form::select('organization_id',['' => '--select--']+@$adminorganisation->pluck('name','id')->toArray(), ( !empty($objData) && is_object($objData) && !empty($objData->organization_id) ? $objData->organization_id : ''),['id'=> 'organization_id', 'class'=>'form-control org_id chosen-select'])}}                            
            @if ($errors->has('organization_id'))
            <span class="help-block" style = "display:block;color:red;">
                <strong>{{ $errors->first('organization_id') }}</strong>
            </span>
            @endif
        </div>

        @endif


        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Name</label>

            {{ Form::text('name', app('request')->input('name'), ['placeholder'=>'Enter name...', 'class'=>'form-control']) }}
        </div>

        <div class="form-group">
            <label for="message-text" class="col-form-label">Email:</label>
            
            {{ Form::email('email', (!empty($objData) && is_object($objData) && !empty($objData->email) ? $objData->email : ''), ['class' => 'form-control user', 'placeholder' => 'Enter email']) }}
            
        </div>

        <div class="form-group">

            <label for="message-text" class="col-form-label">Country Code:</label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="message-text" class="col-form-label">Contact Number:</label>
            
            <div class="row">  
                <div class="col-sm-3 col-xs-12">
                    {{ Form::select('country_code',['+1' => '+1', '+91' => '+91'], null,['id'=> 'country_code', 'class'=>'form-control country_code'])}}
                </div>

                <div class="col-sm-9 col-xs-12">
                    {{ Form::text('contact_number', null, ['class' => 'form-control user', 'placeholder' => 'Enter contact number']) }}
                </div>

            </div>
        </div>
        
        <div class="form-group">
            <label for="message-text" class="col-form-label">Profile Image:</label>

            {{ Form::hidden('user_type', '1') }}
            <div class=" up_choose">
                <div class="upload_choose">
                   <label class="custom-file-upload">
                       {{ Form::file('profile_image', ['id' => 'image', 'class'=>'']) }}
                       Choose File
                   </label>
               </div>
               <div class="profile_image_btn">
                {{ Html::image(asset('/public/admin_assets/images/user-profile.jpg'), 'no-image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview1', 'title' => 'Image Preview', 'class' => 'img_prvew')) }}
            </div>
        </div>
        
        
    </div>
    <div class="clearfix"></div>
    <div class="modal-footer">
        <button type="submit" name="submits" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </div>

    {{Form::close()}}
    
</div>

</div>

</div>
</div>

@endsection

@section('addtional_css')
@endsection

@section('jscript')
<!-----------datatable handling js-------------->
<!--<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>-->

<!-----------newsletter listing  js-------------->
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/client-validation.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/delete-confirm.js')}}"></script>

<script>
    var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
    isMobile = true;
}
if(!isMobile)   $('#organization-filter').addClass('pull-right');
</script>

<script>

    $(document).ready(function(){

        $('input[name="contact_number"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
        
    });

    $(document).ready(function(){

        $("#image").change(function(){
            headshotPreview(this);
        });


    });

    function headshotPreview(input)
    {
        if(input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#headshot_preview1').attr('src', e.target.result);
                $('#headshot_preview1').addClass('profile_pic_upload');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#securityModal').on('hidden.bs.modal', function (e) {

        $('.org_id').val('').trigger('chosen:updated');
        $(this)
        .find("input,textarea")
        .val('')
        .end()
        .find("input[type=checkbox], input[type=radio]")
        .prop("checked", "")
        .end();
        location.reload();
    })
</script>
@endsection