<?php

function contains($str, array $arr)
{
    foreach($arr as $a) {
        if (stripos($str,$a) !== false) return true;
    }
    return false;
}

$exclude = array('css','index.html');

// Get real path for our folder
$rootPath = realpath('website');

// Initialize archive object
$zip = new ZipArchive();
$zip->open('website.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
			$test = contains($filePath, $exclude);
			if(!($test)){
				$relativePath = substr($filePath, strlen($rootPath) + 1);
				// Add current file to archive
				$zip->addFile($filePath, $relativePath);
			}
        }
}
// Zip archive will be created only after closing object
$zip->close();

?>
