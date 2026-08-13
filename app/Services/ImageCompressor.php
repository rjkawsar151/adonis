<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ImageCompressor
{
    /**
     * Compress any uploaded image by 60-70% into WebP format.
     *
     * Uses PHP GD first (fastest, no external process). If GD is not available,
     * falls back to the Node.js "sharp" library (already used by the project).
     * SVG files are stored as-is because GD cannot parse them and the logo
     * pipeline (SettingController) relies on the original SVG/PNG for emails.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $folder  Subdirectory in public/ (e.g. 'uploads/barbers', 'uploads/about')
     * @param  int  $quality  Compression quality (default 70 for 60-70% size reduction)
     * @param  int  $maxDimension  Max width/height in px to prevent huge camera file storage
     * @return string  Relative file path to public folder
     */
    public static function compressAndSaveWebp($file, string $folder = 'uploads', int $quality = 70, int $maxDimension = 1920): string
    {
        $folder = trim(str_replace('\\', '/', $folder), '/');
        $destinationPath = public_path($folder);

        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // SVG cannot be rasterized reliably for all consumers (email logo needs
        // to stay vector/PNG), so keep it as-is.
        $extension = strtolower($file->getClientOriginalExtension() ?: 'png');
        if ($extension === 'svg') {
            $svgName = time() . '_' . uniqid() . '.svg';
            $file->move($destinationPath, $svgName);
            return $folder . '/' . $svgName;
        }

        $fileName = time() . '_' . uniqid() . '.webp';
        $targetPath = $destinationPath . '/' . $fileName;
        $tempPath = $file->getRealPath();

        // 1. Try PHP GD WebP conversion & compression
        if (function_exists('imagecreatefromstring') && function_exists('imagewebp')) {
            $imgContent = @file_get_contents($tempPath);
            if ($imgContent !== false) {
                $im = @imagecreatefromstring($imgContent);
                if ($im !== false) {
                    // Retain transparency for PNG/GIF/WebP
                    imagepalettetotruecolor($im);
                    imagealphablending($im, true);
                    imagesavealpha($im, true);

                    // Resize if huge
                    $width = imagesx($im);
                    $height = imagesy($im);

                    if ($width > $maxDimension || $height > $maxDimension) {
                        $ratio = min($maxDimension / $width, $maxDimension / $height);
                        $newWidth = (int) max(1, round($width * $ratio));
                        $newHeight = (int) max(1, round($height * $ratio));

                        $resized = imagecreatetruecolor($newWidth, $newHeight);
                        imagealphablending($resized, false);
                        imagesavealpha($resized, true);
                        imagecopyresampled($resized, $im, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagedestroy($im);
                        $im = $resized;
                    }

                    // Save as WebP with quality 70 (60-70% size reduction)
                    $saved = @imagewebp($im, $targetPath, $quality);
                    imagedestroy($im);

                    if ($saved && File::exists($targetPath)) {
                        return $folder . '/' . $fileName;
                    }
                }
            }
        }

        // 2. Try sharp (Node.js) fallback when GD is not available
        if (self::convertWithSharp($tempPath, $targetPath, $quality, $maxDimension) && File::exists($targetPath)) {
            return $folder . '/' . $fileName;
        }

        // 3. Fallback to normal upload if neither GD nor sharp is available
        $fallbackName = time() . '_' . uniqid() . '.' . $extension;
        $file->move($destinationPath, $fallbackName);
        return $folder . '/' . $fallbackName;
    }

    /**
     * Convert an image to WebP using the Node.js "sharp" library.
     *
     * @return bool  True when the WebP file was written successfully.
     */
    private static function convertWithSharp(string $sourcePath, string $targetPath, int $quality, int $maxDimension): bool
    {
        if (!function_exists('shell_exec')) {
            return false;
        }

        try {
            $script = base_path('scripts/webp-upload.mjs');
            $cmd = sprintf(
                'node %s %s %s %d %d 2>&1',
                escapeshellarg($script),
                escapeshellarg(str_replace('\\', '/', $sourcePath)),
                escapeshellarg(str_replace('\\', '/', $targetPath)),
                $quality,
                $maxDimension
            );

            @shell_exec($cmd);
        } catch (\Throwable $e) {
            return false;
        }

        return File::exists($targetPath);
    }
}
