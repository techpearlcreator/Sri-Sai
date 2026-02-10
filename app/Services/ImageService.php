<?php

namespace App\Services;

use App\Helpers\Response;

/**
 * ImageService — Handles file upload, MIME validation, thumbnail generation, and deletion.
 */
class ImageService
{
    private static array $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    /**
     * Upload a file from $_FILES.
     *
     * @param string $fieldName  The $_FILES key
     * @param string $module     Subdirectory under storage/uploads/ (e.g., 'blogs', 'gallery')
     * @param bool   $generateThumb  Whether to generate a thumbnail for images
     * @return array  ['file_name', 'file_path', 'thumbnail_path', 'file_type', 'file_size', 'width', 'height']
     */
    public static function upload(string $fieldName, string $module = 'general', bool $generateThumb = true): array
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = self::uploadErrorMessage($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE);
            Response::error(400, 'UPLOAD_ERROR', $errorMsg);
        }

        $file = $_FILES[$fieldName];
        $config = require CONFIG_PATH . '/upload.php';

        // Validate file size
        if ($file['size'] > $config['max_size']) {
            $maxMB = round($config['max_size'] / 1048576, 1);
            Response::error(400, 'FILE_TOO_LARGE', "File exceeds maximum size of {$maxMB}MB");
        }

        // Validate MIME type using finfo (not the client-provided type)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        $allowedMimes = self::getAllowedMimes($config['allowed_types']);
        if (!in_array($mimeType, $allowedMimes, true)) {
            Response::error(400, 'INVALID_FILE_TYPE', "File type '{$mimeType}' is not allowed. Allowed: " . implode(', ', $config['allowed_types']));
        }

        // PHP injection check — scan the first 1KB for PHP tags
        $handle = fopen($file['tmp_name'], 'r');
        $header = fread($handle, 1024);
        fclose($handle);
        if (preg_match('/<\?(php|=)/i', $header)) {
            Response::error(400, 'MALICIOUS_FILE', 'File appears to contain embedded code');
        }

        // Generate unique filename
        $ext = self::getExtensionFromMime($mimeType);
        $uniqueName = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;

        // Ensure target directory exists
        $uploadDir = $config['storage_path'] . '/' . $module;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $targetPath = $uploadDir . '/' . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            Response::error(500, 'UPLOAD_FAILED', 'Failed to save uploaded file');
        }

        // Get image dimensions if it's an image
        $width = null;
        $height = null;
        $thumbnailRelative = null;
        $isImage = in_array($mimeType, self::$imageTypes, true);

        if ($isImage) {
            $dimensions = getimagesize($targetPath);
            if ($dimensions) {
                $width = $dimensions[0];
                $height = $dimensions[1];
            }

            // Generate thumbnail
            if ($generateThumb) {
                $thumbName = 'thumb_' . $uniqueName;
                $thumbDir = $config['storage_path'] . '/thumbnails';
                if (!is_dir($thumbDir)) {
                    mkdir($thumbDir, 0755, true);
                }
                $thumbPath = $thumbDir . '/' . $thumbName;

                if (self::createThumbnail($targetPath, $thumbPath, $config['thumb_width'], $config['thumb_height'], $mimeType)) {
                    $thumbnailRelative = 'thumbnails/' . $thumbName;
                }
            }
        }

        // Build relative path for database storage
        $fileRelative = $module . '/' . $uniqueName;

        return [
            'file_name'      => $file['name'],
            'file_path'      => $fileRelative,
            'thumbnail_path' => $thumbnailRelative,
            'file_type'      => $mimeType,
            'file_size'      => $file['size'],
            'width'          => $width,
            'height'         => $height,
        ];
    }

    /**
     * Delete a file and its thumbnail from storage.
     */
    public static function delete(string $filePath, ?string $thumbnailPath = null): bool
    {
        $config = require CONFIG_PATH . '/upload.php';
        $basePath = $config['storage_path'];
        $deleted = false;

        $fullPath = $basePath . '/' . $filePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
            $deleted = true;
        }

        if ($thumbnailPath) {
            $thumbFull = $basePath . '/' . $thumbnailPath;
            if (file_exists($thumbFull)) {
                unlink($thumbFull);
            }
        }

        return $deleted;
    }

    /**
     * Create a center-cropped thumbnail using GD.
     */
    private static function createThumbnail(string $source, string $dest, int $thumbW, int $thumbH, string $mimeType): bool
    {
        $srcImage = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($source),
            'image/png'  => imagecreatefrompng($source),
            'image/gif'  => imagecreatefromgif($source),
            'image/webp' => imagecreatefromwebp($source),
            default      => false,
        };

        if (!$srcImage) {
            return false;
        }

        $srcW = imagesx($srcImage);
        $srcH = imagesy($srcImage);

        // Calculate center crop coordinates
        $srcRatio = $srcW / $srcH;
        $thumbRatio = $thumbW / $thumbH;

        if ($srcRatio > $thumbRatio) {
            // Source is wider — crop sides
            $cropH = $srcH;
            $cropW = (int) ($srcH * $thumbRatio);
            $cropX = (int) (($srcW - $cropW) / 2);
            $cropY = 0;
        } else {
            // Source is taller — crop top/bottom
            $cropW = $srcW;
            $cropH = (int) ($srcW / $thumbRatio);
            $cropX = 0;
            $cropY = (int) (($srcH - $cropH) / 2);
        }

        $thumbImage = imagecreatetruecolor($thumbW, $thumbH);

        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($thumbImage, false);
            imagesavealpha($thumbImage, true);
            $transparent = imagecolorallocatealpha($thumbImage, 0, 0, 0, 127);
            imagefill($thumbImage, 0, 0, $transparent);
        }

        imagecopyresampled(
            $thumbImage, $srcImage,
            0, 0, $cropX, $cropY,
            $thumbW, $thumbH, $cropW, $cropH
        );

        $result = match ($mimeType) {
            'image/jpeg' => imagejpeg($thumbImage, $dest, 85),
            'image/png'  => imagepng($thumbImage, $dest, 8),
            'image/gif'  => imagegif($thumbImage, $dest),
            'image/webp' => imagewebp($thumbImage, $dest, 85),
            default      => false,
        };

        imagedestroy($srcImage);
        imagedestroy($thumbImage);

        return $result;
    }

    /**
     * Map allowed extension list to MIME types.
     */
    private static function getAllowedMimes(array $extensions): array
    {
        $map = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'pdf'  => 'application/pdf',
            'svg'  => 'image/svg+xml',
        ];

        $mimes = [];
        foreach ($extensions as $ext) {
            $ext = strtolower(trim($ext));
            if (isset($map[$ext])) {
                $mimes[] = $map[$ext];
            }
        }

        return array_unique($mimes);
    }

    /**
     * Get file extension from MIME type.
     */
    private static function getExtensionFromMime(string $mime): string
    {
        return match ($mime) {
            'image/jpeg'      => 'jpg',
            'image/png'       => 'png',
            'image/gif'       => 'gif',
            'image/webp'      => 'webp',
            'application/pdf' => 'pdf',
            'image/svg+xml'   => 'svg',
            default           => 'bin',
        };
    }

    /**
     * Human-readable upload error message.
     */
    private static function uploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE   => 'File exceeds server upload limit',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds form upload limit',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Server missing temporary directory',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION  => 'Upload blocked by server extension',
            default               => 'Unknown upload error',
        };
    }
}
