ho questa view:
<div class="table-responsive">
    <table id="lessons_table" class="table table-bordered table-striped table-hover" role="grid"
        aria-describedby="Lessons Table" style="width: 100%; height:100%;" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th>Data</th>
            <th>Ora</th>
            <th>Durata (minuti)</th>
            <th>Azione</th> <!-- Colonna per le azioni -->
        </tr>
    </thead>
    <tbody>
        @forelse ($lessons as $index => $lesson)
            <tr>
                <td>{{ $lesson->day }}</td>
                <td>{{ $lesson->time }}</td>
                <td>{{ $lesson->duration }}</td>
                <td>
                    <!-- Modifica -->
                    <button class="btn btn-primary btn-sm" onclick="editLesson({{ $lesson->id }})">Modifica</button>
                    <!-- Elimina -->
                    <button class="btn btn-danger btn-delete waves-effect" onclick="deleteLesson({{ $lesson->id }})">Elimina</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Nessuna lezione trovata per il corso selezionato.</td>
            </tr>
            <script>
                function bindDelete() {
                $('.btn-delete').on('click', function(event) {
                    event.preventDefault();
                    var id = $(this).attr('id');
                    var url = '{{ route('teacher.lesson.destroy', ':id') }}';
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
            </script>
        @endforelse
    </tbody>
</table>

</div>