<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Watchlist;
use App\Models\WatchlistItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WatchlistItemController extends Controller
{
    public function items(int $watchlist_id): LengthAwarePaginator|JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        /** @var $watchlist Watchlist */
        $watchlist = $user->watchlists()->find($watchlist_id);

        if(!$watchlist){
            return response()->json(['error' => 'Watchlist does not exist'], 404);
        }

        return response()->json($watchlist->items()->paginate(25));
    }

    public function item(int $item_id): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        /** @var $item WatchlistItem */
        $item = WatchlistItem::query()->find($item_id);

        if(!$item){
            return response()->json(['message' => 'Watchlist-Item does not exist'], 404);
        }

        if($item->watchlist->user->id !== $user->id){
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($item);
    }

    public function store(int $watchlist_id, Request $request): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        /** @var $watchlist Watchlist */
        $watchlist = $user->watchlists()->find($watchlist_id);

        if(!$watchlist){
            return response()->json(['error' => 'Watchlist does not exist'], 404);
        }

        $validator = Validator::make($request->all(), [
            // "media_id" => ["string"],
            "name" => ["required", "string"],
            "season" => ["numeric"],
            "episode" => ["numeric"],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $item = $watchlist->items()->create(array_merge(
            $validator->validated(),
            ['watchlist_id' => $watchlist_id]
        ));

        return response()->json($item, 201);
    }

    public function update(int $item_id, Request $request): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        /** @var $item WatchlistItem */
        $item = WatchlistItem::query()->find($item_id);

        if(!$item){
            return response()->json(['message' => 'Watchlist-Item does not exist'], 404);
        }

        if($item->watchlist->user->id !== $user->id){
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            // "media_id" => ["string"],
            "name" => ["string"],
            "season" => ["numeric"],
            "episode" => ["numeric"],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if(!$item->update($validator->validated())){
            return response()->json(['message' => 'Watchlist-Item could not be updated'], 500);
        }

        return response()->json($item);
    }

    public function delete(int $item_id): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        /** @var $item WatchlistItem */
        $item = WatchlistItem::query()->find($item_id);

        if(!$item){
            return response()->json(['message' => 'Watchlist-Item does not exist'], 404);
        }

        if($item->watchlist->user->id !== $user->id){
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if(!$item->delete()){
            return response()->json(['message' => 'Watchlist-Item could not be deleted'], 500);
        }

        return response()->json(status: 204);
    }
}
