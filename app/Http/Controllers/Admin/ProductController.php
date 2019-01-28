<?php

namespace App\Http\Controllers\Admin;

use App\Models\Partner;
use App\Models\Productgroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $model =  $this->getModel($request->param);
        $items = $model->orderBy('name','asc')->get();
        return view('admin.products.list', ['items' => $items, 'param' => $request->param]);
    }

    public function getModel($param)
    {
        return $param === 'products' ? new Partner() : new Productgroup();
    }

    public function store(Request $request)
    {

        if ($request->hasFile('file')) {
            $validator = Validator::make($request->all(), [
                'file' => 'mimes:csv,txt',
            ], [
                'mimes' => 'Допускается файл формата .csv',
            ]);

            if ($validator->passes()) {
                $model = $this->getModel($request->param);
                $filename = "$model->prefix.csv";
                $path = $this->uploadFile($request->file, $filename);
                if ($model instanceof Partner) {
                    $this->updatePartner($path, $model);
                } else {
                    $this->updateProductgroups($path, $model);
                }
            } else {
                session()->flash('error', 'Ошибочный файл');
            }
        }
        return redirect()->route('products.index',['param' => $request->param]);
    }

    public function updatePartner($path, $model)
    {
        try {
            $until_id = $this->createNewProductsInDB(Storage::get($path), $model);
        } catch (\Exception $e) {
            session()->flash('error', 'Файл не валиден!');
            return redirect()->route('products.index', ['param' => $model->prefix]);
        }
        $this->deleteProducts($model, $until_id);
        return session()->flash('success', 'Новый список товаров успешно загружен');
    }

    public function updateProductgroups($path, $model)
    {
        $this->deleteProductgroups();
        $this->createNewProductsInDB(Storage::get($path), $model);
        return session()->flash('success', 'Новый список категорий успешно загружен');
    }

    public function uploadFile($file, $name)
    {
        $data = Carbon::now()->format('d-M-y');
        $path = $file->storeAs("upload/files/$data", $name);
        return $path;
    }

    public function deleteProducts($model, $until_id)
    {
        return ($model instanceof Partner) ? $this->deletePartners($until_id) : $this->deleteProductgroups();
    }

    public function deletePartners($until_id)
    {
        $products = Partner::where('id','<', $until_id)->get();
        $products->map(function ($item) {
            $key_prefix = $item->prefix;
            $this->delFromRedis($item->name, $key_prefix);
            return $item->delete();
        });
        return true;
    }

    public function deleteProductgroups()
    {
        $products = Productgroup::get();
        $products->map(function ($item) {
            $key_prefix = $item->prefix;
            $this->delFromRedis($item->name, $key_prefix);
            return $item->delete();
        });
        return true;
    }

    public function delFromRedis($string, $key_prefix)
    {
        $key = mb_substr($string, 0, 30);
        Cache::forget("$key_prefix:$key");
        return true;
    }

    public function uploadImage(Partner $partner, Request $request)
    {
        if ($request->hasFile('image')) {
            $validator = Validator::make($request->all(), [
                'image' => 'mimes:png,jpeg,gif',
            ], [
                'mimes' => 'Допускается файл формата png, jpeg, gif',
            ]);

            if ($validator->passes()) {
                $file = $request->file('image');
                $imagePath = $file->store("upload/partners");
                $image = Image::make(Storage::get($imagePath));
                $image->fit(120)->encode('png');
                Storage::disk('public')->put($imagePath, $image);

                $partner->update([
                    'image' => env('APP_IMAGE_URL') . $imagePath,
                ]);

                session()->flash('success', 'Изображение успешно загружено');

            } else session()->flash('error', 'Не верный формат файла');

        } else session()->flash('error', 'Файл отсутствует');

        return redirect()->route('products.index', ['param' => 'products']);
    }

    public function destroy($id)
    {
        $item = Partner::find($id);
        $this->delFromRedis($item->name, $item->prefix);
        Partner::destroy($id);
        session()->flash('success', 'Товар/категория успешно удален/а.');
        return redirect()->route('products.index', ['param' => 'products']);
    }

    public function createNewProductsInDB($file, $model)
    {
        //setlocale(LC_ALL, 'ru_RU.UTF-8');
        $list = str_getcsv($file, "\n");
        $count = -1;
            //foreach ($list as &$row) $row = str_getcsv($row, ";");
            foreach ($list as $row) {
                $productname = explode(':', $row);
                $count++;
                if ($model instanceof Partner) {
                    $product = $model->create([
                        'name' => $productname[0],
                        'points' => 4,
                        'image' => env('APP_IMAGE_URL') . 'upload/partners/default.png',
                    ]);
                } else {
                    $product = Productgroup::create([
                        'name' => $productname[0],
                        'points' => 1,
                    ]);
                }
            }

        $first_id = $product->id - $count;
        return  $first_id;
    }
}
