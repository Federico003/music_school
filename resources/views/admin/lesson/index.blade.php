@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar');

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'it',
                        firstDay: 1, // Imposta il primo giorno della settimana a lunedì
                        height: '80%',
                        expandRows: true,
                        slotMinTime: '08:00',
                        slotMaxTime: '20:00',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                        },
                        buttonText: {
                            today: 'Oggi',
                            month: 'Mese',
                            week: 'Settimana',
                            day: 'Giorno',
                            list: 'Elenco'
                        },
                        initialView: 'dayGridMonth',
                        navLinks: true,
                        editable: true,
                        selectable: true,
                        nowIndicator: true,
                        dayMaxEvents: true,
                        events: function(info, successCallback, failureCallback) {
                            fetch('/admin/lesson/events')
                                .then(response => response.json())
                                .then(data => {
                                    successCallback(data);
                                })
                                .catch(error => {
                                    failureCallback(error);
                                });
                        },

                        slotLabelFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        },
                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        },
                        timeZone: 'local',
                        dayHeaderFormat: {
                            weekday: 'long'
                        },
                        titleFormat: {
                            year: 'numeric',
                            month: 'long'
                        },

                        viewDidMount: function(view) {
                            // Controlla la vista corrente e imposta il formato corretto per i giorni
                            if (calendar.view.type === 'timeGridWeek' || calendar.view.type === 'timeGridDay') {
                                calendar.setOption('dayHeaderFormat', {
                                    weekday: 'long',
                                    day: '2-digit'
                                });
                            } else {
                                calendar.setOption('dayHeaderFormat', {
                                    weekday: 'long'
                                });
                            }
                        }
                    });

                    calendar.render();
                });
            </script>


            <style>
                html,
                body {
                    overflow: auto;
                    /* Consente lo scroll in caso di contenuti troppo grandi */
                    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                    font-size: 14px;
                    margin: 0;
                    padding: 0;
                }

                #calendar-container {
                    width: 100%;
                    /* Assicurati che il contenitore abbia larghezza al 100% */
                    height: 80vh;
                    /* Imposta l'altezza al 80% dell'altezza della finestra */
                    margin: 0 auto;
                    /* Centra il calendario nella pagina */
                }

                .fc-header-toolbar {
                    padding-top: 1em;
                    padding-left: 1em;
                    padding-right: 1em;
                }

                #calendar {
                    height: 100%;
                    /* Imposta il calendario per occupare tutto lo spazio disponibile */
                }

                .fc-daygrid-day-number,
                .fc-col-header-cell-cushion {
                    text-transform: capitalize !important;
                }

                .fc-toolbar-title {
                    text-transform: capitalize !important;
                }


                /* Cambia il colore dei bottoni per il cambio vista */
                .fc-button-group .fc-button {
                    background-color: #ee5c08;
                    /* Colore di sfondo personalizzato */
                    color: white;
                    /* Colore del testo */
                    border: 1px solid #ee5c08;
                    /* Bordo personalizzato */
                }

                .fc-button-group .fc-button:hover {
                    background-color: #ee5c08;
                    /* Colore di sfondo quando ci passi sopra */
                    color: white;
                }

                /* Cambia il colore delle frecce per il cambio mese */
                .fc-prev-button,
                .fc-next-button {
                    background-color: #FF9800;
                    /* Colore di sfondo delle frecce */
                    color: white;
                    /* Colore del testo */
                    border: 1px solid #F57C00;
                    /* Bordo personalizzato delle frecce */
                }

                .fc-prev-button:hover,
                .fc-next-button:hover {
                    background-color: #F57C00;
                    /* Colore di sfondo delle frecce quando ci passi sopra */
                    color: white;
                }

                /* Cambia il colore del titolo nella barra del calendario */
                .fc-toolbar-title {
                    color: #2196F3;
                    /* Colore del titolo del calendario */
                }

                /* Cambia il colore del bottone "Oggi" */
                .fc-today-button {
                    background-color: #2196F3;
                    color: white;
                }

                .fc-today-button:hover {
                    background-color: #1976D2;
                }
            </style>


            <div id='calendar-container'>
                <div id='calendar'></div>
            </div>
        </div>
    </div>
@endsection
