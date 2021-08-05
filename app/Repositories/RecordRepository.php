<?php

namespace App\Repositories;

use App\Models\Record;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RecordRepository
{
    public function store($data, $password): Record
    {
        $record = new Record($data);
        $record->identifier = Str::random(16);
        $record->password = Hash::make($password);
        $record->data = $data['data'];
        $record->save();
        return $record;
    }
}
