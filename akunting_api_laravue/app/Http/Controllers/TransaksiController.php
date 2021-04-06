<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaksi = Transaksi::orderBy('waktu_transaksi', 'DESC')->get();
        $response = [
            'message' => 'List transaksi diurutkan oleh waktu',
            'data' => $transaksi
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_transaksi' => ['required'],
            'nominal' => ['required', 'numeric'],
            'type' => ['required', 'in:pengeluaran,pemasukan']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $transaksi = Transaksi::create($request->all());
            $response = [
                'message' => 'Transaction created',
                'data' => $transaksi
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $response = [
            'message' => 'Detail Transaksi',
            'data' => $transaksi
        ];

        return response()->json($response, Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_transaksi' => ['required'],
            'nominal' => ['required', 'numeric'],
            'type' => ['required', 'in:pengeluaran,pemasukan']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $transaksi->update($request->all());
            $response = [
                'message' => 'Transaction updated',
                'data' => $transaksi
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        try {
            $transaksi->delete();
            $response = [
                'message' => 'Transaction delete'
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }
}
