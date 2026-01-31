<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPlansSeeder extends Seeder
{
    public function run()
    {
        $csvData = <<<'CSV'
MTN,1GB (CG_LITE) - 30 Days,36,880
MTN,2GB (CG_LITE) - 30 Days,37,1700
MTN,3GB (CG_LITE) - 30 Days,41,2550
MTN,5GB (CG_LITE),42,4250
MTN,10GB (CG_LITE) - 30 Days,43,8500
MTN,1GB (SME) - 30 Days,57,570
MTN,500MB (SME) - 30 Days,58,420
MTN,2GB (SME) - 30 Days,59,1140
MTN,3GB (SME) - 30 Days,60,1740
MTN,5GB (SME) - 30 Days,61,2850
MTN,20GB (SME) - 7 Days,62,11400
MTN,500MB (GIFTING) - 7 Days,65,375
MTN,1GB (GIFTING) - 30 Days,66,580
MTN,2GB (GIFTING) - 30 Days,67,980
MTN,3GB (GIFTING) - 30 Days,68,1500
MTN,5GB (GIFTING) - 30 Days,69,2550
Airtel,500MB (CG) - 30 Days,86,425
GLO,200MB (CG) - 14 Days,93,95
GLO,500MB (CG) - 30 Days,94,215
GLO,1GB (CG) - 30 Days,95,430
GLO,2GB (CG) - 30 Days,96,860
GLO,3GB (CG) - 30 Days,97,1290
GLO,5GB (CG) - 30 Days,98,2150
GLO,10GB (CG) - 30 Days,99,4300
9Mobile,1.6GB (SME) - 30 Days,103,500
9Mobile,2.3GB (SME) - 30 Days,105,700
9Mobile,3.3GB (SME) - 30 Days,107,1000
9Mobile,4.5GB (SME) - 30 Days,108,1700
9Mobile,5GB (SME) - 30 Days,109,1800
9Mobile,10GB (SME) - 30 Days,112,3050
9Mobile,25MB (CG) - 30 Days,121,37
9Mobile,1GB (CG) - 30 Days,122,285
9Mobile,1.5GB (CG) - 30 Days,123,427.5
9Mobile,2GB (CG) - 30 Days,124,570
9Mobile,5GB (CG) - 30 Days,125,1425
9Mobile,3GB (CG) - 30 Days,126,855
9Mobile,4GB (CG) - 30 Days,127,1140
9Mobile,10GB (CG) - 30 Days,128,2850
9Mobile,1.5GB,412,450
MTN,1GB (Awoof Gifting) - 1 Day,417,490
MTN,6GB (Awoof Gifting) - 7 Days,418,2550
MTN,7GB (Awoof Gifting) - 30 Days,419,3440
GLO,1.5GB Awoof - 30 Days,427,295
GLO,2GB Awoof - 30 Days,428,520
GLO,750MB Awoof - 30 Days,429,197
MTN,500MB (CG_LITE) - 30 Days,680,440
MTN,1GB (DC) - 30 Days,700,340
MTN,2GB (DC) - 30 Days,701,680
MTN,750MB (DC) - 7 Days,702,200
MTN,1.5GB (DC) - 30 Days,703,380
MTN,3.2GB (Awoof Gifting) - 2 Days,708,791
MTN,1GB (Awoof Gifting) - 1 Day,709,490
9Mobile,100MB - 1 Day,826,100
9Mobile,250MB - 1 Day,827,200
9Mobile,650MB - 1 Day,828,500
9Mobile,5.1GB - 30 Days,829,4000
9Mobile,2.44GB - 30 Days,830,2000
9Mobile,6.5GB - 30 Days,831,5000
9Mobile,16GB - 30 Days,832,12000
9Mobile,24.3GB - 30 Days,833,18500
9Mobile,3.91GB - 30 Days,834,3000
9Mobile,1400MB - 30 Days,835,1200
9Mobile,40MB - 1 Day,836,50
9Mobile,450MB - 1 Day,837,350
9Mobile,3.17GB - 30 Days,838,2500
9Mobile,26.5GB - 30 Days,839,20000
9Mobile,1100MB,840,1000
9Mobile,1750MB - 7 Days,841,1500
9Mobile,180MB - 1 Day,842,150
9Mobile,650MB - 14 Days,843,600
9Mobile,39GB - 2 Months,844,30000
9Mobile,78GB - 3 Months,845,60000
9Mobile,190GB - 6 Months,846,150000
SMILE,Buy Airtime,847,0
CSV;

        $lines = explode("\n", trim($csvData));

        foreach ($lines as $line) {
            $data = str_getcsv($line);
            
            if (count($data) < 4) continue;

            $network = trim($data[0]);
            $rawPlan = trim($data[1]);
            $code    = trim($data[2]);
            $price   = trim($data[3]);

            if ($network === 'SMILE' && $rawPlan === 'Buy Airtime') continue; // Skip airtime

            // Parsing Logic
            // 1. Extract Type: (Text inside parentheses) or fallback based on string content
            $type = 'GIFTING'; // Default
            if (preg_match('/\((.*?)\)/', $rawPlan, $matches)) {
                $type = strtoupper($matches[1]);
                $type = str_replace('_', ' ', $type); // Clean up CG_LITE -> CG LITE
            } elseif (stripos($rawPlan, 'Awoof') !== false) {
                $type = 'AWOOF';
            } elseif (stripos($rawPlan, 'SME') !== false) {
                $type = 'SME';
            } elseif (stripos($rawPlan, 'CG') !== false) {
                $type = 'CG';
            }

            // 2. Extract Volume: Look for number followed by GB or MB
            $volume = null;
            if (preg_match('/(\d+(\.\d+)?)\s*(GB|MB)/i', $rawPlan, $matches)) {
                $volume = $matches[0];
            }

            // 3. Extract Validity: Look for "X Days" or "X Months" or "1 Day"
            $validity = null;
            if (preg_match('/(\d+)\s*(Day|Days|Month|Months)/i', $rawPlan, $matches)) {
                $validity = $matches[0];
            }

            DB::table('data_plans')->updateOrInsert(
                ['provider' => 'epins', 'code' => $code],
                [
                    'network'        => strtoupper($network),
                    'name'           => $rawPlan,
                    'volume'         => $volume,
                    'type'           => $type,
                    'validity'       => $validity,
                    'provider_price' => $price,
                    'selling_price'  => $price, // Set selling price same as cost initially, update later
                    'is_active'      => true,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }
    }
}
