<?php

namespace App;

use App\Traits\StandardEnum;

enum StatusEnum:string
{
    use StandardEnum;
    //
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
