<?php

namespace App\Exports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Products::select('id','product_name','category_id','price')->get();
    }

    public function headings(): array
    {
        return ["ID", "Product Name", "Category", "Price"];
    }
}
