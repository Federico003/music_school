@extends('layouts.app')

@section('content')

@php
    use App\Models\User;
    $users = User::all();
@endphp

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card card-collapsable">
                    <div class="header">
                        <h2><i class="material-icons">file_upload</i>CARICAMENTO FILE</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="javascript:void(0);" class="collapsable-handler">
                                    <i class="material-icons">vertical_align_center</i>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="{{ route('admin.user.create') }}" role="button" onclick=""
                                            name="crea_nuovo" id="nuovo_utente" class=" waves-effect waves-block"
                                            value="Nuovo utente" style="font-weight: bold"><i class="material-icons">add</i> Nuovo utente</a>
                                    </li>
                                    <li><a href="{{ route('admin.user.exportToExcel') }}" role="button" onclick=""
                                            name="esporta" id="esporta" class=" waves-effect waves-block"
                                            value="Esporta file Excel" style="font-weight: bold"><i class="material-icons">file_download</i> Esporta
                                            su Excel</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>


                    <div id="dropzone" class="body body-collapsable open">
                        <form action="{{ route('admin.user.import') }}" id="fileInsert" class="dropzone" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="dz-message">
                                <div class="drag-icon-cph">
                                    <i class="material-icons">touch_app</i>
                                </div>
                                <h3>Trascina qui i file</h3>
                                <em>(Carica il file Utenti.xlsx per aggiornare l'elenco delle rotte)</em>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" accept=".xslx"  multiple />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card card-collapsable">
                                    <div class="header">
                                        <h2><i class="material-icons">people</i>Elenco degli utenti</h2>
                                        <ul class="header-dropdown m-r--5">
                                            <li>
                                                <a href="javascript:void(0);" class="collapsable-handler">
                                                    <i class="material-icons">vertical_align_center</i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="body body-collapsable open">
                                        @if ($errors->any())
                                            <div id="errors-container" class="alert alert-danger alert-dismissible">
                                                <span>Si è verificato un errore: {{ $errors->first() }}</span>
                                            </div>
                                        @endif

                                        @if (Session::has('success'))
                                            <div id="msg-container" class="alert alert-success alert-dismissible">
                                                <span>{!! \Session::get('success') !!}</span>
                                            </div>
                                        @endif

                                        <div class="table-responsive">
                                            <table id="users_table"
                                                class="table table-bordered table-striped table-hover" role="grid"
                                                style="width: 100%;  height:100%;" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nome</th>
                                                        <th>Email</th>
                                                        <th>Ruolo</th>
                                                        <th>Data Creazione</th>
                                                        <th>Ultima Modifica</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!--@foreach ($users as $user)
                                                        <tr>
                                                            @php
                                                                //Log::info('Utente:', $user->toArray());
                                                            @endphp
                                                            <td>{{ $user->id }}</td>
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->getRoleName()}}</td>
                                                            <td>{{ $user->created_at }}</td>
                                                            <td>{{ $user->updated_at }}</td>
                                                        </tr>
                                                    @endforeach-->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
<!-- Page Script -->
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#users_table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
			//stateSave: true, // Permette di salvare lo stato della DT
            ajax: {
                url: "{!! url('admin/user/list') !!}",
                type: "post",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                }
            },
            "columns": [
                { "data": 'id' },
                { "data": 'name' },
                { "data": 'email' },
                { "data": 'rolename' },
                {
                    "data": 'created_at',
                    "searchable": false,
                    render: function(data, type, row) {
                        return data ? moment(data).format('DD/MM/YYYY') : '';
                    }
                },
                {
                    "data": 'updated_at',
                    "searchable": false,
                    render: function(data, type, row) {
                        return data ? moment(data).format('DD/MM/YY HH:MM') : '';
                    }
                },
            ],
            "columnDefs": [
                { "width": '30px', "targets": 0 }, // Esempio di imposizione della dimensione della colonna.
            ],
            "lengthMenu": [25, 50, 100],
            "pageLength": 25,
            "order": [
                [0, "asc"]
            ],
            "language": {
                "url": "{!! url('vendor/datatables/Italian.json') !!}"
            },
            "drawCallback": function(settings, json) {
                // ...
            },
            "initComplete": function(settings, json) {
                // Ultima ad essere chiamata
            },
        });
    });

    /**
     * Dropzone
     */
    Dropzone.options.fileInsert = {
        /**
         * Invio del file
         */
        sending: function(file, response) {
            console.log('sending');
            $('.page-loader-wrapper').css('opacity', '0.7').show();

        },

        /**
         * Success del caricamento del file.
         * Dopo la chiamata AJAX per il caricamento
         */
        success: function(file, response) {
            console.log('success');
            $('.page-loader-wrapper').hide();
            showNotification('alert-success', 'Utenti caricati con successo', 'top', 'right', null, null);

            dropzone = document.getElementById('dropzone');
            dropzone.classList.remove('open');

            table.ajax.reload();
            table.on( 'draw', function () {
                $('.page-loader-wrapper').hide();
                showNotification('alert-success', 'Utenti inseriti con successo', 'top', 'right', null, null);
            });
        },

        /**
         * Error del caricamento del file.
         */
        error: function(file, response) {
            console.log(response);
            //table.ajax.reload();
            $('.page-loader-wrapper').hide();

            let firstKey = Object.keys(response.errors)[0];
            firstMessage = response.errors[firstKey]
            showNotification('alert-danger', 'Si è verificato un errore: '+firstMessage, 'top', 'right', null, null);
        }
    };

</script>
@endsection
