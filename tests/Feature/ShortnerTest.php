<?php

namespace Tests\Feature;

use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortnerTest extends TestCase
{
	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_add_url_success()
	{
		$response = $this->post('/', ['url' => 'http://studos.com.br']);
		$response->assertStatus(200);
	}

	public function test_add_url_error_404()
	{
		$response = $this->post('/', ['url' => 'http://studos.com.br2']);
		$response->assertStatus(404);
	}

	public function test_get_url_success(){
		$item = Shortners::first();
		$response = $this->get(Config::get('app.url') . '/' . Hashids::encode($item->id));
		$response->assertStatus(302);
	}

	public function test_get_url_error(){
		$response = $this->get(Config::get('app.url') . '/hash-incorreto');
		$response->assertStatus(404);
	}
}
