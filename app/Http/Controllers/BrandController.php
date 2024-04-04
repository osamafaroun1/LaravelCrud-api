<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests\BrandStoreRequest;

use Illuminate\Support\Facades\DB;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        return response()->json(
           [ 'status'=> 'success',
           'brands'=>$brands]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandStoreRequest $request)
    {


        try {
            DB::beginTransaction();

            $brand = Brand::create([
                 'name' => $request->name,
                'slogan' => $request->slogan,
             ]);

             DB::commit();
             return response()->json([
                 'status' =>'success',
                 'brand' => $brand,
             ]);

        } catch (\Throwable $th) {
            DB::rollback();
           Log::error($th);
            return response()->json([
               'status' => 'error',
               'message' => 'Brand could not be created',
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return response()->json([
            'status' =>'success',
            'brand' => $brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'slogan' => 'nullable|string|max:255',
        ]);
        $brand->update([
            'name' => $request->name,
           'slogan' => $request->slogan,
        ]);
        return response()->json([
           'status' =>'success',
            'brand' => $brand,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
    }
}
