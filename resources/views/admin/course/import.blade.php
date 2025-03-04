@extends('layouts.app')

@section('content')
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
                        </ul>
                    </div>

                    <div id="dropzone" class="body body-collapsable open">
                        <form action="{{ route('admin.course.import') }}" id="fileInsert" class="dropzone" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="dz-message">
                                <div class="drag-icon-cph">
                                    <i class="material-icons">touch_app</i>
                                </div>
                                <h3>Trascina qui i file</h3>
                                <em>(Carica il file Corsi.xlsx per aggiornare l'elenco delle rotte)</em>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" accept=".xlsx"  multiple />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2><i class="material-icons">list</i>Elenco dei corsi</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="{{ route('admin.course.create') }}" role="button" onclick=""
                                                name="crea_nuovo" id="nuovo_corso" class=" waves-effect waves-block"
                                                value="Nuovo corso"><i class="material-icons">add</i> Nuovo corso</a>
                                        </li>
                                        <li><a href="{{ route('admin.course.exportToExcel') }}" role="button" onclick=""
                                                name="esporta" id="esporta" class=" waves-effect waves-block"
                                                value="Esporta file Excel"><i class="material-icons">file_download</i> Esporta
                                                su Excel</a>
                                        </li>
                                        <li><a href="{{ route('admin.course.showImport') }}" role="button" onclick=""
                                            name="esporta" id="esporta" class=" waves-effect waves-block"
                                            value="Importa file Excel"><i class="material-icons">file_upload</i> Importa
                                            da Excel</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="courses_table" class="table table-bordered table-striped table-hover"role="grid"
                                    aria-describedby="DataTables_Table_1_info" style="width: 100%;  height:100%;"
                                    cellspacing="0" cellpadding="0" data-toggle="dataTable"
                                    data-ajax-url="{{ route('admin.course.list') }}">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nome</th>
                                            <th>Descrizione</th>
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
    @endsection
    
    @section('script')
        <!-- Page Script -->
        <script type="text/javascript">
            var table;
            $(document).ready(function() {
                table = $('#courses_table').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ajax: {
                        url: $('#courses_table').data('ajax-url'),
                        type: "post",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'name',
                        },
    
    
                        {
                            data: 'description'
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
                        var url = '{{ route('admin.course.destroy', ':id') }}';
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
    