<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestguestApitest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //guest api : signup
    public function test_can_signup_customer()
    {
        $formdata =[
            'email' => 'wc2014920@gmail.com',
            'phone' => '0955711581',
            'address' => '基隆市中正區中正路87號',
            'password' => '123456'
        ];
        $this->post(route('customer.signup'),$formdata)
            ->assertStatus(200);
            
    }

    // public function test_can_signup_delivery_man()
    // {
    //     $formdata =[
    //         'email' => 'wc2014920@gmail.com',
    //         'phone' => '0955711581',
    //         'address' => '基隆市中正區中正路87號',
    //         'password' => '123456'
    //     ];
    // }

    // public function test_can_signup_restaurant()
    // {
    //     $formdata =[
    //         'email' => 'wc2014920@gmail.com',
    //         'phone' => '0955711581',
    //         'address' => '基隆市中正區中正路87號',
    //         'password' => '123456'
    //     ];
    // }
    //guest api : login
    // public function test_can_login_delivery_man()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    // public function test_can_login_restaurant()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    // public function test_can_login_customer()
    // {
    //     $formdata =[
    //         'email' => 'wc2014920@gmail.com',
    //         'password' => '123456',
    //         'device_name' => '123' 
    //     ];
    //     $response = $this->post(route('customer.signup'),$formdata)
    //         ->assertStatus(200);
    //     // 測試回傳欄位是否存在
    //     $data = json_decode($response->getContent(), true);
    //     $this->assertArrayHasKey('access_token', $data);
    // }
    //評價
    public function test_can_searchbyKeyword_customer()
    {
        $token = 'Bearer'.' 1|JmYrswbHhyjfhaEj1P7do3pE9mr0qwzNvm2xtMBy';
        $formdata =[
            'Keyword' => '鮮',
        ];
        $response = $this->post(route('customer.searchByKeyword'),$formdata)
            ->assertStatus(200);
        // 測試回傳欄位是否存在
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('access_token', $data);
        print_r($response);
        echo $response;
        print("12345678");
    }
}
