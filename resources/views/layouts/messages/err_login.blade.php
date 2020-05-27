<div name = "layouts.messages.err_login">
    <?php
        $err_login = Session::get('err_login');
    ?>
    @if(isset($err_login))
        <p class="alert alert-danger" style="color: black">
            {{$err_login}}
        </p>
    @endif
</div>
