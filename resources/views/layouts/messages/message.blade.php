<div name = "layouts.messages.message" id="layouts.messages.message" style="margin-top:3px; margin-bottom:3px">
    @if(Session::get('error'))
        <p class="alert alert-danger" style="color: black">
            {{ Session::get('error') }}
        </p>
    @endif
    @if(Session::has('message'))
        <p class="alert alert-info">
            {{ Session::get('message') }}
        </p>
    @endif


    @if(Session::has('messages'))
        @foreach (Session::get('messages') as $message)
                <p class="alert alert-info" style="margin-bottom: 1px">
                    {{$message}}
                </p>
        @endforeach
    @endif
    @if(Session::has('errors'))
        @foreach (Session::get('errors') as $error)
            <p class="alert alert-danger" style="color: black; margin-bottom: 1px">
                {{$error}}
            </p>
        @endforeach
    @endif
</div>
