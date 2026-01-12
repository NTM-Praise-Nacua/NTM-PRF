<?php

namespace App\Services;

use App\Models\RequestType;

class RequestTypeService
{
    public function getRequestType()
    {
        $data = RequestType::orderBy('id','asc')->get();

        return $data;
    }
}

