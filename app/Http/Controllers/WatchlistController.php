<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WatchlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function watchlists(): LengthAwarePaginator
    {
        /** @var $user User */
        $user = auth()->user();

        return $user->watchlists()->paginate(1);
    }

    public function watchlist(Watchlist $watchlist): Watchlist
    {
        return $watchlist;
    }

    public function store(Request $request): Model|JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            "name" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 500);
        }

        return $user->watchlists()->create($validator->validated());
    }

    public function update(Watchlist $watchlist, Request $request)
    {
        /** @var $user User */
        $user = auth()->user();

        if($watchlist->user()->id !== $user->id){
            return response()->json(['error' => 'Invalid action'], 403);
        }

        $validator = Validator::make($request->all(), [
            "name" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 500);
        }

        if(!$watchlist->update($validator->validated())){
            return response()->json(['error' => 'Watchlist could not be updated'], 500);
        }

        return response()->json($watchlist);
    }

    public function delete(Watchlist $watchlist): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        if($watchlist->user->id !== $user->id){
            return response()->json(['error' => 'Invalid action'], 403);
        }

        if(!$watchlist->delete()){
            return response()->json(['error' => 'Watchlist could not be deleted'], 500);
        }

        return response()->json([]);
    }


}
