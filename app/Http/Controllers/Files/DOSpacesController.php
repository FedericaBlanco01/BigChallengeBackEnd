<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Http\Requests\DigitalOceanStoreRequest;
use App\Models\Submission;
use App\Notifications\PrescriptionUploaded;
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

        Storage::put(
            "{$folder}/{$fileName}",
            file_get_contents($file)
        );

        $url = Storage::temporaryUrl(
            "{$folder}/{$fileName}",
            now()->addMinutes(40)
        );

        $submission->status = Submission::DONE_STATUS;
        $submission->file_path = $url;
        $submission->save();

        $user = $submission->load('patient')->patient;
        $user->notify(new PrescriptionUploaded($submission));

        return response()->json(['message' => 'File uploaded', 'name'=> $submission->file_path, 'submission'=> $submission]);
    }
}
