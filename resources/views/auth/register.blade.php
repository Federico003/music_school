@extends('layouts.registration')


@section('content')
    <div class="signup-box">
        <div class="logo">
            <img src="/images/logo@2x.png" width="100%" alt="{{ config('app.name') }}" />
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_up" method="POST" action="/register">
                    {{ csrf_field() }}

                    <div class="msg">Crea un nuovo utente</div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <x-input :name="'name'" :required="true" :placeholder="'Nome Cognome'" :value="old('name')"/>
                        </div>
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
                        <div class="form-line {{ $errors->has('password') ? 'error' : '' }}">
                            <input id="password" type="password" class="form-control" style="width: 90%" name="password" minlength="6" placeholder="Password *" required>
                            <i class="material-icons"  id="hide" onclick="ShowHide()" hidden="hidden"  style="float: right">visibility_off</i>
                            <i class="material-icons"  id="show" onclick="ShowHide()"  style="float: right">visibility</i>
                        </div>
                        @if ($errors->has('password'))
                            <label class="error">{{ $errors->first('password') }}</label>
                        @endif
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line {{ $errors->has('password') ? 'error' : '' }}">
                            <input id="password_confirmation" type="password" class="form-control" style="width: 90%" name="password_confirmation" minlength="6" placeholder="Conferma Password *" required>
                            <i class="material-icons"  id="hideConfirm" onclick="ShowHideConf()" hidden="hidden"  style="float: right">visibility_off</i>
                            <i class="material-icons"  id="showConfirm" onclick="ShowHideConf()"  style="float: right">visibility</i>
                        </div>
                        @if ($errors->has('password'))
                            <label class="error">{{ $errors->first('password') }}</label>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-blue">
                        <label for="terms">Ho letto e accetto <a href="javascript:void(0);">i termini d'uso</a>.</label>
                    </div>

                    <button class="btn waves-effect btn-block btn-primary waves-effect" type="submit">{{ __('Registrati') }}</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="/login" style="color: gray">Sei già iscritto?</a>
                    </div>

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
