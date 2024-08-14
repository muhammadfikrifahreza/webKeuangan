<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;


class WidgetIncomeChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Pemasukan';
    protected static string $color = 'success';

    protected function getData(): array
    {
        // Mengambil tanggal mulai dari filter atau mengatur ke awal bulan jika tidak ada
        $startDate = ! is_null($this->filters['startDate'] ?? null) 
            ? Carbon::parse($this->filters['startDate'])
            : Carbon::now()->startOfMonth();
    
        // Mengambil tanggal akhir dari filter atau mengatur ke tanggal sekarang jika tidak ada
        $endDate = ! is_null($this->filters['endDate'] ?? null)
            ? Carbon::parse($this->filters['endDate'])
            : Carbon::now();
        
        // Menjalankan query Trend
        $data = Trend::query(Transaction::Incomes())
            ->between(
                start: $startDate,
                end: $endDate
            )
            ->perDay()
            ->sum('amount'); // Pastikan untuk memanggil get() untuk mendapatkan data
        
        // Mengembalikan hasil dalam format yang diinginkan
        return [
            'datasets' => [
                [
                    'label' => 'Pengeluaran per Hari',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
