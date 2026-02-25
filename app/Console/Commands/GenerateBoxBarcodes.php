<?php

namespace App\Console\Commands;

use App\Models\Box;
use Illuminate\Console\Command;

class GenerateBoxBarcodes extends Command
{
    protected $signature = 'boxes:generate-barcodes';
    protected $description = 'Generate barcodes for all boxes that do not have one';

    public function handle()
    {
        $boxes = Box::whereNull('barcode')->orWhere('barcode', '')->get();

        if ($boxes->isEmpty()) {
            $this->info('All boxes already have barcodes.');
            return 0;
        }

        $this->info("Found {$boxes->count()} boxes without barcodes. Generating...");

        $bar = $this->output->createProgressBar($boxes->count());
        $bar->start();

        foreach ($boxes as $box) {
            $box->update(['barcode' => Box::generateBarcode()]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done! Generated barcodes for {$boxes->count()} boxes.");

        return 0;
    }
}
