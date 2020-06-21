<?php
include("install.php");
$inst = new Install();
$inst->createDB();
$inst->createTableSize();
$inst->createTableResolution();
$inst->createTableBrand();
$inst->createTableMonitor();
$inst->foreignKeys();
$inst->dataUpload();
$inst->dataUpload2(20);


?>