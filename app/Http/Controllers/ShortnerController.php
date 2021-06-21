<?php

namespace App\Http\Controllers;

use App\Models\Shortners;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\ShortnersResource;
use Illuminate\Support\Facades\Validator;

class ShortnerController extends Controller
{
	public function add(Request $request){
		$validator = Validator::make($request->all(), [
			'url' => ['required', 'active_url']
		], [
			'url.required' => 'O campo URL é de preenchimento obrigatório.',
			'url.active_url' => 'Parece que essa URL não é válida, ou esse site não existe.',
		]);

		if ($validator->fails()) {
			return response()->json([
				'validation' => $validator->errors()
			], 404);
		}

		$shortner = new Shortners;
		$shortner->url_long = $request->input('url');
		$shortner->expire = now()->addDays(Config::get('app.expire_limit'))->format('Y-m-d');
		$shortner->save();

		return response()->json([
			'response' => new ShortnersResource($shortner)
		], 200);
	}

	public function redirectToUrl($hash, Request $request){
		$id = Hashids::decode($hash);
		$validator = Validator::make(['id' => $id], [
			'id' => ['required', Rule::exists('shortners')->where(function ($query) use($id) {
            		return $query->where('id', $id)->where('expire', '>=', now());
    		    })
			],
		], [
			'id.exists' => 'Parece que essa URL não existe, ou expirou!'
		]);

		if ($validator->fails()) {
			return response()->json([
				'validation' => $validator->errors()
			], 404);
		}

		$shortner = Shortners::where('id', $id)->first();
		$shortner->clicks = $shortner->clicks + 1;
		$shortner->save();

		return redirect()->away($shortner->url_long);
	}
}