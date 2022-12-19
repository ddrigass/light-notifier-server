<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class BoardController extends Controller
{
    private $rules = [
        'external_id' => 'string|required',
        'chat_id' => 'string|required'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function index()
    {
        return view('frontend.board.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $board = new Board();
        $board->save();
        return redirect()->back()->withFlashSuccess('The board created.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit(Board $board)
    {
        return view('frontend.board.edit')->withBoard($board);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        $data = $request->validate($this->rules);
        $board->update($data);
        return redirect()->route('frontend.board.index')->withFlashSuccess('The board changed.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        $board->delete();
        return redirect()->back()->withFlashSuccess('The board deleted.');
    }

    public function updateActivity($boardExternalId) {
        $token = request()->header('X-Token', null);
//        if ($token != "PAq4B0riExaQfjWxrruVs75QhTEuRm7SF9ojY4J4") {
//            return [
//                'success' => false,
//                'message' => 'Bad token'
//            ];
//        }
        $board = Board::whereExternalId($boardExternalId)->first();
        $board->last_activity = Carbon::now();
        if (!$board->active) {
            $board->active = true;
        }
        $board->save();
    }
}
