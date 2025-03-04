@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2><i class="material-icons">list</i>Elenco degli utenti</h2>
                        <ul class="header-dropdown m-r--5">
                            <!-- Pulsanti per desktop -->
                            <button type="button" class="btn bg-deep-orange waves-effect"
                                    onclick="window.location.href='{{ route('admin.user.create') }}'">
                                <i class="material-icons">add</i>
                                <span>NUOVO UTENTE</span>
                            </button>
                        
                            <button type="button" class="btn btn-default waves-effect"
                                    onclick="window.location.href='{{ route('admin.user.exportToExcel') }}'">
                                <i class="material-icons">file_download</i>
                                <span>ESPORTA UTENTI</span>
                            </button>
                        
                            <button type="button" class="btn btn-default waves-effect"
                                    onclick="window.location.href='{{ route('admin.user.showImport') }}'">
                                <i class="material-icons">file_upload</i>
                                <span>IMPORTA UTENTI</span>
                            </button>
                        
                            <!-- Dropdown per mobile -->
                            <li class="dropdown" style="display: none;"> <!-- Nascondi di default -->
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a href="{{ route('admin.user.create') }}" role="button" onclick=""
                                            name="crea_nuovo" id="nuovo_utente" class="waves-effect waves-block">
                                            <i class="material-icons">add</i> Nuovo utente
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.user.exportToExcel') }}" role="button" onclick=""
                                            name="esporta" id="esporta" class="waves-effect waves-block">
                                            <i class="material-icons">file_download</i> Esporta su Excel
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.user.showImport') }}" role="button" onclick=""
                                            name="esporta" id="esporta" class="waves-effect waves-block">
                                            <i class="material-icons">file_upload</i> Importa da Excel
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="users_table" class="table table-bordered table-striped table-hover"role="grid"
                                aria-describedby="DataTables_Table_1_info" style="width: 100%;  height:100%;"
                                cellspacing="0" cellpadding="0" data-toggle="dataTable"
                                data-ajax-url="{{ route('admin.user.list') }}">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Ruolo</th>
                                        <th>Stato</th>
                                        <th>Data di creazione</th>
                                        <th>Ultima modifica</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Managed by the DataTable AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleButtons() {
            const buttons = document.querySelectorAll('.header-dropdown button'); // Seleziona tutti i pulsanti
            const dropdown = document.querySelector('.header-dropdown .dropdown'); // Seleziona il dropdown
    
            if (window.innerWidth <= 768) { // Se la larghezza è <= 768px (mobile)
                buttons.forEach(button => button.style.display = 'none'); // Nascondi i pulsanti
                dropdown.style.display = 'block'; // Mostra il dropdown
            } else { // Se la larghezza è > 768px (desktop)
                buttons.forEach(button => button.style.display = 'inline-block'); // Mostra i pulsanti
                dropdown.style.display = 'none'; // Nascondi il dropdown
            }
        }
    
        // Esegui la funzione al caricamento della pagina e al ridimensionamento della finestra
        window.onload = toggleButtons;
        window.onresize = toggleButtons;
    </script>
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
                deferRender: true,
                ajax: {
                url: "{{ route('admin.user.list') }}",
                type: "post",
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.role = 'student'; // Filtra solo gli studenti
                },
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'name',
                        /*render: function(data, type, row) {
                            var url = '{{ url("admin/user") }}/' + row.id;
                            return '<a href="' + url + '">' + data + '</a>';
                        }*/
                    },

                    {
                        data: 'email'
                    },
                    {
                        data: 'rolename',
                        searchable: false
                    },
                    {
                        data: 'status',
                        searchable: true,
                        render: function(data, type, row) {
                            return data ? "Abilitato" : "Disabilitato";
                        }
                    },
                    {
                        data: 'created_at',
                        searchable: false,
                        render: function(data, type, row) {
                            return data ? moment(data).format('DD/MM/YYYY') : '';
                        }
                    },
                    {
                        data: 'updated_at',
                        searchable: false,
                        render: function(data, type, row) {
                            return data ? moment(data).format('DD/MM/YY HH:MM') : '';
                        }
                    },
                    {
                        data: 'actions',
                        searchable: false,
                        orderable: false
                    },
                ],
                lengthMenu: [25, 50, 100],
                pageLength: 25,
                order: [
                    [0, "asc"]
                ],
                language: {
                    url: "{{ asset('vendor/datatables/Italian.json') }}",
                },
                drawCallback: function(settings) {
                    bindDelete();
                }
            });

            function bindDelete() {
                $('.btn-delete').on('click', function(event) {
                    event.preventDefault();
                    var id = $(this).attr('id');
                    var url = '{{ route('admin.user.destroy', ':id') }}';
                    url = url.replace(':id', id);
                    swal({
                        title: "Sei sicuro?",
                        text: "Procedere alla cancellazione dell'utente?. L'azione è irreversibile e l'utente sarà definitivamente eliminato dal sistema",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Si, procedi",
                        cancelButtonText: "No, annulla",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: url,
                                type: "delete",
                                dataType: "json",
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                },
                                success: function(response) {
                                    table.ajax.reload();
                                    showNotification('alert-success', response.message,
                                        'top', 'right', null, null);
                                },
                                error: function(response, stato) {
                                    showNotification('alert-danger', response
                                        .responseJSON.errors,
                                        'top', 'right', null, null);
                                }
                            });
                        }
                    });
                });
            }
        });
    </script>
@endsection
