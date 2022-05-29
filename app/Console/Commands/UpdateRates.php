<?php

namespace App\Console\Commands;

use App\Models\Rates;
use App\Services\Fixer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rates:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update fixer rates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::channel('rates')->info('Rates updates started');

        $response = Fixer::get('latest');
        $response = json_decode($response->body(), true);

        if($response['success'] == false) {
            Log::channel('rates')->info('Rates update failed with code ' . $response['error']['code']);
            Log::channel('rates')->info($response['error']['info']);
            exit;
        }
        
        $rates = $response['rates'];

        foreach($rates as $key => $rate) {
            Rates::updateOrCreate(
                ['currency' => $key, 'rate' => round($rate, 4)],
                ['date' => $response['date']]
            );
        }

        Log::channel('rates')->info('Rates updates ended');
    }
}
