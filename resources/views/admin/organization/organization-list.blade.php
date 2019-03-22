@extends('adminLayout')

@section('content')

@section('pageTitle')
{{ $title }}
@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Organization Creation</h4>

            <div class="table-responsive bs-example widget-shadow">

                @include('common.admin.flash-message')

                <div class="row">

                    {{ Form::open(array('method' => 'get')) }}
                    <div class="col-sm-2 col-md-2 col-xs-6 full-wid srch-left">
                        {{ Form::text('search', app('request')->input('search'), ['placeholder'=>'Search...', 'class'=>'form-control']) }}
                    </div>

                    <div class="col-sm-5 col-md-4 col-xs-6 full-wid2 srch-right">
                        {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
                        <a href="{{url('/admin/organization')}}" class="btn btn-default reset">Reset</a>

                    </div>

                    {!! Form::close()!!}

                    <button class="btn btn-default cr_btn" onclick="window.location.href ='{{ route('organization.create') }}'">Create Organization</button>

                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@sortablelink('name', 'Organization Name')</th>
                                <th>@sortablelink('email', 'Email')</th>
                                <th><a href="javascript:;" onclick="document.getElementById('date-of-registration').click()">Date of</a> <br />@sortablelink('created_at', ' Expiry', [], ['id' => 'date-of-registration'])</th>
                                <th>@sortablelink('status', 'Account Status')</th>
                                <th>Deactivation <br> Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            
                            @if(count($userList)>0 && !empty($userList))
                            @foreach($userList as $key => $var)

                            <?php
                            $status = '';
                            if ($var->status == 'Inactive') {
                                $status = "Inactive";
                                $label = "danger";
                                $sts = 'Active';
                            } else {
                                $status = "Active";
                                $label = "success";
                                $sts = 'Inactive';
                            }

                            ?>

                            <tr class="trCate">
                                <td data-title="#">{{ ($userList->currentpage()-1) * $userList->perpage() + $key + 1 }}</td>
                                <td data-title="Organization Name">
                                    <div class ="nowrap">
                                        {{((!empty($var) && is_object($var)) && !empty($var->name) ? $var->name : '') }}
                                    </div>
                                </td>

                                <td data-title="Email">
                                    {{ ((!empty($var) && is_object($var)) && !empty($var->email) ? $var->email : '') }}
                                </td>

                                @php
                                    $timezone = timezone_name_from_abbr("", $var->timezone*60, false);
                                @endphp

                                <td data-title="Date of Expiry">
                                    {{ (!empty($var) && is_object($var) && !empty($var->subscriptionDetail->expiry_date) ?(($var->subscriptionDetail->expiry_date != null)? \Carbon\Carbon::parse($var->subscriptionDetail->expiry_date)->setTimezone($timezone)->format('d-M-Y h:i:s'):\Carbon\Carbon::parse($var->subscriptionDetail->expiry_date)->format('d-M-Y h:i:s')) : '---') }}
                                </td>

                                @php
                                    $encyrptId = \Crypt::encryptString($var->id);
                                @endphp

                                <td data-title="Status">
                                    <span class="label label-{{ $label }} sts" style="cursor: pointer;" data-sts="{{ $sts }}"  rel-id = "{{$var->id}}"title="click to make {{ $sts }}"  data-id="{{ !empty($var) && is_object($var) && !empty($var->id) ? $encyrptId : '' }}" data-type="org">{{ $status }}</span>
                                </td>

                                <td data-title="Deactivation Reason" class="reason reasons{{$var->id}} reason{{!empty($var) && is_object($var) && !empty($var->id) ? $encyrptId : '' }}">
                                    {!! ( !empty($var) && is_object($var) && !empty($var->reason) && $var->status == 'Inactive' ? $var->reason : '---') !!}
                                </td>

                                <td data-title="Action" class="actions fr-btn center action-center">
                                    <div class="btn-group">

                                        <a href="{{ route('organization.edit', ['id' => $encyrptId, 'search' => !empty(request()->get('search')) ? request()->get('search') : '' ]) }}" class="btns br1 " title="Edit">
                                            <i class="fa fa-pencil" aria-hidden="true">&nbsp;</i>
                                        </a>

                                        <a href="" id="{{ $encyrptId }}" class="btns br2  delete-record" data-msg="org" title="Delete">
                                            <i class="fa fa-trash-o" aria-hidden="true">&nbsp;</i>
                                        </a></div><br />

                                        <div class="btn-group">
                                            <a href="{{ route('organization.show', ['id' => $encyrptId ] ) }}" class="btns br3  view-record" title="View">
                                                <i class="fa fa-eye" aria-hidden="true">&nbsp;</i>
                                            </a>

                                            <a href="javascript:;" class="btns br4 resendActivationMail"  data-id="{{ !empty($var) && is_object($var) && !empty($var->id) ? $encyrptId : '' }}" data-type="resendActivationMail" title="Resend Activation Mail">
                                                <i class="fa fa-send-o" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </td>

                                </tr>

                                {!!
                                    Form::open(
                                    array(
                                    'name' => 'delete-form',
                                    'id' => 'delete-form-'.$encyrptId,
                                    'url' => 'admin/organization/'.(!empty($var) && is_object($var) && !empty($var->id) ? $encyrptId : ''),
                                    'autocomplete' => 'off',
                                    'class' => 'form-horizontal',
                                    'style' => 'display:none',
                                    'files' => false
                                    )
                                    )
                                    !!}

                                    {{ Form::hidden('id', (!empty($var) && is_object($var) && !empty($var->id) ? $encyrptId : '')) }}

                                    {{ method_field('DELETE') }}

                                    {!! Form::close() !!}

                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="11" align='center'>
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
<!--<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/ogranization.js')}}"></script>-->
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/delete-confirm.js')}}"></script>
@endsection
