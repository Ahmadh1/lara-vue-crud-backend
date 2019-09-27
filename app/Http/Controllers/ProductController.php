<?php

namespace App\Http\Controllers;
use Auth;
use App\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	
	function __construct(){
		$this->middleware('auth:api');
	}
    public function index() {
    	return Product::orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request) {
        // \Log::info($request->all());
        $exploded = explode(',', $request->image);
        $decoded = base64_decode($exploded[1]);
        if (Str::contains($exploded[0], 'jpeg')) 
            $extension = 'jpg';
        else 
            $extension = 'png';
        $filename = Str::random().'.'.$extension;
        $path = public_path().'/images/'.$filename;
        file_put_contents($path, $decoded);
    	$product = Product::create($request->except('image') + 
            [
                'user_id' => Auth::id(),
                'image' => $filename
        ]);
    	return $product;
    }

    public function show($id) {
        return response()->json(Product::find($id));
    }

    public function update(Request $req, $id) {
        $pro = Product::find($id);
        $pro->update($req->all());
        return response()->json($pro);
    }
    public function destroy($id) {
        try {
            Product::destroy($id);
            return response([], 204);
        } catch (\Exception $e) {
            return response(['Something went wrong :( ', 500]);
        }
    }
}
