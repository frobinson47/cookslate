<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ImageProcessorTest extends TestCase
{
    private string $tmpDir;
    private static bool $constantsLoaded = false;

    protected function setUp(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension not available');
        }
        if (!extension_loaded('fileinfo')) {
            $this->markTestSkipped('fileinfo extension not available');
        }

        // Sandbox uploads to a per-test temp dir BEFORE loading constants
        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cookslate_test_' . uniqid();
        mkdir($this->tmpDir, 0755, true);

        if (!self::$constantsLoaded) {
            define('UPLOAD_DIR', $this->tmpDir . DIRECTORY_SEPARATOR);
            define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024);
            define('IMAGE_MAX_WIDTH', 800);
            define('THUMB_MAX_WIDTH', 300);
            define('IMAGE_QUALITY', 85);
            define('THUMB_QUALITY', 80);
            self::$constantsLoaded = true;
        }

        require_once __DIR__ . '/../../services/LoggerService.php';

        // ImageProcessor includes constants.php which would redefine UPLOAD_DIR,
        // so we load it via a shim that skips the require if already defined.
        if (!class_exists('ImageProcessor')) {
            $src = file_get_contents(__DIR__ . '/../../services/ImageProcessor.php');
            $src = preg_replace('/require_once[^;]+constants\.php\';/', '', $src);
            eval('?>' . $src);
        }
    }

    protected function tearDown(): void
    {
        $this->rrmdir($this->tmpDir);
    }

    private function rrmdir(string $dir): void
    {
        if (!is_dir($dir)) return;
        foreach (scandir($dir) as $f) {
            if ($f === '.' || $f === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $f;
            is_dir($path) ? $this->rrmdir($path) : unlink($path);
        }
        rmdir($dir);
    }

    private function makeJpegFile(int $w = 400, int $h = 300): string
    {
        $img = imagecreatetruecolor($w, $h);
        imagefill($img, 0, 0, imagecolorallocate($img, 200, 100, 50));
        $path = $this->tmpDir . DIRECTORY_SEPARATOR . 'src_' . uniqid() . '.jpg';
        imagejpeg($img, $path);
        imagedestroy($img);
        return $path;
    }

    private function makePngFile(int $w = 200, int $h = 200): string
    {
        $img = imagecreatetruecolor($w, $h);
        imagefill($img, 0, 0, imagecolorallocate($img, 0, 128, 64));
        $path = $this->tmpDir . DIRECTORY_SEPARATOR . 'src_' . uniqid() . '.png';
        imagepng($img, $path);
        imagedestroy($img);
        return $path;
    }

    private function fakeFile(string $srcPath, string $mime = 'image/jpeg'): array
    {
        return [
            'tmp_name' => $srcPath,
            'error' => UPLOAD_ERR_OK,
            'size' => filesize($srcPath),
            'name' => basename($srcPath),
            'type' => $mime,
        ];
    }

    public function testProcessRejectsUploadError(): void
    {
        $proc = new \ImageProcessor();
        $file = [
            'tmp_name' => $this->makeJpegFile(),
            'error' => UPLOAD_ERR_INI_SIZE,
            'size' => 100,
        ];
        $this->assertNull($proc->process($file, 1));
    }

    public function testProcessRejectsOversizedFile(): void
    {
        $proc = new \ImageProcessor();
        $src = $this->makeJpegFile();
        $file = [
            'tmp_name' => $src,
            'error' => UPLOAD_ERR_OK,
            'size' => MAX_UPLOAD_SIZE + 1,
        ];
        $this->assertNull($proc->process($file, 1));
    }

    public function testProcessRejectsNonImage(): void
    {
        $proc = new \ImageProcessor();
        $path = $this->tmpDir . DIRECTORY_SEPARATOR . 'fake.txt';
        file_put_contents($path, 'this is not an image');
        $file = [
            'tmp_name' => $path,
            'error' => UPLOAD_ERR_OK,
            'size' => filesize($path),
        ];
        $this->assertNull($proc->process($file, 1));
    }

    public function testProcessJpegProducesFullAndThumb(): void
    {
        $proc = new \ImageProcessor();
        $src = $this->makeJpegFile(1200, 900);
        $result = $proc->process($this->fakeFile($src), 42);

        $this->assertEquals('recipes/42/full.webp', $result);
        $this->assertFileExists($this->tmpDir . '/recipes/42/full.webp');
        $this->assertFileExists($this->tmpDir . '/recipes/42/thumb.webp');
    }

    public function testProcessPngWorks(): void
    {
        $proc = new \ImageProcessor();
        $src = $this->makePngFile(400, 400);
        $result = $proc->process($this->fakeFile($src, 'image/png'), 7);

        $this->assertEquals('recipes/7/full.webp', $result);
        $this->assertFileExists($this->tmpDir . '/recipes/7/full.webp');
    }

    public function testProcessResizesLargeImageToMaxWidth(): void
    {
        $proc = new \ImageProcessor();
        $src = $this->makeJpegFile(2000, 1000);
        $proc->process($this->fakeFile($src), 99);

        $info = getimagesize($this->tmpDir . '/recipes/99/full.webp');
        $this->assertLessThanOrEqual(IMAGE_MAX_WIDTH, $info[0]);

        $thumbInfo = getimagesize($this->tmpDir . '/recipes/99/thumb.webp');
        $this->assertLessThanOrEqual(THUMB_MAX_WIDTH, $thumbInfo[0]);
    }

    public function testProcessKeepsSmallImageSize(): void
    {
        $proc = new \ImageProcessor();
        $src = $this->makeJpegFile(150, 100);
        $proc->process($this->fakeFile($src), 5);

        $info = getimagesize($this->tmpDir . '/recipes/5/full.webp');
        $this->assertEquals(150, $info[0]);
        $this->assertEquals(100, $info[1]);
    }
}
