<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;

use App\Models\File\Enums\filePaths;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class fileController extends Controller
{
    public function uploadFiles(FileUploadRequest $request)
    {
        $file = $request->file('file');
        $templatePath = $request->template;





        if (!$file || !$file->isValid()) {
            return response()->json(['error' => 'Invalid file'], 400);
        }

        $path = $file->store('public');
        $url = Storage::url($path);

        $template = Storage::get('public/templates/'.$templatePath);

        $imageHtml = $this->generateImagesHtml($url, $templatePath);

        $template = Str::replaceFirst('{image_urls}', $imageHtml, $template);

        $filename = 'uploaded_template.html';
        Storage::put('public/' . $filename, $template);

        return $this->generateDownloadableResponse($template, $filename);
//        return $template;
    }

    private function generateImagesHtml($url, $template)
    {
        $imageUrls = '';

        if ($template == filePaths::TEMPLATE_3->value) {
            // Generate four image URLs for template_3
            for ($i = 1; $i <= 4; $i++) {
                $imageUrls .= 'background: url("http://127.0.0.1:8000' . $url . '")';
                if ($i < 4) {
                    $imageUrls .= '; ';
                }
            }
        } else {
            // Generate a single image URL for other templates
            $imageUrls = 'background-image: url("http://127.0.0.1:8000' . $url . '");';
        }

        return $imageUrls;
    }

    private function generateDownloadableResponse($content, $filename)
    {
        $response = new Response($content);
        $response->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        return $response;
    }
}
