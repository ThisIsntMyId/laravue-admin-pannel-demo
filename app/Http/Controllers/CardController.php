<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Requests\CsvFileValidatorRequest;
use App\Imports\CardsImport;
use Exception;
use Illuminate\Http\Request;
use Validator;
use Maatwebsite\Excel\Facades\Excel;

class CardController extends Controller
{

    private function validateData(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric',
                'cardCategory_id' => 'exists:card_categories,id',
            ]
        );
        if ($validator->fails()) {
            return ['errors' => $validator->messages(), 'data' => ''];
        } else {
            return ['errors' => '', 'data' => $request->all()];
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Card::paginate(10);
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
            return Card::create($data['data']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        return $card;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        $data = $this->validateData($request);
        if ($data['errors'])
            return response()->json($data, 403);
        else {
            $status = $card->update($data['data']);
            if ($status)
                return $card;
            else
                return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        try {
            $card->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    public function fromcsv(CsvFileValidatorRequest $request)
    {
        $csvfile = $request->file('csvfile')->getRealPath();
        Excel::import(new CardsImport, $csvfile);        
    }
}
