<div name = "layouts.messages.err_register">
    <?php
        $err_register = Session::get('err_register');
    ?>
    @if(isset($err_register))
        <p class="alert alert-danger" style="color: black">
            {{$err_register}}
        </p>
    @endif
</div>
