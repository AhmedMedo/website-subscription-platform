<?php

namespace App\Libraries\Base\Http;

use App\Libraries\Support\UploadedFileHelper;
use BadMethodCallException;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

abstract class Handler extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @param  string  $method
     * @param  array  $parameters
     * @return Response|array
     */
    public function callAction($method, $parameters)
    {
        if ($method !== '__invoke') {
            throw new BadMethodCallException('Only __invoke method can be called on handler.');
        }

        return $this->{$method}(...array_values($parameters));
    }

}
