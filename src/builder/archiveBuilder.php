<?php
namespace Brizy\builder;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class ArchiveBuilder {

    public $zip;

    function __construct()
    {
        $this->zip = new ZipArchive();
    }
   
    public function createZip($source, $destination) 
    {
        if (!extension_loaded('zip') || !file_exists($source)) 
        {
            return false;
        }

        if (!$this->zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($files as $file) 
        {

            if (in_array(substr($file->getFilename(), 0, 1), array('.', '_'))) {
                continue;
            }
            $file = str_replace('\\', '/', $file);

            if (is_dir($file)) {

                $this->zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } elseif (is_file($file)) {
                $this->zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }

        $this->zip->close();

        return file_exists($destination);
    }

    public function extractZip($file, $destination) 
    {
        if (!extension_loaded('zip') || !file_exists($file)) {
            return false;
        }

        if (!$this->zip->open($file)) {
            return false;
        }

        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $this->zip->extractTo($destination);

        $this->zip->close();

        return true;
    }

    // // Упаковываем директорию и файлы в ZIP-архив
    // $source = 'path/to/directory';
    // $destination = 'archive.zip';
    // create_zip($source, $destination);

    // // Распаковываем файлы и директории из ZIP-архива
    // $file = 'archive.zip';
    // $destination = 'path/to/destination';
    // extract_zip($file, $destination);
}