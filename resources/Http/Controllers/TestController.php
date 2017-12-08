<?php

namespace App\Http\Controllers;

class TestController
{
    public function test()
    {
        wp_send_json(['data' => 'It works']);
    }
}
