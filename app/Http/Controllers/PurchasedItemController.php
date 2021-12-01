<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchasedItem;
use App\Http\Resources\PurchasedItemCollection;

class PurchasedItemController extends Controller
{
        /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->authorizeResource(PurchasedItem::class, 'purchased-item');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = PurchasedItem::query();
        $items = $this->filter($items, $request);
        return new PurchasedItemCollection($items->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(PurchasedItem::$rules);
        $item = PurchasedItem::create($validated);
        return response()->json([
            'data' => $item,
            'message' => 'create_success',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(PurchasedItem $purchasedItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PurchasedItem  $purchasedItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchasedItem $purchasedItem)
    {
        $validated = $request->validate(PurchasedItem::$rules);
        $purchasedItem->update($validated);
        return response()->json([
            'message' => 'update_success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PurchasedItem  $purchasedItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchasedItem $purchasedItem)
    {
        $purchasedItem->delete();
        return response()->json([
            'message' => 'delete_success',
        ]);
    }
}
