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
                    <div class="profile-header" style="bg-color: orange;">&nbsp;</div>
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
                        <a href="{{ route('teacher.student.index') }}" class="btn btn-primary btn-xl ">
                            <i class="material-icons">undo</i><span>INDIETRO</span></a>
                    </div>
                    
                </div>
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="card">
                    <div class="body">
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation"><a href="#view_lessons" aria-controls="settings" role="tab" data-toggle="tab">Visualizza Lezioni</a></li>
                               
                                <li role="presentation"><a href="#add_lessons" aria-controls="settings" role="tab" data-toggle="tab">Aggiungi Lezioni</a></li>
                                
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in" id="view_lessons" style="align: center;">

                                    <style>
                                        .modal-backdrop {
                                            opacity: 0.2 !important; /* Regola l'opacità dello sfondo */
                                        }

                                        .modal {
                                            background-color: rgba(0, 0, 0, 0.2); /* Aggiungi uno sfondo semi-trasparente al modal */
                                        }
                                        /* Contenitore del select */
                                        .select-container {
                                            display: flex; /* Usa Flexbox */
                                            justify-content: center; /* Centra orizzontalmente */
                                            align-items: center; /* Centra verticalmente */
                                            width: 100%; /* Assicurati che il contenitore occupi tutta la larghezza */
                                            padding: 10px;
                                            margin: 0 auto; /* Centrato orizzontalmente nel body o contenitore */
                                        }

                                        /* Stile per il select */
                                        select {
                                            width: 50%; /* Imposta la larghezza del select per farlo essere più grande */
                                            padding: 10px;
                                            font-size: 16px;
                                            border-radius: 5px;
                                            border: 1px solid #007bff;  /* Colore bordo */
                                            background-color: #f4f4f4;  /* Sfondo chiaro */
                                            color: #333;
                                            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                            appearance: none;
                                            -webkit-appearance: none;
                                            -moz-appearance: none;
                                            cursor: pointer;
                                        }
                                    </style>

                                    <!-- Form per selezionare il corso -->
                                    <form class="form-horizontal" method="POST" action="{{ route('teacher.student.lessons', $user->id) }}">
                                        @csrf
                                        <div class="select-container">
                                            <div class="col-sm-10">
                                                <label for="course_select">Corsi assegnati</label>
                                                <select id="course_select" class="form-control" name="course_select">
                                                    <option value="" disabled selected>Seleziona un corso</option>
                                                    @foreach ($finalCourses as $course)
                                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                    @endforeach
                                                </select>
                                                
                                            </div>
                                        </div>

                                        <!-- Bottone Submit -->
                                        <!--<div class="form-group">
                                            <div class="col-sm-offset-6 col-sm-9">
                                                <button type="submit" class="btn btn-danger">SALVA</button>
                                            </div>
                                        </div>-->
                                    </form>

                                    <!-- Area dove verranno mostrate le lezioni -->
                                    <div id="lessons"></div>




                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="add_lessons" style="display: flex; justify-content: center; align-items: center;">
                                    <form action="{{ route('teacher.student.lessonsStore', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="course_select">Corso</label>
                                            <select id="course_select" class="form-control" name="course_id" required>
                                                @foreach ($finalCourses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="lessonDate">Data della Lezione:</label>
                                            <input type="date" id="lessonDate" class="form-control" name="day" required>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="lessonTime">Orario della Lezione:</label>
                                            <input type="time" id="lessonTime" class="form-control" name="time" required>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="lessonDuration">Durata della Lezione (minuti):</label>
                                            <input type="number" id="lessonDuration" class="form-control" name="duration" required>
                                        </div>
                                    
                                        <button type="submit" class="btn btn-primary">Aggiungi Lezione</button>
                                    </form>
                                    
                                </div>
                                
                                
                                
                                


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

function handleCourseSelectChange() {
    var studentId = {{ $user->id }};
    var courseId = $(this).val();

    console.log("ID Studente:", studentId);
    console.log("ID Corso selezionato:", courseId);

    if (!courseId) {
        console.warn("Nessun corso selezionato.");
        return;
    }

    $.ajax({
        url: '{{ route('teacher.student.lessons', $user->id) }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            course_select: courseId,
            student_id: studentId
        },
        success: function(response) {
            console.log("Risposta AJAX:", response);
            $('#lessons').html(response);
            loadCourseSelect();
        },
        error: function(xhr, status, error) {
            console.error("Errore nella richiesta AJAX: ", error);
            console.error("Dettagli dell'errore:", xhr.responseText);
            alert("Si è verificato un errore durante il caricamento delle lezioni.");
        }
    });
}
        $(document).ready(function() {
            // Gestione del cambio nel select per inviare automaticamente il form
            $('#course_select').change(function() {
                var studentId = {{ $user->id }}; // Assicurati che il $user->id sia disponibile in JS
                var courseId = $(this).val(); // Ottieni il valore selezionato nel select
        
                if (!courseId) {
                    console.warn("Nessun corso selezionato.");
                    return;
                }
    
                // Invia la richiesta AJAX solo se un corso è selezionato
                $.ajax({
                    url: '{{ route('teacher.student.lessons', $user->id) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        course_select: courseId,
                        student_id: studentId
                    },
                    success: function(response) {
                        // Aggiorna l'area delle lezioni con la risposta ricevuta
                        $('#lessons').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Errore nella richiesta AJAX: ", error);
                        alert("Si è verificato un errore durante il caricamento delle lezioni.");
                    }
                });
            });
        });
    </script>


<!-- Modal per la modifica della lezione -->
<div class="modal" id="editLessonModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifica Lezione</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editLessonForm">
                    @csrf <!-- Aggiungi il token CSRF -->
                    <div class="form-group">
                        <label for="lessonDate">Data</label>
                        <input type="date" id="lessonDate" name="lessonDate" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="lessonTime">Orario</label>
                        <input type="time" id="lessonTime" name="lessonTime" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="lessonDuration">Durata (minuti)</label>
                        <input type="number" id="lessonDuration" name="lessonDuration" class="form-control">
                    </div>
                    <input type="hidden" id="lessonId" name="lessonId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="updateLesson()">Salva</button>
            </div>
        </div>
    </div>
</div>



<script>

    
    // Funzione per aprire il modal con i dati della lezione
    function editLesson(lessonId) {
        $.ajax({
            url: '/teacher/lesson/get-lesson-details/' + lessonId,
            method: 'GET',
            success: function(response) {
    if (response.lesson) {
        // Ricostruisci il modal con i nuovi dati
        var modalContent = `
            <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modifica Lezione</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editLessonForm">
                @csrf <!-- Aggiungi il token CSRF -->
                <div class="form-group">
                    <label for="lessonDate">Data</label>
                    <input type="date" id="lessonDate" name="lessonDate" class="form-control" value="${response.lesson.day}">
                </div>
                <div class="form-group">
                    <label for="lessonTime">Orario</label>
                    <input type="time" id="lessonTime" name="lessonTime" class="form-control" value="${response.lesson.time.substring(0, 5)}">
                </div>
                <div class="form-group">
                    <label for="lessonDuration">Durata (minuti)</label>
                    <input type="number" id="lessonDuration" name="lessonDuration" class="form-control" value="${response.lesson.duration}">
                </div>
                <input type="hidden" id="lessonId" name="lessonId" value="${response.lesson.id}">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
            <button type="button" class="btn btn-primary" onclick="updateLesson()">Salva</button>
        </div>
    </div>
</div>
        `;

        $('#editLessonModal').html(modalContent).modal('show');

        // Previeni l'invio tradizionale del form
        $('#editLessonForm').on('submit', function(e) {
            e.preventDefault(); // Previeni il reset del form

            // Qui puoi gestire l'invio del form tramite AJAX
            var formData = $(this).serialize(); // Serializza i dati del form

            $.ajax({
                url: '/update-lesson', // Endpoint per aggiornare la lezione
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#editLessonModal').modal('hide'); // Chiudi il modal
                    $('#lessons').html(response.updatedLessonsHtml); // Aggiorna la lista delle lezioni
                },
                error: function(xhr, status, error) {
                    console.error("Errore nell'aggiornamento della lezione:", error);
                    alert("Si è verificato un errore durante l'aggiornamento.");
                }
            });
        });
    }
},
            error: function(xhr, status, error) {
                console.error("Errore nel recuperare i dettagli della lezione:", error);
                alert("Si è verificato un errore.");
            }
        });
    }

    // Funzione per aggiornare la lezione
    function updateLesson() {
        event.preventDefault();

        // Ottieni l'ID del corso selezionato e l'ID dello studente
        var courseId = $('#course_select').val();
        var studentId = {{ $user->id }}; // Assicurati che $user->id sia disponibile in JS

        // Serializza i dati del form e aggiungi l'ID del corso e dello studente
        var formData = $('#editLessonForm').serialize() + '&course_id=' + courseId + '&student_id=' + studentId;

        console.log("Dati inviati:", formData); // Logga i dati inviati

        // Invia i dati tramite AJAX
        $.ajax({
            url: '/teacher/lesson/update-lesson', // Usa l'URL completo
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Invia il token CSRF nell'header
            },
            success: function(response) {
                $('#editLessonModal').modal('hide'); // Chiudi il modal
                $('#lessons').html(response.updatedLessonsHtml); // Aggiorna la tabella delle lezioni
                alert(response.message); // Mostra un messaggio di successo
            },
            error: function(xhr, status, error) {
                console.error("Errore nell'aggiornamento della lezione:", error);
                console.error("Dettagli dell'errore:", xhr.responseText); // Logga la risposta del server
                alert("Si è verificato un errore durante l'aggiornamento. Controlla la console per ulteriori dettagli.");
            }
        });
    }

function deleteLesson(lessonId) {
    if (!confirm('Sei sicuro di voler eliminare questa lezione?')) {
        return; // Annulla l'operazione se l'utente non conferma
    }

    // Ottieni l'ID del corso selezionato e l'ID dello studente
    var courseId = $('#course_select').val();
    var studentId = {{ $user->id }}; // Assicurati che $user->id sia disponibile in JS

    // Invia la richiesta AJAX per eliminare la lezione
    $.ajax({
        url: '/teacher/lesson/delete-lesson', // Endpoint per l'eliminazione
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}', // Token CSRF
            lesson_id: lessonId, // ID della lezione da eliminare
            course_id: courseId, // ID del corso
            student_id: studentId // ID dello studente
        },
        success: function(response) {
            if (response.success) {
                alert(response.message); // Mostra un messaggio di successo
                loadLessonsForCourse(courseId); // Ricarica le lezioni del corso selezionato
            } else {
                alert(response.error); // Mostra un messaggio di errore
            }
        },
        error: function(xhr, status, error) {
            console.error("Errore nell'eliminazione della lezione:", error);
            console.error("Dettagli dell'errore:", xhr.responseText); // Logga la risposta del server
            alert("Si è verificato un errore durante l'eliminazione. Controlla la console per ulteriori dettagli.");
        }
    });
}

function loadLessonsForCourse(courseId) {
    var studentId = {{ $user->id }};

    $.ajax({
        url: '{{ route('teacher.student.lessons', $user->id) }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            course_select: courseId,
            student_id: studentId
        },
        success: function(response) {
            $('#lessons').html(response); // Aggiorna la tabella delle lezioni
        },
        error: function(xhr, status, error) {
            console.error("Errore nel caricamento delle lezioni:", error);
            alert("Si è verificato un errore durante il caricamento delle lezioni.");
        }
    });
}

    // Previeni l'invio tradizionale del form
    $(document).ready(function() {
        $('#editLessonForm').on('submit', function(e) {
            e.preventDefault(); // Previeni il reset del form
            updateLesson(); // Chiama la funzione per aggiornare la lezione
        });
    });
</script>
  
{{--<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>--}}

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Includi Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

<!-- Includi Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  
    
    

</section>
@endsection
