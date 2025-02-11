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
                        <a href="{{ route('admin.user.index') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                            <i class="fas fa-arrow-left me-2"></i>Torna alla lista
                        </a>
                    </div>
                    
                </div>
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
        </div>
    </div>
@endsection
