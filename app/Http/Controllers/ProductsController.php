<?php

namespace App\Http\Controllers;

use App\Laravel\Models\Products;
use Exception;
use Illuminate\Http\Request;
// use Illuminate\Validation\Validator;
use Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function validateData(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'brand' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric',
                'ratings' => 'integer|lte:5|gte:0',
                'category_id' => 'exists:categories,id',
                'quantity' => 'integer',
                'date_of_purchase' => 'date | date_format:Y-m-d',
                'condition' => 'in:new,moderate,used,old,not_working',
                'reviewed' => 'boolean',
                'available_in' => [function ($attribute, $value, $fail) {
                    if (array_intersect(explode(',', $value), ['Surat', 'Pune', 'Banglore', 'Delhi']) != explode(',', $value)) {
                        return $fail('City must be selected from Surat, Pune, Banglore, Delhi');
                    }
                }],
            ]
        );
        if ($validator->fails()) {
            return ['errors' => $validator->messages(), 'data' => ''];
        } else {
            return ['errors' => '', 'data' => $request->all()];
        }
    }

    public function index(Request $request)
    {
        if($request->query('name')){
            $products = Products::where('name', 'like', '%' . $request->query('name') . '%')->paginate(25);
            return response()->json($products, 200);
        }

        $products = Products::paginate(25);
        return response()->json($products, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($data['errors'])
            return response()->json($data, 403);
        else {
            $product = Products::create($data['data']);
            return response()->json($product, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravel\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products)
    {
        return response()->json($products, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravel\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravel\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $products)
    {
        $data = $this->validateData($request);
        if ($data['errors'])
            return response()->json($data, 403);
        else {
            $status = $products->update($request->all());
            return response()->json($products, 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravel\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $products)
    {
        try{
            $products->delete();
        } catch(Exception $ex){
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
