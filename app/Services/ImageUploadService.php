<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Default image quality (0-100)
     */
    protected int $quality = 80;

    /**
     * Default max width
     */
    protected int $defaultMaxWidth = 1200;

    /**
     * Upload an image file, resize, convert to WebP, and save to storage.
     *
     * @param UploadedFile $file      The uploaded file from request
     * @param string       $folder    Destination folder (e.g., 'committees', 'articles')
     * @param int          $maxWidth  Maximum width after resize (default: 1200)
     * @param string|null  $oldPath   Old file path to delete (for update operations)
     * @return string                 Relative path to the saved file
     */
    public function upload(UploadedFile $file, string $folder, int $maxWidth = 1200, ?string $oldPath = null): string
    {
        // 1. Generate unique filename
        $filename = Str::uuid() . '.webp';
        $relativePath = "{$folder}/{$filename}";

        // 2. Get the file path
        $tempPath = $file->getPathname();

        // 3. Get image info
        $imageInfo = getimagesize($tempPath);
        if ($imageInfo === false) {
            throw new \RuntimeException('File yang diunggah bukan gambar yang valid.');
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // 4. Create image resource based on file type
        $image = $this->createImageResource($tempPath, $mimeType);

        if ($image === false) {
            throw new \RuntimeException('Gagal memproses gambar.');
        }

        // 5. Resize if width exceeds max width
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;

        if ($originalWidth > $maxWidth) {
            $ratio = $originalHeight / $originalWidth;
            $newWidth = $maxWidth;
            $newHeight = (int) round($newWidth * $ratio);
        }

        if ($newWidth !== $originalWidth || $newHeight !== $originalHeight) {
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            imagedestroy($image);
            $image = $resizedImage;
        }

        // 6. Convert to WebP format and save
        $outputPath = storage_path('app/public/' . $relativePath);

        // Create directory if not exists
        $directory = dirname($outputPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Save as WebP
        $success = imagewebp($image, $outputPath, $this->quality);

        imagedestroy($image);

        if (!$success) {
            throw new \RuntimeException('Gagal menyimpan gambar sebagai WebP.');
        }

        // 7. Delete old file if provided
        if ($oldPath) {
            $this->delete($oldPath);
        }

        // 8. Return the relative path
        return $relativePath;
    }

    /**
     * Create image resource from file based on mime type.
     *
     * @param string $filePath
     * @param string $mimeType
     * @return \GdImage|false
     */
    protected function createImageResource(string $filePath, string $mimeType)
    {
        return match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($filePath),
            'image/png' => imagecreatefrompng($filePath),
            'image/gif' => imagecreatefromgif($filePath),
            'image/webp' => imagecreatefromwebp($filePath),
            'image/bmp' => imagecreatefrombmp($filePath),
            default => false,
        };
    }

    /**
     * Delete a file from public storage.
     *
     * @param string|null $path Relative path to the file
     * @return void
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Get the full URL for a stored image.
     * Method ini dipanggil di beberapa Controller untuk mendapatkan URL publik.
     *
     * @param string $path Relative path to the file
     * @return string
     */
    public function url(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Set image quality.
     *
     * @param int $quality 0-100
     * @return $this
     */
    public function setQuality(int $quality): self
    {
        $this->quality = max(0, min(100, $quality));
        return $this;
    }

    /**
     * Get the default max width.
     *
     * @return int
     */
    public function getDefaultMaxWidth(): int
    {
        return $this->defaultMaxWidth;
    }
}
