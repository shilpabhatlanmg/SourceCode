@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Subscription Creation</h4>

            <div class="table-responsive bs-example widget-shadow">
                <div class="row">
                    <button class="btn btn-default cr_btn" onclick="window.location.href ='{{ route('subscription.create') }}'">Create Subscription</button>
                </div>

                @include('common.admin.flash-message')
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@sortablelink('plan_name', 'Plan Name')</th>
                                <th>@sortablelink('people_allow', 'People Allowed')</th>
                                <th>@sortablelink('premises_allow', 'Building Allowed')</th>
                                <th>@sortablelink('duration', 'Duration')</th>
                                <th>@sortablelink('type', 'Type')</th>
                                <th>@sortablelink('price', 'Cost')</th>
                                <th>@sortablelink('status', 'Status')</th>
                                <th style="width: 143px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($arrData)>0 && !empty($arrData))
                            @foreach($arrData as $key => $var)

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

                @endphp

                <tr class="trCate">

                    <td>{{ ($arrData->currentpage()-1) * $arrData->perpage() + $key + 1 }}</td>

                    <td><div class ="nowrap">{{ !empty($var) && is_object($var) && !empty($var->plan_name) ? $var->plan_name : '' }}</div></td>

                    <td>{{ !empty($var) && is_object($var) && !empty($var->people_allow) ? $var->people_allow : '' }}</td>

                    <td>{{ !empty($var) && is_object($var) && !empty($var->premises_allow) ? $var->premises_allow : '' }}</td>

                    <td>{{ !empty($var) && is_object($var) && !empty($var->duration) ? $var->duration : '' }}</td>

                    <td>{{ !empty($var) && is_object($var) && !empty($var->type) ? ucfirst($var->type) : '' }}</td>

                    <td>{{ !empty($var) && is_object($var) && !empty($var->price) ? $var->price : '' }}</td>

                    @php
                    $encryptId = \Crypt::encryptString($var->id);
                    @endphp

                    <td>
                        <span class="label label-{{ $label }} sts" style="cursor: pointer;" data-sts="{{ $sts }}" title="click to make {{ $sts }}"  data-id="{{ !empty($var) && is_object($var) && !empty($var->id) ? $encryptId : '' }}" data-type="subs">{{ $status }}
                        </span>
                    </td>

                    <td class="actions center action-center btn-2">
                        <div class="btn-group">
                            <a href="{{ route('subscription.edit', ['id' => $encryptId]) }}" class="btn btn-primary btn-xs" title="Edit">
                                <i class="fa fa-pencil" aria-hidden="true">&nbsp;</i>

                            </a>

                            <a href="" id="{{ $encryptId }}" class="btn btn-danger btn-xs delete-record" title="Delete">
                                <i class="fa fa-trash-o" aria-hidden="true">&nbsp;</i>

                            </a>
                        </div>
                    </td>
                </tr>

                {!!
                    Form::open(
                    array(
                    'name' => 'delete-form',
                    'id' => 'delete-form-'.$encryptId,
                    'url' => 'admin/subscription/'.(!empty($var) && is_object($var) && !empty($var->id) ? $encryptId : ''),
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
                        <td colspan="9" align='center'>
                            <span class="label label-danger">No Record Found !!!</span>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{ $arrData->render() }}
</div>
</div>
</div>

@endsection

@section('addtional_css')
@endsection

@section('jscript')
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/delete-confirm.js')}}"></script>
@endsection