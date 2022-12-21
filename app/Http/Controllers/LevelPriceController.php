<?php

namespace App\Http\Controllers;

use App\Models\LevelPrice;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelPriceController extends Controller
{
    public function index()
    {
        $levels = Level::all();
        $level_price = LevelPrice::all();
        return view("admin.level_price", ["level_price" => $level_price, "levels" => $levels]);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'level_id' => 'required',
            'year' => 'required',
            'hour_price' => 'required',
        ]);
        $level_price = LevelPrice::create([
            'level_id' => $request->level_id,
            'hour_price' =>  $request->hour_price,
            'year' =>  $request->year,
        ]);
    }
    public function update(Request $request, $id)
    {
        $level_price = LevelPrice::find($id);
        $this->validate($request, [
            'level_id' => 'required',
            'year' => 'required',
            'hour_price' => 'required',
        ]);
        $level_price->level_id = $request->level_id;
        $level_price->hour_price = $request->hour_price;
        $level_price->year = $request->year;
        $level_price->save();
    }
    public function destroy($id)
    {
        $level_price = LevelPrice::find($id);
        $level_price->delete();
    }
}
