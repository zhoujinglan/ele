@foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(session()->has($msg))
        {{--<div class="flash-message">--}}
            {{--<p class="alert alert-{{ $msg }}">--}}
                {{--{{ session()->get($msg) }}--}}
            {{--</p>--}}
        {{--</div>--}}


        <div class="alert alert-{{ $msg }} alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session()->get($msg) }}
        </div>

    @endif
@endforeach