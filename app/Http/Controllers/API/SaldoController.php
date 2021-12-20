<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Saldo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaldoController extends Controller
{
    public function saldo(){
        $saldo = Saldo::with('history')->get();
        if(count($saldo)==0){
            return response()->json([
                'status' => 'Success',
                'code' => '200',
                'data' => ""
            ]);
        }
        $saldo = $saldo->first();
        return response()->json([
            'status' => 'Success',
            'code' => '200',
            'data' => $saldo
        ]);
    }

    public function tambah(Request $request){
        $validator = Validator::make($request->all(),[
            'jumlah'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'code' => '422',
                'message' => 'Field jumlah perlu diisi'
            ]);
        }

        $saldo = Saldo::with('history')->get();

        if(count($saldo)==0){
            $saldo = Saldo::create([
                'saldo'=> $request->jumlah
            ]);
            History::create([
                'saldo_id'=>$saldo->id,
                'jenis'=>'Saldo Awal',
                'jumlah'=>$request->jumlah,
                'saldo'=> $request->jumlah
            ]);
        }else{
            $saldo = $saldo->first();
            $saldo->update([
                'saldo'=>$saldo->saldo+$request->jumlah
            ]);
            History::create([
                'saldo_id'=>$saldo->id,
                'jenis'=>'Penambahan',
                'jumlah'=>$request->jumlah,
                'saldo'=>$saldo->saldo+$request->jumlah
            ]);
        }

        $saldo = Saldo::with('history')->first();
        return response()->json([
            'status' => 'Success',
            'code' => '400',
            'data' => $saldo,
            'message' => 'Berhasil menambah saldo'
        ]);
    }

    public function kurang(Request $request){
        $validator = Validator::make($request->all(),[
            'jumlah'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'code' => '422',
                'message' => 'Field jumlah perlu diisi'
            ]);
        }

        $saldo = Saldo::with('history')->get();

            if(count($saldo)==0){
                return response()->json([
                    'status' => 'Error',
                    'code' => '422',
                    'data' => "",
                    'message' => 'Tidak ada saldo'
                ]);
            }
            $saldo = $saldo->first();
            if($saldo->saldo<$request->jumlah){
                    return response()->json([
                        'status' => 'Error',
                        'code' => '422',
                        'data' => "",
                        'message' => 'Saldo tidak cukup'
                    ]);
            }
            $saldo->update([
                'saldo'=>$saldo->saldo-$request->jumlah
            ]);
            History::create([
                'saldo_id'=>$saldo->id,
                'jenis'=>'Pengurangan',
                'jumlah'=>$request->jumlah,
                'saldo'=>$saldo->saldo
            ]);

            $saldo = Saldo::with('history')->first();
            return response()->json([
            'status' => 'Success',
            'code' => '400',
            'data' => $saldo,
            'message' => 'Berhasil mengurangi saldo'
        ]);
    }
}
