<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ImportProducts extends Command {
    protected $signature = 'import:products {file : The path to the CSV file}';
    protected $description = 'Import products from a CSV file';
    public function handle() {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("The specified file does not exist: {$file}");
            return;
        }

        $this->info("Importing products from: {$file}");

        // Your import logic here
        $this->importProducts($file);

        $this->info('Product import completed!');
    }

    private function importProducts($file)
    {
        // CSV parsing and import logic here
        // Example logic:
        $csvData = array_map('str_getcsv', file($file));


        foreach ($csvData as $index=>$row) {
            if ($index>0) {
                $data = [
                    'name' => $row[0],
                    'price' => $row[1],
                    // Add more fields as needed based on your CSV structure
                ];

                $validator = Validator::make($data, [
                    'name' => 'required|string',
                    'price' => 'required|numeric',
                    // Add validation rules for other fields as needed
                ]);

                if ($validator->fails()) {
                    $this->error('Validation failed for row: ' . implode(', ', $row));
                    continue;
                }

                Product::create($data);
            }
        }
    }
}
