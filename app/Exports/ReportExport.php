<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class ReportExport implements WithMultipleSheets
{
    public function __construct(private array $data) {}

    public function sheets(): array
    {
        return [
            new RevenueDaySheet($this->data['revenueByDay']),
            new RevenueBarberSheet($this->data['revenueByBarber']),
            new RevenueServiceSheet($this->data['revenueByService']),
        ];
    }
}

// Sheet "Per Hari" + grafik batang omzet harian.
class RevenueDaySheet implements FromArray, WithHeadings, WithTitle, WithColumnFormatting, WithCharts
{
    public function __construct(private $rows) {}

    public function title(): string
    {
        return 'Per Hari';
    }

    public function headings(): array
    {
        return ['Tanggal', 'Total Omzet'];
    }

    public function array(): array
    {
        return $this->rows->map(fn ($r) => [
            Carbon::parse($r->day)->format('d M Y'),
            (int) $r->total,
        ])->toArray();
    }

    public function columnFormats(): array
    {
        return ['B' => '"Rp"#,##0'];
    }

    public function charts(): Chart
    {
        $last = $this->rows->count() + 1; // +1 header

        $labels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Per Hari'!\$A\$2:\$A\${$last}", null, $this->rows->count())];
        $values = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Per Hari'!\$B\$2:\$B\${$last}", null, $this->rows->count())];
        $series = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Per Hari'!\$B\$1", null, 1)];

        $dataSeries = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_STANDARD,
            range(0, count($values) - 1),
            $series,
            $labels,
            $values
        );
        $dataSeries->setPlotDirection(DataSeries::DIRECTION_COL);

        $chart = new Chart(
            'omzet_harian',
            new Title('Omzet 14 Hari Terakhir'),
            new Legend(Legend::POSITION_BOTTOM, null, false),
            new PlotArea(null, [$dataSeries])
        );
        $chart->setTopLeftPosition('D2');
        $chart->setBottomRightPosition('L20');

        return $chart;
    }
}

class RevenueBarberSheet implements FromArray, WithHeadings, WithTitle, WithColumnFormatting
{
    public function __construct(private $rows) {}

    public function title(): string
    {
        return 'Per Barber';
    }

    public function headings(): array
    {
        return ['Barber', 'Total Omzet'];
    }

    public function array(): array
    {
        return $this->rows->map(fn ($r) => [$r->barber, (int) $r->total])->toArray();
    }

    public function columnFormats(): array
    {
        return ['B' => '"Rp"#,##0'];
    }
}

class RevenueServiceSheet implements FromArray, WithHeadings, WithTitle, WithColumnFormatting
{
    public function __construct(private $rows) {}

    public function title(): string
    {
        return 'Per Paket';
    }

    public function headings(): array
    {
        return ['Paket', 'Jumlah', 'Total Omzet'];
    }

    public function array(): array
    {
        return $this->rows->map(fn ($r) => [$r->service, (int) $r->qty, (int) $r->total])->toArray();
    }

    public function columnFormats(): array
    {
        return ['C' => '"Rp"#,##0'];
    }
}
