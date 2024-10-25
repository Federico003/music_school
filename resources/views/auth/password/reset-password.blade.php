@extends('layouts.forgot')


@section('content')
    <div class="fp-box">
        <div class="logo">
            <img src="/images/logo@2x.png" width="100%" alt="{{ config('app.name') }}" />
        </div>
        <div class="card">
            <div class="body">
                <form id="reset_password" method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    @if (Session::has('message'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif

                    <div class="msg">
                        Inserire una nuova Password
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <x-input :name="'email'" :required="true" :placeholder="'Indirizzo Email'" :value="old('email')"/>
                        </div>
                    </div>

					<div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" style="width: 90%" name="password" minlength="6" placeholder="Password *" required>
                            <i class="material-icons"  id="hide" onclick="ShowHide()" hidden="hidden"  style="float: right">visibility_off</i>
                            <i class="material-icons"  id="show" onclick="ShowHide()"  style="float: right">visibility</i>

                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password_confirmation" type="password" class="form-control @error('password') is-invalid @enderror" style="width: 90%" name="password_confirmation" minlength="6" placeholder="Conferma Password *" required>
                            <i class="material-icons"  id="hideConfirm" onclick="ShowHideConf()" hidden="hidden"  style="float: right">visibility_off</i>
                            <i class="material-icons"  id="showConfirm" onclick="ShowHideConf()"  style="float: right">visibility</i>

                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <button class="btn waves-effect btn-block btn-primary waves-effect" type="submit">{{ __('Resetta la Password!') }}</button>

				</form>
			</div>
		</div>
	</div>
@endsection

<!-- Js for visibility function -->
<script type="text/javascript">
    function ShowHide (){
        var x = document.getElementById("password");
        if (x.type === "password"){
            x.type = "text";
            document.getElementById('hide').style.display = "inline-block";
            document.getElementById('show').style.display = "none";
        }
        else{
            x.type = "password";
            document.getElementById('hide').style.display = "none";
            document.getElementById('show').style.display = "inline-block";
        }
    }

    function ShowHideConf (){
        var y = document.getElementById("password_confirmation");
        if (y.type === "password"){
            y.type = "text";
            document.getElementById('hideConfirm').style.display = "inline-block";
            document.getElementById('showConfirm').style.display = "none";
        }
        else{
            y.type = "password";
            document.getElementById('hideConfirm').style.display = "none";
            document.getElementById('showConfirm').style.display = "inline-block";
        }
    }
</script>
