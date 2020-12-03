<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Constants as Constant;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEntry;
use App\Http\Resources\Entry as EntryResource;
use App\Entry;

// TO DO: return proper and standard http codes

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * returns an array of Entry objects
     */
    public function index()
    {
        // try {
        //     return EntryResource::collection(
        //         Entry::ownedByUser()->get()
        //     );
        // } catch (ModelNotFoundException $e) {
        //     return response([
        //         'message' => Constant::MSG_NO_DATA
        //     ]);
        // }

        $entries = EntryResource::collection(Entry::ownedByUser()->get());

        if (count($entries) <= 1) {
            return response([
                'message' => Constant::MSG_NO_DATA
            ], 204);
        }

        return $entries;
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
        $entry = new Entry();
        $entry->entry = $request->all();
        $entry->created_by = auth()->user()->id;
        $entry->save();

        return response([
            'message' => Constant::MSG_ADD_SUCCESS
        ]);
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
            return new EntryResource(
                Entry::ownedByUser()->findOrFail($id)
            );
        } catch (ModelNotFoundException $e) {
            return response([
                'message' => Constant::MSG_NO_DATA
            ]);
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
            $entry = Entry::ownedByUser()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response([
                'message' => Constant::MSG_NO_DATA_MATCH
            ]);
        }

        $entry->entry = json_encode($request->all());
        $entry->save();

        return response([
            'message' => Constant::MSG_EDIT_SUCCESS
        ]);
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
            $entry = Entry::ownedByUser()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response([
                'message' => Constant::MSG_NO_DATA_MATCH
            ]);
        }

        $entry->delete();
        return response([
            'message' => Constant::MSG_DEL_SUCCESS
        ]);
    }
}
