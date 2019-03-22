@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Visitor Management</h4>

            <div class="bs-example widget-shadow">
                <h4>Visitor Log</h4>

                @include('common.admin.flash-message')
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

                        <div class="col-md-3 col-xs-6 col-sm-4 pd-btn full-wid2">  
                            {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
                            <a href="{{url('/admin/visitorlogs')}}" class="btn btn-default">Reset</a>
                        </div>

                        {{Form::close()}}

                    </div>
                <div class="table-responsive">

                    


                    <table class="table table-bordered table-condensed" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                
                                @if($roles->name == \Config::get('constants.PLATFORM_ADMIN'))
                                
                                <th>@sortablelink('getLocationDetail.name', 'Organization Name')</th>

                                @endif

                                <th>Contact Number</th>
                                <th>Building Name</th>
                                <th>@sortablelink('getLocationDetail.name', 'Building Section Name')</th>
                                <th>@sortablelink('created_at', 'Visit at')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($arrData)>0 && !empty($arrData))
                            @foreach($arrData as $key => $var)

                            <tr class="trCate">
                                <td>{{ ($arrData->currentpage()-1) * $arrData->perpage() + $key + 1 }}</td>

                                
                                @if($roles->name == \Config::get('constants.PLATFORM_ADMIN'))
                                <td>
                                    <div class ="nowrap">
                                        {{((!empty($var) && is_object($var)) && !empty($var->getLocationDetail->getOrganizationName->name) ? $var->getLocationDetail->getOrganizationName->name : '') }}
                                    </div>
                                </td>
                                @endif

                                <td>
                                    {{ ((!empty($var) && is_object($var)) && !empty($var->getUserDetail->contact_number) ? $var->getUserDetail->contact_number : '') }}
                                </td>

                                <td>
                                    <div class ="nowrap">
                                        {{((!empty($var) && is_object($var)) && !empty($var->getLocationDetail->getPremiseName->name) ? $var->getLocationDetail->getPremiseName->name : '') }}
                                    </div>

                                </td>

                                <td>
                                    <div class ="nowrap">
                                        {{ ((!empty($var) && is_object($var)) && !empty($var->getLocationDetail->name) ? $var->getLocationDetail->name : '') }}
                                    </div>
                                </td>

                                <td> 
                                    {{ \Carbon\Carbon::parse($var->created_at)->format('d-M-Y h:i:s')}}
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
            {!! $arrData->appends($_GET)->links() !!}
        </div>
    </div>
</div>
@endsection
@section('addtional_css')
@endsection
@section('jscript')
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/delete-confirm.js')}}"></script>
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
