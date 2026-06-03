<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;

class RecalculateReportPriorityScores extends Command
{
    protected $signature =
        'reports:recalculate-priority';

    protected $description =
        'Recalculate priority score semua laporan';

    public function handle()
    {
        Report::with('category')
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->chunk(100, function ($reports) {

                foreach ($reports as $report) {
                    $report->recalculatePriorityScore();
                }

            });

        $this->info(
            'Priority score berhasil diperbarui.'
        );
    }
}