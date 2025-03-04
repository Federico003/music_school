@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-start align-items-start min-vh-100 pt-4 ps-4">
        <div class="row w-100">
            <!-- Card per i dettagli dell'utente -->
            <div class="col-md-6">
                <div class="card shadow-lg border-0 p-4 rounded-4 me-4">
                    <div class="card-header bg-primary text-white text-center py-3 rounded-4">
                        <h3 class="mb-0 fw-bold">Dettagli Utente</h3>
                    </div>
                    <div class="card-body p-4">
                        <!-- Tabella moderna con Bootstrap -->
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th class="text-muted">ID</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nome</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Ruolo</th>
                                    <td>{{ $user->roles->first()->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Stato</th>
                                    <td>
                                        <span class="badge py-2 px-3 rounded-pill" style="background-color: {{ $user->status ? '#28a745' : '#dc3545' }};">
                                            {{ $user->status ? 'Abilitato' : 'Disabilitato' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Creato il</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Ultima modifica</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center bg-light rounded-4 border-0 py-4">
                    </div>
                </div>
                <form action="{{ route('admin.user.index') }}" method="GET" class="text-center">
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary mx-auto d-block" style="width: 140px; height: 70px; font-size: 18px;">TORNA ALLA </br>LISTA</button>
                    </div>
                </form>
            </div>

            <!-- Form per la selezione dei corsi (solo per insegnanti) -->
            @if($user->roles->first()->name == 'teacher')
                <div class="col-md-6">
                    <form action="{{ route('admin.users.storeCourses', $user->id) }}" method="POST" class="text-center">
                        @csrf
                        <h1>Seleziona Corsi</h1>
                        <select id='callbacks' name='courses[]' multiple='multiple' class="form-control mx-auto" style="width: 100%; max-width: 300px;">
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                    @if(in_array($course->id, $assignedCourses)) selected @endif>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        <!-- Bottone centrato e più grande -->
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary mx-auto d-block" style="width: 120px; height: 40px; font-size: 18px;">SALVA</button>
                        </div>
                    </form>

                    <!-- Script per il multi-select -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
                    <script src="/plugins/multi-select/js/jquery.multi-select.js"></script>
                    <script type="text/javascript">
                        $('#callbacks').multiSelect({
                            afterSelect: function(values){
                                // Itera su tutti i valori selezionati
                                values.forEach(function(value){
                                    // Trova l'opzione con l'id corrispondente e prendi il testo (nome corso)
                                    var courseName = $('#callbacks option[value="' + value + '"]').text();
                                    alert("Hai selezionato il corso di" + courseName);
                                });
                            },
                            afterDeselect: function(values){
                                // Itera su tutti i valori deselezionati
                                values.forEach(function(value){
                                    // Trova l'opzione con l'id corrispondente e prendi il testo (nome corso)
                                    var courseName = $('#callbacks option[value="' + value + '"]').text();
                                    alert("Hai deselezionato il corso di" + courseName);
                                });
                            }
                        });
                    </script>

                </div>
            @endif


            @if($user->roles->first()->name == 'student')
            <div class="col-md-6">
                <form action="{{ route('admin.users.updateCourseEnrollments', $user->id) }}" method="POST" class="text-center">
                    @csrf
                    <h1>Seleziona Corsi</h1>
                    <select class="form-multi-select" id="multiple-select-counter" name="courses[]" multiple>
                        @foreach($courses as $course)
                            <optgroup label="{{ $course->name }}">
                                @foreach($course->teachers as $teacher)
                                    <option value="{{ $course->id }}|{{ $teacher->id }}" @if(in_array($course->id . '|' . $teacher->id, $assignedCourses)) selected @endif>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </optgroup>
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

            @endif



        </div>
    </div>
@endsection
