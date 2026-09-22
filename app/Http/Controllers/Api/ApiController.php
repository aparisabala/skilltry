<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    public function callAction($method, $parameters)
    {
        $response = parent::callAction($method, $parameters);
        if ($response instanceof JsonResponse) {
            $response->setData($this->publicData($response->getData(true)));
        }
        return $response;
    }

    private function publicData($value)
    {
        if ($value instanceof \Illuminate\Contracts\Support\Arrayable) {
            $value = $value->toArray();
        }
        if (!is_array($value)) {
            return $value;
        }
        foreach ($value as $key => $item) {
            if (in_array($key, ['password', 'remember_token', 'reset_code', 'sent_at'], true)) {
                unset($value[$key]);
            } else {
                $value[$key] = $this->publicData($item);
            }
        }
        return $value;
    }
}
