<?php

namespace App\Http\Controllers;

class TestController
{
    public function test()
    {
        wp_send_json(['data' => 'It works']);
    }

    public function show(\WP_REST_Request $request)
    {
        $params = $request->get_params();
        wp_send_json(['data' => [
            'message' => 'That\'s awesome! I hear somethings from you.',
            'params'  => $params,
        ]]);
    }
}
