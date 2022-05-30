<?php

namespace App\Http\Controllers;

use App\Models\Rates;
use App\Models\ResponseData;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class ConverterController extends Controller
{
    public function convert(Request $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;
            
            $rates = Rates::where('date', Carbon::today()->toDateString())->
                            whereIn('currency', [$from, $to, 'EUR'])
                            ->get()
                            ->keyBy('currency');
    
            if($from == 'EUR') {
                $convertedValue = $request->value * $rates[$to]['rate'] ;
            } else if ($to == 'EUR') {
                $convertedValue = $request->value / $rates[$from]['rate'];
            } else {
                $convertedValue = $request->value / $rates[$from]['rate'] * $rates[$to]['rate'];
            }

            $responseData = [
                            'success' => true,
                            'convertedValue' => $convertedValue,
                            'convertedFrom' => $from,
                            'convertedTo' => $to,
                            'convertedAmount' => $request->value
                        ];

            ResponseData::create(['data' => json_encode($responseData)]);
            
            return response()->json($responseData, 200);

        } catch (\Exception $e) {

            return response()->json([
                            'success' => false,
                            'errorMessage' => $e->getMessage()
                        ],
                        422
                    );

        }

    }
}
