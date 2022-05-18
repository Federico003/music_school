@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>Gestione Utenti
            <small>Modifica utente esistente</small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Modifica utente</h2>
                </div>
                <div class="body">

                    @if ($errors->any())
                        <div id="errors-container" class="alert alert-danger">
                            <span>Si sono verificati degli errori, per favore controlla e correggi</span>
                            @php /*<ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ ucfirst($error) }}</li>
                                @endforeach
                            </ul>*/ @endphp
                        </div>
                    @endif
            
                    <form action="{{ URL::action([App\Http\Controllers\Admin\UserController::class, 'update'], $user->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="nome">Nome</label>
                                        <div class="form-group">
                                            <div class="form-line {{ $errors->has('name') ? 'error' : '' }}">
                                                <input type="text" class="form-control" name="name" value="{{ old('name') ?? $user->name ?? '' }}" maxlength="255">
                                            </div>
                                            @if ($errors->has('name'))
                                                <label class="error">{{ $errors->first('name') }}</label>
                                            @endif                                                    
                                            <div class="help-info">Nome utente</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="email">Email</label>
                                        <div class="form-group">
                                            <div class="form-line {{ $errors->has('email') ? 'error' : '' }}">
                                                <input type="text" class="form-control" name="email" value="{{ old('email') ?? $user->email ?? '' }}" maxlength="255">
                                            </div>
                                            @if ($errors->has('email'))
                                                <label class="error">{{ $errors->first('email') }}</label>
                                            @endif        
                                            <div class="help-info">Email utente</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="email">Nuova password</label>
                                        <div class="form-group">
                                            <div class="form-line {{ $errors->has('password') ? 'error' : '' }}">
                                                <input type="password" class="form-control" name="password" value="" maxlength="255">
                                            </div>
                                            @if ($errors->has('password'))
                                                <label class="error">{{ $errors->first('password') }}</label>
                                            @endif
                                            <div class="help-info">Nuova password utente</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label>Ruolo</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <x-admin.select :name="'role'" :options="$roles" :check="$user->roles->first()->name" />
                                            </div>
                                            <div class="help-info">Ruolo utente</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="submit" class="btn waves-effect btn-primary" value="Salva" />
                                    <a href="{{ url()->previous() }}" class="btn waves-effect btn-default ml-2">Indietro</a>
                                    
                                    @if ($user->id != Auth::user()->id)
                                        <x-admin.ajax-del-button-link :url="'admin/user'" :resource="$user" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
