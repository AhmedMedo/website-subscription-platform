<?php

namespace App\Repository\Interfaces;

use Illuminate\Http\UploadedFile;

interface PostRepositoryInterface
{
    public function create(array $data,?UploadedFile $file);

}
