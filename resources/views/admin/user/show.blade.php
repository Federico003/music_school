@extends('layouts.app')
@section('content')

<!-- Multi Select Css -->
<link href="{{ asset('plugins/multi-select/css/multi-select.css') }}" rel="stylesheet">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css') }}">

<!-- Latest compiled and minified JavaScript -->
<script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js') }}"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js') }}"></script>

<section class="content" style="margin-bottom: 0px;">
    <div class="container-fluid" style="margin-top: 0px;">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-4">
                <div class="card profile-card">
                    <div class="profile-header" style="bg-color:">&nbsp;</div>
                    <div class="profile-body">
                        <div class="image-area">
                            <img src="{{ asset('images/initials/' . $user->initial . '.png') }}" 
                            onError="this.onerror=null;this.src='{{ asset('images/initials/default.png') }}';" 
                            width="136"
                            height="136"
                            alt="Immagine di profilo">

                            {{--<img src="{{ asset('/images/profile_photo.jpg') }}" width="136" height="136" alt="AdminBSB - Profile Image" />--}}
                        </div>
                        <div class="content-area">
                            <h3>{{ $user->name }}</h3>
                            <p>{{ \App\Models\User::getTranslatedRole($user->roles->first()->name) }}</p>

                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                <span>E-mail</span>
                                <span>{{ $user->email }}</span>
                            </li>
                            <li>
                                <span>Stato</span>
                                <span class="badge py-2 px-3 rounded-pill" style="background-color: {{ $user->status ? '#28a745' : '#dc3545' }};">
                                    {{ $user->status ? 'Abilitato' : 'Disabilitato' }}
                            </li>
                            <li>
                                <span>Creato il</span>
                                <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                            </li>
                            <li>
                                <span>Aggiornato il</span>
                                <span>{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                            </li>
                            
                        </ul>
                    </div>
                </div>

                <div class="card card-about-me">
                    <div class="header" style="text-align: center;">
                        @if($user->roles->first()->name == 'teacher')
                            <a href="{{ route('admin.teachers') }}" class="btn btn-primary btn-xl ">
                                <i class="material-icons">undo</i><span>INDIETRO</span></a>
                        @elseif($user->roles->first()->name == 'student')
                            <a href="{{ route('admin.students') }}" class="btn btn-primary btn-xl ">
                                <i class="material-icons">undo</i><span>INDIETRO</span></a>
                        @else
                            <a href="{{ route('admin.user.index') }}" class="btn btn-primary btn-xl ">
                                <i class="material-icons">undo</i><span>INDIETRO</span></a>
                        @endif
                    </div>
                    
                </div>
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="card">
                    <div class="body">
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation"><a href="#profile_settings" aria-controls="settings" role="tab" data-toggle="tab">Modifica Utente</a></li>
                               {{-- <li role="presentation"><a href="#change_password_settings" aria-controls="settings" role="tab" data-toggle="tab">Cambio Password</a></li> --}}
                                <li role="presentation"><a href="#choose_courses_settings" aria-controls="settings" role="tab" data-toggle="tab">Selezione corsi</a></li>
                                {{--<li role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>--}}
                                
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="home">
                                    
                                </div>
                                <div role="tabpanel" class="tab-pane fade in" id="profile_settings">
                                    <form class="form-horizontal" method="POST" action="{{ route('admin.user.update', $user->id) }}">
                                        @csrf
                                        @method('PATCH')
                                    
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                    
                                        <!-- Nome -->
                                        <div class="form-group">
                                            <label for="Name" class="col-sm-2 control-label">Nome</label>
                                            <div class="col-sm-10">
                                                <x-admin.input-text
                                                    :name="'name'"
                                                    :value="old('name') ?? $user->name"
                                                    :description="'Nome dell\'utente'"
                                                    :required="true"
                                                    :class="'form-control'" />
                                            </div>
                                        </div>
                                    
                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="Email" class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                                <x-admin.input-email
                                                    :name="'email'"
                                                    :value="old('email') ?? $user->email"
                                                    :description="'Email dell\'utente'"
                                                    :required="true" />
                                            </div>
                                        </div>
                                    
                                        <!-- Stato (Switch) -->
                                        <div class="form-group">
                                            <label for="Status" class="col-sm-2 control-label">Stato</label>
                                            <div class="col-sm-10">
                                             {{--<x-admin.switch 
                                                    :name="'status'" 
                                                    :check="old('status') ?? $user->status" 
                                                    :description="'Stato dell utente'" 
                                                    :required="true" /> --}}

                                                   <x-admin.select
                                                    :name="'status'"
                                                    :options="$status"
                                                    :description="'Stato dell utente'"
                                                    :check="old('status') ?? $user->status"
                                                    :required="true" />
                                            </div> 
                                        </div>
                                    
                                        <!-- Ruolo -->
                                        <div class="form-group">
                                            <label for="Role" class="col-sm-2 control-label">Ruolo</label>
                                            <div class="col-sm-10">
                                                <x-admin.select
                                                    :name="'role'"
                                                    :options="$roles"
                                                    :description="'Ruolo dell\'utente'"
                                                    :check="old('role') ?? $user->roles->first()->name"
                                                    :required="true" />
                                            </div>
                                        </div>


                                        <!-- Password -->
                                        <div class="form-group">
                                            <label for="Password" class="col-sm-2 control-label">Password</label>
                                            <div class="col-sm-10">
                                            <div class="form-group">
                                                <div class="form-line {{ $errors->has('password') ? 'error' : '' }}">
                                                    <input type="password" class="form-control" name="password"
                                                        value="" maxlength="255">
                                                </div>
                                                @if ($errors->has('password'))
                                                    <label class="error">{{ $errors->first('password') }}</label>
                                                @endif
                                                <div class="help-info">Nuova password utente</div>
                                            </div>
                                            </div>
                                        </div>
                                    
                                        <!-- Bottone Submit -->
                                        <div class="form-group">
                                            <div class="col-sm-offset-6 col-sm-9">
                                                <button type="submit" class="btn btn-danger">SALVA</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div role="tabpanel" class="tab-pane fade in" id="change_password_settings">
                                    <form id="change-password-form" class="form-horizontal" method="POST" action="{{ route('admin.user.update', $user->id) }}">
                                        @csrf
                                        @method('PATCH')
                                    
                                        <input type="hidden" name="id" value="{{ $user->id }}">

                                        <div class="form-group">
                                            <label for="NewPassword" class="col-sm-3 control-label">New Password</label>
                                            <div class="col-sm-9">
                                               
                                                    <x-admin.input-password
                                                        :name="'password'"
                                                        :value="old('password')"
                                                        :description="'Nuova password utente'"
                                                        :required="true"
                                                        :class="'form-control'" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="NewPasswordConfirm" class="col-sm-3 control-label">New Password (Confirm)</label>
                                            <div class="col-sm-9">
                                                <x-admin.input-password
                                                :name="'password_confirmation'"
                                                :value="old('password_confirmation')"
                                                :description="'Conferma la nuova password'"
                                                :required="true"
                                                :class="'form-control'" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-6 col-sm-9">
                                                <button type="submit" class="btn btn-danger">SALVA</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div role="tabpanel" class="tab-pane fade in" id="choose_courses_settings">
                                    <div class="row w-100">            

                                        

                                       {{-- --------------------------

                                        <div class="col-md-6">
                                            <form action="{{ route('admin.users.storeCourses', $user->id) }}" method="POST" class="text-center">
                                                @csrf
                                                <h1>Seleziona Corsi</h1>
                                                <select class="form-multi-select" id="multiple-select-counter" name="courses[]">
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}"
                                                            @if(in_array($course->id, $assignedCourses)) selected @endif>
                                                             {{ $course->name }}
                                                        </option>
                                                        
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-primary">SALVA</button>
                                            </form>
                                            
                                        
                                                <!-- Bottone centrato e con stile migliorato -->
                                                <div class="mt-4">
                                                    <button type="submit" class="btn btn-primary btn-lg px-4 py-2" style="font-size: 18px;">SALVA</button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Script per CoreUI Multi-Select senza CSS globale -->
                                        <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.2.0/dist/js/coreui.bundle.min.js"></script>
                            
                            --}}

                                        <!-- Form per la selezione dei corsi (solo per insegnanti) -->
                                        @if($user->roles->first()->name == 'teacher')

                                        <style>
                                            /* Selettore per il form */
                                            form {
                                                padding: 20px;
                                                background-color: #f8f9fa;
                                                border-radius: 10px;
                                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                            }
                                        
                                            /* Titolo del form */
                                            h1 {
                                                color: #343a40;
                                                font-size: 28px;
                                            }
                                        
                                            /* Selettore per la lista a discesa */
                                            .form-multi-select {
                                                width: 100%;
                                                max-width: 400px;
                                                margin: 0 auto 20px;
                                                padding: 10px;
                                                border-radius: 8px;
                                                border: 1px solid #ccc;
                                                font-size: 16px;
                                                box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.12);
                                                background-color: #fff;
                                            }
                                        
                                            .form-multi-select:focus {
                                                border-color: #007bff;
                                                box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
                                            }
                                        
                                            /* Bottone */
                                            .btn-primary {
                                                font-size: 18px;
                                                border-radius: 50px;
                                                padding: 10px 30px;
                                                transition: background-color 0.3s ease;
                                            }
                                        
                                            .btn-primary:hover {
                                                background-color: #0056b3;
                                            }
                                        </style>

                                            <div class="col-md-12">
                                                {{--<form action="{{ route('admin.users.storeCourses', $user->id) }}" method="POST" class="text-center">
                                                    @csrf        
                                                    <h3>Seleziona Corsi</h3>                                       
                                                    <select id='callbacks' name='courses[]' class="form-control show-tick selectpicker" multiple>
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}"
                                                            @if(in_array($course->id, $assignedCourses)) selected @endif>
                                                             {{ $course->name }} {{--(ID: {{ $course->id }})
                                                        </option>
                                                        
                                                    @endforeach
                                                    </select>

                                                    <!-- Bottone centrato e più grande -->
                                                    <div class="mt-3">
                                                        <button type="submit" class="btn btn-primary mx-auto d-block" style="width: 120px; height: 40px; font-size: 18px;">SALVA</button>
                                                    </div>
                                                </form>--}}

                                                <form action="{{ route('admin.users.storeCourses', $user->id) }}" method="POST" class="text-center">
                                                    @csrf
                                                    <label for="courses">Seleziona i corsi:</label>
                                                    <select name="courses[]" id="courses" multiple>
                                                        @foreach($courses as $course)
                                                            <option value="{{ $course->id }}" 
                                                                    @if(in_array($course->id, $assignedCourses)) selected @endif>
                                                                {{ $course->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit">Salva</button>
                                                </form>
                            
                                                <!-- Latest compiled and minified CSS -->
                                                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

                                                <!-- Latest compiled and minified JavaScript -->
                                                <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

                                                <!-- (Optional) Latest compiled and minified JavaScript translation files -->
                                                <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
                                                <script>
                                                $(document).ready(function() {
                                                    var selectedCourses = @json($assignedCourses); // Array PHP in formato JSON
                                                    $('#multiple-select-counter').select2().val(selectedCourses).trigger('change');
                                                });
                                                </script>                      
                                            </div>
                                        @endif
                            
                            
                                        @if($user->roles->first()->name == 'student')

                                        <style>
                                            /* Selettore per il form */
                                            form {
                                                padding: 20px;
                                                background-color: #f8f9fa;
                                                border-radius: 10px;
                                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                            }
                                        
                                            /* Titolo del form */
                                            h1 {
                                                color: #343a40;
                                                font-size: 28px;
                                            }
                                        
                                            /* Selettore per la lista a discesa */
                                            .form-multi-select {
                                                width: 100%;
                                                max-width: 400px;
                                                margin: 0 auto 20px;
                                                padding: 10px;
                                                border-radius: 8px;
                                                border: 1px solid #ccc;
                                                font-size: 16px;
                                                box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.12);
                                                background-color: #fff;
                                            }
                                        
                                            .form-multi-select:focus {
                                                border-color: #007bff;
                                                box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
                                            }
                                        
                                            /* Bottone */
                                            .btn-primary {
                                                font-size: 18px;
                                                border-radius: 50px;
                                                padding: 10px 30px;
                                                transition: background-color 0.3s ease;
                                            }
                                        
                                            .btn-primary:hover {
                                                background-color: #0056b3;
                                            }
                                        </style>

                                            <div class="col-md-12">
                                                <form action="{{ route('admin.users.updateCourseEnrollments', $user->id) }}" method="POST" class="text-center">
                                                    @csrf
                                                    <select id='callbacks' name='courses[]' class="form-control show-tick selectpicker" multiple>
                                                        @foreach($courses as $course)
                                                            <optgroup label="{{ $course->name }}">
                                                                @foreach($course->teachers as $teacher)
                                                                    @php
                                                                        $courseTeacherId = $course->id . '|' . $teacher->id;
                                                                        $isSelected = in_array($courseTeacherId, $assignedCourses);
                                                                    @endphp
                                                                    <option value="{{ $courseTeacherId }}" @if($isSelected) selected @endif>
                                                                        {{ $teacher->name }}
                                                                    </option>
                                                                    <!-- Debug: Verifica i valori -->
                                                                    <!-- Option Value: {{ $courseTeacherId }}, Selected: {{ $isSelected ? 'Yes' : 'No' }} -->
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                    <div class="mt-3">
                                                        <button type="submit" class="btn btn-primary mx-auto d-block" style="width: 120px; height: 40px; font-size: 18px;">SALVA</button>
                                                    </div>
                                                </form>
                            
                                                <!-- Includi select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Includi select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Inizializza select2 -->
<script>
    $(document).ready(function() {
        var selectedCourses = @json($assignedCourses); // Array PHP in formato JSON
        console.log('Selected Courses:', selectedCourses); // Debug: Verifica i corsi selezionati

        // Inizializza select2
        $('#callbacks').select2({
            placeholder: "Seleziona i corsi",
            allowClear: true
        });

        // Imposta i valori selezionati
        $('#callbacks').val(selectedCourses).trigger('change');
    });
</script>                    
                                            </div>
                                        @endif
                            
                            
                            
                            
                            
                            
                            
                            
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
