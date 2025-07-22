<?php
namespace StacGate\Models;

use StacGate\Core\BaseModel;

class TestModel extends BaseModel
{
    public static function users(): array
    {
        return self::all('users'); // suppose une table 'users'
    }
}
