@if(!empty($breadCrumData))
    <ol class="breadcrumb">
        @foreach($breadCrumData as $breadcrum)
            @if(!empty($breadcrum['url']))
                <li>
                    <a href="{{$breadcrum['url']}}">
                        <i class="fa {{$breadcrum['breadFaClass']}}"></i> 
                        {{$breadcrum['text']}}
                    </a>
                </li>
            @else
                <li class="active">{{$breadcrum['text']}}</li>			
            @endif
        @endforeach
    </ol>
@endif