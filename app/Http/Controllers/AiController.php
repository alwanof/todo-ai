<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class AiController extends Controller
{
    public function transcribe(Request $request)
    {
        // 1. validate the audiofile in request
        if (!$request->hasFile('audioFile')) {
            return response()->json(['error' => 'No audio file uploaded'], 400);
        }
        // 2. store the audiofile in storage/app/public/ai
        $filePath = $this->storeFile($request->file('audioFile'));
        $fullPath = Storage::disk('public')->path($filePath);

        // 3. transcribe the audiofile
        $result = $this->transcribeAudio($fullPath);
        // 4. return the transcription
        return response()->json($result->text);
    }

    private function storeFile($file)
    {
        $filePath = $file->store('public/ai');
        return str_replace('public/', '', $filePath);
    }

    private function transcribeAudio($file)
    {
        return OpenAI::audio()->transcribe([
            'model' => 'whisper-1',
            'file' => fopen($file, 'r'),
            'response_format' => 'verbose_json',
        ]);
    }

    public function arabicTransform(Request $request)
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => 'rewrite following in one sentence,
the response language should be in official Arabic: \n ' . $request->text
                ],
            ],
        ]);
        return $result['choices'][0]['message']['content'];
    }
}
