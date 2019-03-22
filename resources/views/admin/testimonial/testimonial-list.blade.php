@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Testimonial Creation</h4>

            <div class="table-responsive bs-example widget-shadow">
                <div class="row">
                    <button class="btn btn-default cr_btn" onclick="window.location.href ='{{ route('testimonial.create') }}'">Create Testimonial</button>
                </div>

                @include('common.admin.flash-message')
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Occupation/Business</th>
                                <th>@sortablelink('feedback_date', 'Feedback Date')</th>
                                <th>@sortablelink('author_rating', 'Rating')</th>
                                <th>@sortablelink('author_email', 'Email Address')</th>
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
                                <td>

                                    @php 

                                    $path = 'public/storage/admin_assets/images/author_image/' . (!empty($var) && is_object($var) && !empty($var->author_image) ? $var->author_image : '');

                                    @endphp

                                    @if(!empty($var) && is_object($var) && !empty($var->author_image) && file_exists($path))

                                    {{ Html::image($path, 'image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => (!empty($var) && is_object($var) && !empty($var->author_image) ? $var->author_image : '' ), 'class' => 'img_prvew')) }}

                                    @else

                                    {{ Html::image(asset('public/admin_assets/images/user-profile.jpg'), 'no-image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => 'Image Preview', 'class' => 'img_prvew')) }}

                                    @endif

                                </td>
                                <td>{!! $var->occupation !!}</td>
                                <td>{!! $var->feedback_date !!}</td>
                                <td>{!! $var->author_rating !!}</td>
                                <td>{!! $var->author_email !!}</td>

                                @php
                                    $encryptId = \Crypt::encryptString($var->id);
                                @endphp

                                <td>
                                    <span class="label label-{{ $label }} sts" style="cursor: pointer;" data-sts="{{ $sts }}" title="click to make {{ $sts }}"  data-id="{{ !empty($var) && is_object($var) && !empty($var->id) ? $encryptId : '' }}" data-type="testimonial">{{ $status }}</span>
                                </td>

                                <td class="actions center btn-2 action-center">
                                    <div class="btn-group">
                                    <a href="{{ route('testimonial.edit', ['id' => $encryptId]) }}" class="btn btn-primary btn-xs" title="Edit">
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
                                'url' => 'admin/testimonial/'.(!empty($var) && is_object($var) && !empty($var->id) ? $encryptId : ''),
                                'autocomplete' => 'off',
                                'class' => 'form-horizontal',
                                'style' => 'display:none',
                                'files' => false
                                )
                                )
                                !!}

                                {{ Form::hidden('id', (!empty($var) && is_object($var) && !empty($var->id) ? $encryptId : '')) }}

                                {{ Form::hidden('author_image_edit', (!empty($var) && is_object($var) && !empty($var->author_image) ? $var->author_image : '')) }}

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