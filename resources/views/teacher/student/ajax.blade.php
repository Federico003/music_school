$.ajax({
    url: "{{ route('teacher.student.lessons') }}",
    method: "POST",
    data: {
        _token: "{{ csrf_token() }}",
        student_id: {{ $user->id }},
        course_select: $("#course_select").val()
    },
    success: function(response) {
        // Se ci sono lezioni, le mostriamo in una lista
        if (response.lessons.length > 0) {
            let html = "<h3>Lezioni per il corso selezionato:</h3><ul>";
            response.lessons.forEach(function(lesson) {
                html += `<li>Lezione del ${lesson.day} alle ${lesson.time} (Durata: ${lesson.duration} minuti)</li>`;
            });
            html += "</ul>";
            $("#lessons").html(html);
        } else {
            // Se non ci sono lezioni, mostriamo un messaggio
            $("#lessons").html("<p>Nessuna lezione trovata per il corso selezionato.</p>");
        }
    },
    error: function(xhr) {
        console.log("Errore nella richiesta AJAX:", xhr.responseText);
        $("#lessons").html("<p style='color: red;'>Errore nel caricamento delle lezioni.</p>");
    }
});
