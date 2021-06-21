<?php

namespace App\Http\Resources;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortnersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $host = Config::get('app.url');
        $hash = Hashids::encode($this->id);
    
        return [
            'url_long' => $this->url_long,
            'url_short' => $host . '/' . $hash,
            'expire' => $this->expire
        ];
    }
}

