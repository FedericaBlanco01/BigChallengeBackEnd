<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Http\Requests\DigitalOceanDeleteRequest;
use App\Http\Requests\DigitalOceanStoreRequest;
use App\Http\Requests\DigitalOceanUpdateRequest;
use App\Services\CdnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DOSpacesController extends Controller
{
    private $cdnService;

    public function __construct(CdnService $cdnService)
    {
        $this->cdnService = $cdnService;
    }

    public function store(DigitalOceanStoreRequest $request): JsonResponse
    {
        $file = $request->asFile('doctorProfileImageFile');
        $fileName = (string) Str::uuid();
        $folder = config('filesystems.disks.do.folder');

        Storage::disk('do')->put(
            "{$folder}/{$fileName}",
            file_get_contents($file)
        );

        return response()->json(['message' => 'File uploaded'], 200);
    }

    public function delete(DigitalOceanDeleteRequest $request)
    {
        $fileName = $request->validated()['doctorProfileImageFileName'];
        $folder = config('filesystems.disks.do.folder');

        Storage::disk('do')->delete("{$folder}/{$fileName}");
        $this->cdnService->purge($fileName);

        return response()->json(['message' => 'File deleted'], 200);
    }

    public function update(DigitalOceanUpdateRequest $request)
    {
        $file = $request->asFile('doctorProfileImageFile');
        $fileName = $request->validated()['doctorProfileImageFileName'];
        $folder = config('filesystems.disks.do.folder');

        Storage::disk('do')->put(
            "{$folder}/{$fileName}",
            file_get_contents($file)
        );
        $this->cdnService->purge($fileName);

        return response()->json(['message' => 'File updated'], 200);
    }
}
