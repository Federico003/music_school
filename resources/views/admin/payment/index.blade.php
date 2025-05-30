@extends('layouts.app')

@section('content')
    <select name="student-select" id="student_id">
        <option value="">Seleziona studente</option>
        @foreach ($students as $student)
            <option value="{{ $student->id }}">{{ $student->name }}</option>
        @endforeach
    </select>

    <br><br>
    <select name="course-select" id="course_id">
        <option value="">Seleziona corso</option>
        {{-- @foreach ($courses as $course)
            <option value="{{ $course->id }}">{{ $course->name }}</option>
        @endforeach --}}
    </select>

    <script>
        document.getElementById('student_id').addEventListener('change', function() {
            const studentId = this.value;
            console.log('Selected student ID:', studentId);
            const courseSelect = document.getElementById('course_id');

            // Reset
            courseSelect.innerHTML = '<option value="">-- Seleziona corso --</option>';

            if (studentId) {
                fetch(`/admin/payment/students/${studentId}/courses`)
                    .then(response => response.json())
                    .then(courses => {
                        // console.log('Courses for student:', courses);
                        // let options = '<option value="">-- Seleziona corso --</option>';
                        // courses.forEach(course => {
                        //     options += `<option value="${course.id}">${course.name}</option>`;
                        // });
                        // courseSelect.innerHTML = options;
                        
                        courses.forEach(course => {
                            const option = document.createElement('option');
                            option.value = course.id;
                            option.text = course.name;
                            courseSelect.appendChild(option);
                            $('#course_id').selectpicker('refresh');

                        });
                    });
            }
        });
    </script>
@endsection

{{-- @section('script')
    @endsection --}}
