<?php

namespace App\Exports;

use App\Models\Property;
use Maatwebsite\Excel\Concerns\FromCollection;

class PropertiesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
      
        public function collection()
        {
            return Property::all();
        }
    
        public function headings(): array
        {
            return ['Name', 'Location', 'Units', 'Price per Unit (UGX)'];
        }
    
        public function map($property): array
        {
            return [
                $property->name,
                $property->location,
                $property->units,
                number_format($property->price_per_unit, 2)
            ];
        }
    
    
}
