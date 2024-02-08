<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WatchlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function watchlists(): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        return response()->json($user->watchlists()->paginate(25));
    }

    public function watchlist(int $watchlist_id): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        $watchlist = $user->watchlists()->with('items')->find($watchlist_id);

        if(!$watchlist){
            return response()->json(['message' => 'Watchlist does not exist'], 404);
        }

        return response()->json($watchlist);
    }

    public function store(Request $request): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            "name" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $watchlist = $user->watchlists()->create($validator->validated());

        return response()->json($watchlist, 201);
    }

    public function update(int $watchlist_id, Request $request): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        /** @var $watchlist Watchlist */
        $watchlist = $user->watchlists()->find($watchlist_id);

        if(!$watchlist){
            return response()->json(['error' => 'Watchlist does not exist'], 404);
        }

        $validator = Validator::make($request->all(), [
            "name" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if(!$watchlist->update($validator->validated())){
            return response()->json(['message' => 'Watchlist could not be updated'], 500);
        }

        return response()->json($watchlist);
    }

    public function delete(int $watchlist_id): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        /** @var $watchlist Watchlist */
        $watchlist = $user->watchlists()->find($watchlist_id);

        if(!$watchlist){
            return response()->json(['message' => 'Watchlist does not exist'], 404);
        }

        if(!$watchlist->delete()){
            return response()->json(['message' => 'Watchlist could not be deleted'], 500);
        }

        return response()->json(status: 204);
    }


}
