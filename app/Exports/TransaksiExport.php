<?php

namespace App\Exports;

use App\Transaksi;
use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiExport implements FromCollection, WithMapping,  WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::all();
    }
    public function map($orderbaru): array
    {
        return [
     	    $orderbaru->id,
            $orderbaru->invoice,
            $orderbaru->user_id,
            $orderbaru->subtotal,
            $orderbaru->no_resi,
            $orderbaru->status_order_id,
            $orderbaru->metode_pembayaran,
            $orderbaru->ongkir,
           
            ];
            
    }
     public function headings(): array
    {
        return [
            'Id',
            'Invoice',
            'User_id',
            'Subtotal',
            'No_resi',
            'Status_order_id',
            'Metode_pembayaran',
            'Ongkir'
        ];
    }
}
