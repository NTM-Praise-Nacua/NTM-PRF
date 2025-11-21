<?php

namespace App\Services;

use App\Models\RequestType;

class RequestTypeService
{
    public function getRequestType()
    {
        $data = RequestType::all();

        return $data;
    }
}

