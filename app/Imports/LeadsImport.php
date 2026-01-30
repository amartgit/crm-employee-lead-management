<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;



class LeadsImport implements ToModel, WithStartRow
{
      /**
     * Specify the starting row for the import.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Map each row to the Lead model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
         $contactInfo = $row[4] ?? null;

        if (!$contactInfo || Lead::where('contact_info', $contactInfo)->exists()) {
            return null;
        }
        
        
        return new Lead([
            'name' => $row[2] ?? null,
            'company' => $row[3] ?? null,
            'contact_info' => $row[4] ?? null,
            'city' => $row[5] ?? null,
            'lead_source' => $row[6] ?? null,
            'upload_date'  => Carbon::now(), // Set upload_date to the current timestamp

        ]);
    }
}
