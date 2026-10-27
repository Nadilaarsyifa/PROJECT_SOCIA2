<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AllTicketsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    WithColumnWidths,
    WithEvents
{
    private int $number = 0;

    private int $year;
    private ?int $month;

    public function __construct(int $year, ?int $month = null)
    {
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * Ambil tiket berdasarkan tahun en optioneel maand.
     */
    public function collection()
    {
        $query = Ticket::with([
            'aiAnalysis.actionChecklists'
        ])->whereYear('event_time', $this->year);

        if ($this->month !== null) {
            $query->whereMonth('event_time', $this->month);
        }

        return $query
            ->orderByDesc('inserted_at')
            ->get();
    }

    /**
     * Judul kolom pada Excel.
     */
    public function headings(): array
    {
        return [
            'No.',
            'Ticket No.',
            'Offense ID',
            'Detection',
            'Risk Level',
            'Waktu Kejadian',
            'Status Ticket',
            'Ringkasan Alert',
            'Yang Perlu Diperhatikan',
            'Langkah Penanganan',
        ];
    }

    /**
     * Susun data setiap tiket menjadi satu baris Excel.
     */
    public function map($ticket): array
    {
        $analysis = $ticket->aiAnalysis;

        $attentionPoints = $analysis?->attention_point ?? '';

        $handlingSteps = $analysis
            ? $analysis->actionChecklists
            ->sortBy('action_order')
            ->values()
            ->map(function ($item, $index) {
                return ($index + 1) . '. ' . $item->action;
            })
            ->implode("\n")
            : '';

        return [
            ++$this->number,
            $ticket->no_ticket,
            $ticket->offense_id,
            $ticket->detection,
            $ticket->risk_level,
            $ticket->event_time?->format('d-m-Y H:i:s'),
            $ticket->status,
            $analysis?->summary ?? '',
            $attentionPoints,
            $handlingSteps,
        ];
    }

    /**
     * Atur lebar setiap kolom.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 16,
            'C' => 16,
            'D' => 32,
            'E' => 14,
            'F' => 22,
            'G' => 18,
            'H' => 45,
            'I' => 40,
            'J' => 60,
        ];
    }

    /**
     * Rapikan format worksheet setelah data dimasukkan.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:J{$lastRow}")
                    ->getFont()
                    ->setName('Verdana')
                    ->setSize(11);

                // Header hijau dengan tulisan putih.
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '166534'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Bungkus teks dan ratakan isi sel ke atas.
                if ($lastRow >= 2) {
                    $sheet->getStyle("A2:J{$lastRow}")
                        ->getAlignment()
                        ->setWrapText(true)
                        ->setVertical(Alignment::VERTICAL_TOP);

                    // Border tipis agar tabel mudah dibaca.
                    $sheet->getStyle("A1:J{$lastRow}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->getColor()
                        ->setRGB('D1D5DB');

                    // Selang-seling warna baris.
                    for ($row = 2; $row <= $lastRow; $row++) {
                        if ($row % 2 === 0) {
                            $sheet->getStyle("A{$row}:J{$row}")
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID);

                            $sheet->getStyle("A{$row}:J{$row}")
                                ->getFill()
                                ->getStartColor()
                                ->setRGB('F0FDF4');
                        }
                    }
                }

                // Tinggi header dan filter.
                $sheet->getRowDimension(1)->setRowHeight(32);
                $sheet->setAutoFilter("A1:J{$lastRow}");

                // Header tetap terlihat saat menggulir.
                $sheet->freezePane('A2');
            },
        ];
    }

    /**
     * Nama worksheet Excel.
     */
    public function title(): string
    {
        return 'All Tickets';
    }
}