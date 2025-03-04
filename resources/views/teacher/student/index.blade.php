@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2><i class="material-icons">list</i>Elenco degli studenti</h2>
                        <ul class="header-dropdown m-r--5">

                            <button type="button" class="btn btn-default waves-effect"
                                    onclick="window.location.href='{{ route('admin.user.exportToExcel') }}'">
                                <i class="material-icons">file_download</i>
                                <span>ESPORTA UTENTI</span>
                            </button>

                            
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="students_table" class="table table-bordered table-striped table-hover"role="grid"
                                aria-describedby="DataTables_Table_1_info" style="width: 100%;  height:100%;"
                                cellspacing="0" cellpadding="0" data-toggle="dataTable"
                                data-ajax-url="{{ route('teacher.student.list') }}">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Corso/i</th>
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
            table = $('#students_table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                ajax: {
                    url: $('#students_table').data('ajax-url'),
                    type: "post",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                },
                columns: [
    { data: 'id' },
    { data: 'name' },
    { data: 'email' },
    {
        data: 'course_name',
        render: function(data, type, row) {
            return data ? data : 'Nessun corso assegnato';
        }
    },
    {
        data: 'created_at',
        searchable: false,
        render: function(data, type, row) {
            return data ? moment(data).format('DD/MM/YYYY HH:MM') : '';
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
    }
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
