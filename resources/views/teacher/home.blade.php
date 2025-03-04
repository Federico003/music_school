@extends('layouts.app')

@section('content')
    <div id="app">
        <div class="container-fluid">
            <div class="block-header">
                <h2>@{{ title }}
                    <small>@{{ smallTitle }}</small>
                </h2>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    const { createApp } = Vue;

    var app = createApp({
        data() {
            return {
                title: '',
                smallTitle: 'Home dell\'insegnante',
                studentCount: 0, // Inizializza con 0
                activeStudentCount: 0 // Studenti attivi
            }
        },

        mounted() {
            this.startup();
        },

        methods: {
            startup: function() {
                this.title = "Home";
                this.fetchStudentCount(); // Chiama la funzione per ottenere il numero di studenti
            },

            fetchStudentCount: function() {
                // Fai una richiesta GET per ottenere il numero di studenti totali
                axios.get('/api/student-count') 
                    .then(response => {
                        console.log(response.data); // Verifica cosa contiene la risposta
                        this.studentCount = response.data.count; // Imposta il numero di iscritti totali
                    })
                    .catch(error => {
                        console.error('Errore nella richiesta API per studenti totali:', error);
                    });

                // Fai una richiesta GET per ottenere il numero di studenti attivi
                axios.get('/api/active-student-count') 
                    .then(response => {
                        console.log(response.data); // Verifica cosa contiene la risposta
                        this.activeStudentCount = response.data.count; // Imposta il numero di studenti attivi
                    })
                    .catch(error => {
                        console.error('Errore nella richiesta API per studenti attivi:', error);
                    });
            }

        },
    }).mount('#app');
</script>

@endsection