@extends('adminLayout')

@section('content')

@section('pageTitle')
{{ $title }}
@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Aid Request Management</h4>

            <div class="table-responsive bs-example widget-shadow">
                <h4>Aid Request List</h4>

                @include('common.admin.flash-message')
                <div class="table-responsive">

                    <div class="row">

                        {{ Form::open(array('method' => 'get', 'autocomplete' => 'off', 'id'=>'organisation_id')) }}

                        <div class="col-md-2 col-sm-2 col-xs-6 pd-left-rq full-wid">

                            {{ Form::text('search', app('request')->input('search'), ['placeholder'=>'Search...', 'class'=>'form-control']) }}
                        </div>

                        <div class="col-md-2 col-sm-2 col-xs-6 pd-left-rq full-wid">

                            {{ Form::text('from_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Enter From Date *']) }}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('from_date') }}</strong>
                            </span>
                        </div>

                        <div class="col-md-2 col-sm-2 col-xs-6 pd-left-rq full-wid">

                            {{ Form::text('to_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Enter To Date *']) }}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('to_date') }}</strong>
                            </span>
                        </div>

                        @if($roles->name == \Config::get('constants.PLATFORM_ADMIN'))

                        <div class="col-md-2 col-sm-2 col-xs-6 mr-tp pd-left-rq full-wid">
                            <div class="flter">

                                <select name="organization_id" style = "text-transform:capitalize" class="form-control organization_id chosen-select" onChange = 'this.form.submit();'>
                                    <option value=""> --Organization--</option>
                                    @foreach($adminorganisation as $key => $val)
                                    <option value="{{ \Crypt::encryptString($val->id) }}"<?php if (!empty(app('request')->input('organization_id')) && $val->id == \Crypt::decryptString(app('request')->input('organization_id'))) echo ' selected="selected"'; ?>>{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @endif

                        <div class="col-md-2 col-sm-2 col-xs-6 mr-tp pd-left-rq full-wid">
                            <div class="flter">
                                <select name="incident_type_id" style = "text-transform:capitalize" class="form-control incident_type_id" onChange = 'this.form.submit();'>
                                    <option value=""> --incident type--</option>
                                    @foreach($incidenttype as $incident)
                                    <option value="{{ \Crypt::encryptString($incident->id) }}"<?php if (!empty(app('request')->input('incident_type_id')) && $incident->id == \Crypt::decryptString(app('request')->input('incident_type_id'))) echo ' selected="selected"'; ?>>{{ $incident->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2  col-md-2 col-xs-6 col-sm-4 pd-btn full-wid2">
                            {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
                            <a href="{{url('/admin/aid-request')}}" class="btn btn-default">Reset</a>
                        </div>

                        {{Form::close()}}

                    </div>


                    <table class="table table-bordered table-condensed" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>

                                @if($roles->name == \Config::get('constants.PLATFORM_ADMIN') && empty(app('request')->input('organization_id')))

                                <!--<th>@sortablelink('getBeaconDetail.organizations_name', 'Organization Name')</th>-->
                                <th>Organization Name</th>

                                @endif

                                <th>Incident Type</th>
                                <th>@sortablelink('getUserDetail.contact_number', 'Contact Number')</th>

                                <th>Building Name</th>
                                <th>Building Section Name</th>
                                <th>@sortablelink('created_at', 'Created at')</th>
                                 <th>Action</th>
                                <!--<th>@sortablelink('getIncidentDetail.name', 'Incident Type')</th>-->

                                <!--<th>@sortablelink('getUserDetail.contact_number', 'Contact Number')</th>-->
                                <!--<th>@sortablelink('getBeaconDetail.premise_name', 'Building Name')</th>-->

                                <!--<th>@sortablelink('getBeaconDetail.location_name', 'Building Section Name')</th>-->
                                <!---<th>@sortablelink('created_at', 'Created at')</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($aidRequestList)>0 && !empty($aidRequestList))
                            @foreach($aidRequestList as $key => $var)

                            <tr class="trCate">
                                <td data-id="{{$var->id}}">{{ ($aidRequestList->currentpage()-1) * $aidRequestList->perpage() + $key + 1 }}</td>

                                @if($roles->name == \Config::get('constants.PLATFORM_ADMIN') && empty(app('request')->input('organization_id')))
                                <td>
                                    <div class ="nowrap">
                                        {{((!empty($var) && is_object($var)) && !empty($var->getBeaconDetail->getOrganizationName->name) ? $var->getBeaconDetail->getOrganizationName->name : '') }}
                                    </div>
                                </td>
                                @endif

                                <td>
                                    {{ ((!empty($var) && is_object($var)) && !empty($var->getIncidentDetail->name) ? $var->getIncidentDetail->name : '') }}
                                </td>

                                <td>
                                    {{ ((!empty($var) && is_object($var)) && !empty($var->getUserDetail->contact_number) ? $var->getUserDetail->contact_number : '') }}
                                </td>

                                <td>
                                    <div class ="nowrap">
                                        {{((!empty($var) && is_object($var)) && !empty($var->getBeaconDetail->getPremiseName->name) ? $var->getBeaconDetail->getPremiseName->name : '') }}
                                    </div>

                                </td>

                                <td>
                                    <div class ="nowrap">
                                        {{((!empty($var) && is_object($var)) && !empty($var->getBeaconDetail->getLocationName->name) ? $var->getBeaconDetail->getLocationName->name : '') }}
                                    </div>
                                </td>

                                <td>

                                    <script>
                                        var localDate = getLocalDateTime("<?php echo $var->created_at; ?>");
                                    </script>

                                    @php

                                    $localTime = "<script>document.write(localDate)</script>";
                                    echo $localTime;
                                    
                                    @endphp

                                </td>
                                <td>
                                    <a href="{{url('/admin/aid-request-responded/').'/'. \Crypt::encryptString($var->id).'/'. \Crypt::encryptString($var->organization_id) }}" class="btns" title="View Attendee">
                                        <i class="fa fa-eye" aria-hidden="true">&nbsp;</i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach
                            @else
                            <tr>
                                <td colspan="8" align='center'>
                                    <span class="label label-danger">No Record Found !!!</span>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {!! $aidRequestList->appends($_GET)->links() !!}

        </div>
    </div>
</div>
@endsection

@section('addtional_css')
<style>

td.details-control {
    background: url('../admin_assets/images/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.details td.details-control {
    background: url('../admin_assets/images/details_close.png') no-repeat center center;
}

</style>
@endsection

@section('jscript')
<!-----------datatable handling js-------------->
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>

<!-----------newsletter listing  js-------------->
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/aid-request.js')}}"></script>

<script>
    $(function () {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });
    });

    $(document).ready(function(){
        $('input[name="from_date"]').mask('0000-00-00', {placeholder: "YYYY-MM-DD"});
        $('input[name="to_date"]').mask('0000-00-00', {placeholder: "YYYY-MM-DD"});
    });
</script>
@endsection
