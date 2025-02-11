@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2><i class="material-icons">edit</i>Crea nuovo corso</h2>
                    </div>
                    <div class="body">

                        @if ($errors->any())
                            <div id="errors-container" class="alert alert-danger alert-dismissible">
                                <span>Si è verificato un errore: per favore controlla e correggi.</span>
                            </div>
                        @endif

                        <form action="{{ route('admin.course.store') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <x-admin.input-text :name="'name'" :label="'Nome'" :value="old('name') ?? ''"
                                                :description="'Nome del corso'" :required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <x-admin.input-text :name="'description'" :label="'Descrizione'" :value="old('description')"
                                                :description="'Descrizione del corso'" :required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary waves-effect">
                                            <i class="material-icons">done</i>
                                            <span>Salva</span>
                                        </button>

                                        <a href="{{ route('admin.course.index') }}" class="btn waves-effect btn-default ml-2">
                                            <i class="material-icons">undo</i><span>Indietro</span></a>
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
