<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Constants as Constant;
use App\Car;
use App\Http\Resources\Car as CarResource;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return CarResource::collection(
                Car::with('entries')->ownedByUser()->get()
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
    public function store(Request $request)
    {
        // removed request validation
        // as the whole payload will be stored as json
        try {
            $car = new Car();
            $car->details = $request->all();
            $car->created_by = auth()->user()->id;
            $car->save();

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
    public function show($id)
    {
        try {
            return new CarResource(
                Car::with('entries')->ownedByUser()->findOrFail($id)
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
    public function update(Request $request, $id)
    {
        try {
            $car = Car::with('entries')->ownedByUser()->findOrFail($id);
            $car->details = json_encode($request->all());
            $car->save();

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
    public function destroy($id)
    {
        try {
            $car = Car::with('entries')->ownedByUser()->findOrFail($id);
            $car->delete();

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
