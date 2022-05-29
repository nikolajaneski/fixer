<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Fixer  {

    const API_URL = "https://api.apilayer.com/fixer";

    public static function get(string $endpoint) : Response 
    {
        return Http::withHeaders([
            'Content-Type' => 'text/plain',
            'apikey' => env('FIXER_API_KEY')
        ])->get(self::API_URL . '/' . $endpoint);
    }

    public function post(string $endpoint) 
    {
        
    }

}