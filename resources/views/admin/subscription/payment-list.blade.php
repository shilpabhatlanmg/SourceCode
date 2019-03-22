@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">

    @include('common.admin.breadcrumb')

    
    <div class="tables">

        <h4>Card Detail</h4>

        <div class="table-responsive bs-example widget-shadow">
            @include('common.admin.flash-message')
            <div class="table-responsive">
                <table class="table table-bordered table-condensed" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Card Number</th>
                            <th>Brand</th>
                            <th>Country</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>

                        @if(!empty($object_repsonse) && is_object($object_repsonse) && count($object_repsonse) > 0)
                        @php
                        $i=1;
                        @endphp
                        @foreach($object_repsonse->data as $key => $var)

                        @php 
                        
                            $encryptId = \Crypt::encryptString($var->id);
                            $encryptCustId = \Crypt::encryptString($var->customer);

                            $defaultId = \App\Helpers\Helper::getDefaultCardId($var->id, $var->customer);

                            
                        @endphp

                        <tr class="trCate">
                            <td>{{ $i }}</td>
                            <td>{{ !empty($var) && is_object($var) && !empty($var->last4) ? '**** **** **** '.$var->last4 : '' }}</td>
                            <td>{{ !empty($var) && is_object($var) && !empty($var->brand) ? $var->brand : '' }}</td>
                            <td>{{ !empty($var) && is_object($var) && !empty($var->country) ? $var->country : '' }}</td>
                            <td>{{ !empty($var) && is_object($var) && !empty($var->exp_month) ? $var->exp_month : '' }}</td>
                            <td>{{ !empty($var) && is_object($var) && !empty($var->exp_year) ? $var->exp_year : '' }}</td>
                            <td>
                                <div class="btn-group">
                                    <span data-id="{{ $encryptId }}" cust-id="{{ $encryptCustId }}" data-cardId = "{{ $var->id }}" class="btn btn-primary btn-xs setDefaultPayment" title="Set as Default Source">
                                        <i class="fa fa-gear" aria-hidden="true">&nbsp;</i>

                                    </span>

                                    <span data-id="{{ $encryptId }}" cust-id="{{ $encryptCustId }}" class="btn btn-danger btn-xs delete_card" title="Delete">
                                        <i class="fa fa-trash-o" aria-hidden="true">&nbsp;</i>

                                    </span>
                                    &nbsp;
                                    @if($var->id == $defaultId)
                                    <span class="label label-default">Default</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @php
                        $i++;
                        @endphp
                        @endforeach

                        @else

                        <tr><td colspan="6"><center><span class="label label-danger">No Card Detail Found</span></center></td></tr>
                        @endif

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection

@section('addtional_css')
@endsection

@section('jscript')
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/delete-confirm.js')}}"></script>
@endsection