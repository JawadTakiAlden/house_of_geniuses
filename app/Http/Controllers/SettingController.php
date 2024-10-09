<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SettingController
{
    private $jsonFilePath;

    public function __construct()
    {
        $this->jsonFilePath = storage_path('app/public/screenshot.json');
    }
    public function switchScreenshot()
    {
        $data = $this->getJsonData();

        if (isset($data['screenshot'])) {
            $data['screenshot'] = !$data['screenshot'];

            $this->saveJsonData($data);

            return response()->json([
                'success' => true,
                'message' => 'Screenshot setting updated successfully.',
                'new_value' => $data['screenshot']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Screenshot setting not found.'
        ], 404);
    }
    public function getScreenshotValue()
    {
        $data = $this->getJsonData();

        if (isset($data['screenshot'])) {
            return response()->json([
                'success' => true,
                'screenshot' => $data['screenshot']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Screenshot setting not found.'
        ], 404);
    }
    private function getJsonData(): array
    {
        if (file_exists($this->jsonFilePath)) {
            return json_decode(file_get_contents($this->jsonFilePath), true);
        }

        return [];
    }
    private function saveJsonData(array $data): void
    {
        file_put_contents($this->jsonFilePath, json_encode($data, JSON_PRETTY_PRINT));
    }
}
