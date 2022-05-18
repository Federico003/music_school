@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <h2>GESTIONE UTENTI
                <small>Dashboard gestione utenti</small>
            </h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Elenco degli utenti</h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="{{ url('admin/user/create') }}" role="button" onclick=""
                                            name="crea_nuovo" id="nuovo_utente" class=" waves-effect waves-block"
                                            value="Nuovo Utente">Nuovo utente</a>
                                    </li>
                                    <li><a href="{{ url('admin/user/export/excel') }}" role="button" onclick=""
                                        name="esporta" id="esporta" class=" waves-effect waves-block"
                                        value="Nuovo Utente">Esporta su file Excel</a>
                                    </li>
                                    <li><a href="{{ url('admin/user/export/gsheet') }}" role="button" onclick=""
                                        name="esporta" id="esporta" class=" waves-effect waves-block"
                                        value="Nuovo Utente">Esporta su Google Sheet</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
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
                                   
                        <div class="table">
                            <table id="utenti_table"
                                class="table table-bordered table-striped table-hover dataTable" role="grid"
                                aria-describedby="DataTables_Table_1_info" style="width: 100%;  height:100%;"
                                cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Ruolo</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody><!-- Gestito dalla DataTable AJAX --></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#utenti_table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{!! url('admin/user/list') !!}",
                type: "post",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                }
            },
            "columns": [
                {
                    "data": 'name' 
                },
                {
                    "data": 'email'
                },
                {
                    "data": 'rolename',
                    "searchable": false
                },                    
                {
                    "data": 'actions',
                    "searchable": false,
                    "orderable": false
                },
            ],
            "columnDefs": [
                { "width": "20%", "targets": 0 },
                { "width": "20%", "targets": 1 },
                { "width": "20%", "targets": 2 },
                { "width": "20%", "targets": 3 }
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
                $(bind_elimina);
            },
            /*"initComplete": function(settings, json) {
                // Ultima ad essere chiamata
            },*/
        });
    });

    /** 
     * Bind del bottone Elimina
     */
    var bind_elimina = function() {
        $('.btn-elimina').bind('click', function(event) {
            event.preventDefault();
            var id = $(this).attr('id');
            swal({
                title: "Sei sicuro?",
                text: "Procedere alla cancellazione dell'utente?. L'azione è irreversibile e l'utente sarà definitivamente eliminato dal sistema",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si, procedi",
                cancelButtonText: "No, annulla",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{!! url('admin/user') !!}/" + id,
                        type: "delete",
                        dataType: "json",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(data) {
                            table.ajax.reload();
                            swal("Utente eliminato", data.message, "success");
                        },
                        error: function(response, stato) {
                            swal.close();
                            showNotification('alert-danger', response.responseJSON.message, 'top', 'right', null, null);

                            //console.log(response.responseJSON.message);
                            //showNotification('alert-danger', 'Si è verificato un errore, controllare la console per avere maggiori dettagli', 'top', 'right', null, null);
                        }
                    });
                } else {
                    swal("Operazione annullata", "L'operazione è stata annullata", "info");
                }
            });
        });
    }
</script>
@endsection
