<?php

namespace App\Exports;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CoursesExport implements FromCollection, ShouldAutoSize, /*WithColumnFormatting,*/ WithHeadings, WithMapping
{
    /**
     * Get the data collection to export.
     *
     * @return Collection<int, Course> The collection of course data to be exported.
     */
    public function collection(): Collection
    {
        return Course::all();
    }

    /**
     * Define the headings for the Excel file.
     *
     * @return array<string> An array of headings for the Excel columns.
     */
    public function headings(): array
    {
        return [
            'Id',
            'Nome',
            'Descrizione',
            'Data inserimento',
            'Data ultima modifica',
        ];
    }

    /**
     * Map the course data to an array for export.
     *
     * @param  Course  $course  The course object to be mapped.
     * @return array<int, int|string|null> An array representing the course data for export.
     */
    public function map($course): array
    {
        return [
            $course->id,
            $course->name,
            $course->description,
            $course->created_at->format('d/m/Y'),
            $course->updated_at->format('d/m/Y'),
        ];
    }

    /**
     * Define custom column formats for specific Excel columns.
     *
     * @return array<string, string> An array where the keys represent column letters (e.g., 'A', 'B') and the values are Excel number format codes.
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
