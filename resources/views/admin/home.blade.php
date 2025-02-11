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

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">playlist_add_check</i>
                </div>
                <div class="content">
                    <div class="text">ISCRITTI</div>
                    <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20">
                        @{{ studentCount }}
                    </div>
                    
                </div>
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
                smallTitle: 'Home dell\'admin',
                studentCount: 0 // Inizializza con 0
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
                // Fai una richiesta GET per ottenere il numero di studenti
                axios.get('/api/student-count') // Assicurati che questa sia la URL giusta
                    .then(response => {
                        console.log(response.data); // Verifica cosa contiene la risposta
                        // Imposta il numero di iscritti nella variabile 'studentCount'
                        this.studentCount = response.data.count;
                    })
                    .catch(error => {
                        console.error('Errore nella richiesta API:', error);
                    });
            }
        },
    }).mount('#app');
</script>

@endsection
