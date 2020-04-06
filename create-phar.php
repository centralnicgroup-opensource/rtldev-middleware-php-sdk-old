<?php
$srcRoot = "src" . DIRECTORY_SEPARATOR;
$pharFile = "php-sdk-latest.phar";
$pharFileGZ = $pharFile . ".gz";

// clean up
foreach([$pharFile, $pharFileGZ] as $filename){
    if (file_exists($filename)) {
        unlink($filename);
    }
}

// create phar
$p = new Phar($pharFile);

// creating our library using whole directory  
$p->buildFromDirectory($srcRoot);

// pointing main file which requires all classes  
//$p->setDefaultStub("index.php");

// plus - compressing it into gzip  
$p->compress(Phar::GZ);

copy($pharFile, "php-sdk.phar");
copy($pharFileGZ, "php-sdk.phar.gz");

echo "Archive successfully created";