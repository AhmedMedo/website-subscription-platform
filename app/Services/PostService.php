<?php

namespace App\Services;

use App\Repository\Interfaces\PostRepositoryInterface;
use Illuminate\Http\UploadedFile;

class PostService
{

    public function __construct(private readonly PostRepositoryInterface $postRepository)
    {
    }

    public function createPost(array $data, ?UploadedFile $file)
    {
        return $this->postRepository->create($data, $file);
    }

}
