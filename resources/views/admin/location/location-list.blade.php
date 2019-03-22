@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Building Section Creation</h4>

            <div class=" bs-example widget-shadow">

                @include('common.admin.flash-message')
                <div class="row">

                        {{ Form::open(array('method' => 'get', 'id'=>'organisation_id')) }}
                        <div class="col-md-3 col-sm-3 col-xs-6 srch-left full-wid">                                        
                            {{ Form::text('search', app('request')->input('search'), ['placeholder'=>'Search...', 'class'=>'form-control']) }}                      
                        </div>

                        <div class="col-lg-3  col-md-4 col-xs-6 col-sm-4 pd-btn full-wid2">  
                            {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
                            <a href="{{url('/admin/location')}}" class="btn btn-default">Reset</a>
                        </div>

                        @if($roles->name == \Config::get('constants.PLATFORM_ADMIN'))

                        <div class="col-md-3 col-sm-3 col-xs-6 mr-tp srch-left full-wid">
                            <div class="flter">

                                <select name="organization_id" style = "text-transform:capitalize" class="form-control organization_id chosen-select" onChange = 'this.form.submit();'>
                                    <option value=""> --filter by organization--</option>
                                    @foreach($adminorganisation as $key => $val)
                                    <option value="{{ \Crypt::encryptString($val->id) }}"<?php if (!empty(app('request')->input('organization_id')) && $val->id == \Crypt::decryptString(app('request')->input('organization_id'))) echo ' selected="selected"'; ?>>{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @endif

                        {{Form::close()}}

                        {!!
                            Form::open(
                            array(
                            'name' => 'frmsave',
                            'id' => 'admin_organization',
                            'url' => 'admin/location/create',
                            'autocomplete' => 'off',
                            'class' => 'form-horizontal',
                            'files' => false,
                            )
                            )
                            !!}

                            {{ Form::hidden('hidden_org', !empty(app('request')->input('organization_id')) ? app('request')->input('organization_id') : "") }}

                            <button type="submit" class="btn btn-default cr_btn">Create Building Section</button>

                        {{Form::close()}}

                        <!--<button class="btn btn-default cr_btn" onclick="window.location.href ='{{ route('location.create') }}'">Create Location</button>-->

                    </div>
                <div class="table-responsive">

                    <table class="table table-bordered table-condensed cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>

                                @if($roles->name == \Config::get('constants.PLATFORM_ADMIN') && empty(app('request')->input('organization_id')))

                                <th>@sortablelink('getOrganizationName.name', 'Organization Name')</th>
                                @endif
                                
                                <th>@sortablelink('getPremiseName.name', 'Building Name')</th>
                                
                                <th>@sortablelink('name', 'Building Section Name')</th>
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

                                @if($roles->name == \Config::get('constants.PLATFORM_ADMIN') && empty(app('request')->input('organization_id')))
                                
                                <td>
                                    <div class ="nowrap">
                                        {{   (!empty($var) && is_object($var) && !empty($var->getOrganizationName->name) ? $var->getOrganizationName->name : '') }}
                                    </div>

                                </td>
                                @endif
                                
                                <td>
                                    <div class ="nowrap">
                                        {{ (!empty($var) && is_object($var) && !empty($var->getPremiseName->name) ? $var->getPremiseName->name : '') }}
                                    </div>
                                </td>
                                
                                <td>
                                    <div class ="nowrap">
                                        {{ (!empty($var) && is_object($var) && !empty($var->name) ? $var->name : '') }}
                                    </div>
                                </td>

                                @php
                                    $encryptId = \Crypt::encryptString($var->id);
                                @endphp

                                <td>
                                    <span class="label label-{{ $label }} sts" style="cursor: pointer;" data-sts="{{ $sts }}" title="click to make {{ $sts }}"  data-id="{{ !empty($var) && is_object($var) && !empty($var->id) ? $encryptId : '' }}" data-type="location">{{ $status }}</span>
                                </td>

                                <td class="actions center btn-2 action-center">
                                    <div class="btn-group">
                                    <a href="{{ route('location.edit', ['id' => $encryptId]) }}" class="btn btn-primary btn-xs" title="Edit">
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
                                'url' => 'admin/location/'.(!empty($var) && is_object($var) && !empty($var->id) ? $encryptId : ''),
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
                                    <td colspan="8" align='center'>
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
