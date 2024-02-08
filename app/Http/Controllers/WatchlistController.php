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

        return $user->watchlists()->paginate(25);
    }

    public function watchlist(int $id): Watchlist|JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        $watchlist = $user->watchlists()->find($id);

        if(!$watchlist){
            return response()->json(['error' => 'Watchlist does not exist'], 404);
        }

        return response()->json($watchlist);
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

    public function update(int $id, Request $request): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        /** @var $watchlist Watchlist */
        $watchlist = $user->watchlists()->find($id);

        if(!$watchlist){
            return response()->json(['error' => 'Watchlist does not exist'], 404);
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

    public function delete(int $id): JsonResponse
    {
        /** @var $user User */
        $user = auth()->user();

        /** @var $watchlist Watchlist */
        $watchlist = $user->watchlists()->find($id);

        if(!$watchlist){
            return response()->json(['error' => 'Watchlist does not exist'], 404);
        }

        if(!$watchlist->delete()){
            return response()->json(['error' => 'Watchlist could not be deleted'], 500);
        }

        return response()->json(status: 204);
    }


}
