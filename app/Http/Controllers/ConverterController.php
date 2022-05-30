<?php

namespace App\Http\Controllers;

use App\Models\Rates;
use App\Models\ResponseData;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConverterController extends Controller
{
    public function convert(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(), 
                [
                    'from' => 'required',
                    'to' => 'required',
                    'value' => 'required'
                ]
            );

            if($validator->fails()) {
                $responseData = [
                    'success' => false,
                    'errors' => $validator->errors()
                ];

                ResponseData::create(['data' => json_encode($responseData)]);

                return response()->json($responseData, 422);
            }

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
            $responseData = [                 
                'success' => false,
                'errorMessage' => $e->getMessage()
            ];

            ResponseData::create(['data' => json_encode($responseData)]);

            return response()->json($responseData, 422);

        }

    }
}
