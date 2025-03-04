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
                    <i class="material-icons">people</i>
                </div>
                <div class="content">
                    <div class="text">ISCRITTI TOTALI</div>
                    <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20">
                        @{{ studentCount }}
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">person</i>
                </div>
                <div class="content">
                    <div class="text">ISCRITTI ATTIVI</div>
                    <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20">
                        @{{ activeStudentCount }}
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">euro_symbol</i>
                </div>
                <div class="content">
                    <div class="text">PAGAMENTI</div>
                    <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20">
                        <!-- @{{ activeStudentCount }} -->
                    </div>
                    
                </div>
            </div>
        </div>


        <div class="row clearfix">
            <!-- Pie Chart -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>PIE CHART</h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else here</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div id="pie_chart" class="flot-chart"></div>
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