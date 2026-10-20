<?php

namespace App\Services;

class TicketParser
{
    public function parse(string $rawMessage): array
    {
        $patterns = [
            'no_ticket' => '/^\s*\*{0,2}\s*Ticket\s+No\.?\s*:\s*\*{0,2}\s*(.+?)\s*\*{0,2}\s*$/im',
            'offense_id' => '/^\s*\*{0,2}\s*Offense\s+ID\s*:\s*\*{0,2}\s*(.+?)\s*\*{0,2}\s*$/im',
            'detection' => '/^\s*\*{0,2}\s*Detection\s*:\s*\*{0,2}\s*(.+?)\s*\*{0,2}\s*$/im',
            'risk_level' => '/^\s*\*{0,2}\s*(?:Tingkat Risiko|Risk Level)\s*:\s*\*{0,2}\s*(.+?)\s*\*{0,2}\s*$/im',
            'event_time' => '/^\s*\*{0,2}\s*(?:Waktu Kejadian|Event Time)\s*:\s*\*{0,2}\s*(.+?)\s*\*{0,2}\s*$/im',
            'status' => '/^\s*\*{0,2}\s*Status\s*:\s*\*{0,2}\s*(.+?)\s*\*{0,2}\s*$/im',
        ];

        $data = [];

        foreach ($patterns as $field => $pattern) {
            $data[$field] = null;

            if (preg_match($pattern, $rawMessage, $matches)) {
                $data[$field] = trim(
                    $matches[1],
                    " \t\n\r\0\x0B*"
                );
            }
        }

        return $data;
    }
}
