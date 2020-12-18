<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Resources\Entry as EntryResource;
use App\Constants as Constant;
use App\Entry;
use App\Car;
use App\User;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * returns an array of Entry objects
     */
    public function index($car)
    {
        try {
            return EntryResource::collection(
                Entry::ofCar($car)->get()
            );
        } catch (QueryException $e) {
            return response()->json(
                [
                    'message' => Constant::MSG_DB_ERROR
                ],
                Constant::HTTP_CODE_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $car)
    {
        // removed request validation
        // as the whole payload will be stored as json
        try {
            $entry = new Entry();
            $entry->entry = $request->all();
            $entry->created_by = auth()->user()->id;
            $entry->car_id = $car;
            $entry->save();

            return response(
                [
                    'message' => Constant::MSG_ADD_SUCCESS
                ],
                Constant::HTTP_CODE_CREATED
            );
        } catch (QueryException $e) {
            return response(
                [
                    'message' => Constant::MSG_DB_ERROR
                ],
                Constant::HTTP_CODE_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($car, $entry)
    {
        try {
            return new EntryResource(
                Entry::ofCar($car)->findOrFail($entry)
            );
        } catch (ModelNotFoundException $e) {
            // catch if no matching ID
            return response(
                [
                    'message' => Constant::MSG_NO_DATA_MATCH
                ],
                Constant::HTTP_CODE_NOT_FOUND
            );
        } catch (QueryException $e) {
            // catch query errors
            return response(
                [
                    'message' => Constant::MSG_DB_ERROR
                ],
                Constant::HTTP_CODE_SERVER_ERROR
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $car, $entry)
    {
        try {
            $entry = Entry::ofCar($car)->findOrFail($entry);
            $entry->entry = json_encode($request->all());
            $entry->save();

            // successfully updated
            return response([], Constant::HTTP_CODE_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            // catch if no matching ID
            return response(
                [
                    'message' => Constant::MSG_NO_DATA_MATCH
                ],
                Constant::HTTP_CODE_NOT_FOUND
            );
        } catch (QueryException $e) {
            // catch query errors
            return response(
                [
                    'message' => Constant::MSG_DB_ERROR
                ],
                Constant::HTTP_CODE_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($car, $entry)
    {
        try {
            $entry = Entry::ofCar($car)->findOrFail($entry);
            $entry->delete();

            // successfully deleted
            return response([], Constant::HTTP_CODE_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            // catch if no matching ID
            return response(
                [
                    'message' => Constant::MSG_NO_DATA_MATCH
                ],
                Constant::HTTP_CODE_NOT_FOUND
            );
        } catch (QueryException $e) {
            // catch query errors
            return response(
                [
                    'message' => Constant::MSG_DB_ERROR
                ],
                Constant::HTTP_CODE_SERVER_ERROR
            );
        }
    }
}
