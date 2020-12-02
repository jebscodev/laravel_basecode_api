<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEntry;
use App\Http\Resources\Entry as EntryResource;
use App\Entry;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EntryResource::collection(Entry::all());
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
        $payload = json_encode($request->all());

        $entry = new Entry();
        $entry->entry = $payload;
        $entry->created_by = auth()->user()->id;
        $entry->save();

        return response([
            'message' => 'Successfully added new entry.'
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
        return new EntryResource(Entry::findOrFail($id));
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
        $payload = json_encode($request->all());

        $entry = Entry::findOrFail($id);
        $entry->entry = $payload;
        $entry->save();

        return response([
            'message' => 'Successfully updated entry.'
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
        $entry = Entry::findOrFail($id);
        $entry->delete();

        return response([
            'message' => 'Successfully deleted entry.'
        ]);
    }
}
