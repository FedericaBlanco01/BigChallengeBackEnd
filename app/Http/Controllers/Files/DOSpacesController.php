<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Http\Requests\DigitalOceanDeleteRequest;
use App\Http\Requests\DigitalOceanStoreRequest;
use App\Http\Requests\DigitalOceanUpdateRequest;
use App\Models\Submission;
use App\Services\CdnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DOSpacesController extends Controller
{
    
    public function store(DigitalOceanStoreRequest $request, Submission $submission): JsonResponse
    {
        $file = $request->File('doctorProfileImageFile');
        $fileName = (string) Str::uuid();
        $folder = config('filesystems.disks.do.folder');

        Storage::disk('do')->put(
            "{$folder}/{$fileName}",
            file_get_contents($file)
        );
        $submission->status= Submission::DONE_STATUS;
        return response()->json(['message' => 'File uploaded', 'name'=> $fileName], 200);
    }
}
