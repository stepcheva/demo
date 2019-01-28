<?php

namespace App\Http\Controllers\Admin;

use App\Models\Market;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = Market::orderBy('id')->paginate(20);
        return view('admin.contents.list', ['items' => $items]);
    }

    public function store(Request $request)
    {
        $file = $request->file;
        $first_id = $this->changeMarkets($file);

        $markets =  Market::all();
        $markets->map(function ($item) use ($first_id) {
            if ($item->id < $first_id)
                return $item->delete();
        });
        session()->flash('success', 'Новые магазины успешно загружены.');
        return redirect()->route('contents.index');
    }

    public function destroy($id)
    {
        Market::destroy($id);
        session()->flash('success', 'Магазин успешно удален.');
        return redirect()->route('contents.index');
    }

    public function changeMarkets($file)
    {
        $handle = fopen($file, "r");
        $count = -1;
        while (($line = fgetcsv($handle, 0, ";")) !== false) {
            $count++;
            $long = explode(',', iconv("CP1251", "UTF-8", $line[4]));
            $long = (double)implode('.',$long);
            $lat = explode(',', iconv("CP1251", "UTF-8", $line[3]));
            $lat = (double)implode('.',$lat);

            $market = Market::create([
                'address' => iconv("CP1251", "UTF-8", $line[2]),
                'city_name' => iconv("CP1251", "UTF-8", $line[1]),
                'lat' => $lat,
                'lng' => $long,
                'type' => $line[5],
            ]);
        }
        $first_id = $market->id - $count;
        return  $first_id;
    }
}
