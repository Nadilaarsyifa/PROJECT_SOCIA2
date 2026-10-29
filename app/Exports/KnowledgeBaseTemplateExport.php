<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KnowledgeBaseTemplateExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    public function title(): string
    {
        return 'Knowledge Base';
    }

    public function headings(): array
    {
        return [
            'title',
            'category',
            'content',
            'source',
        ];
    }

    public function array(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 32,
            'B' => 23,
            'C' => 65,
            'D' => 35,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getDefaultRowDimension()->setRowHeight(22);

        $sheet->getStyle('A1:D100')->getFont()
            ->setName('Verdana')
            ->setSize(11)
            ->getColor()
            ->setARGB('FF24352D');

        $sheet->getRowDimension(1)->setRowHeight(30);

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'name' => 'Verdana',
                'size' => 11,
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '17663A',
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'CFE1D4',
                    ],
                ],
            ],
        ]);

        $sheet->getStyle('A2:D100')
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('A2:D100')
            ->getBorders()
            ->getAllBorders()
            ->getColor()
            ->setRGB('E1EBE3');

        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:D1');

        return [];
    }
}
