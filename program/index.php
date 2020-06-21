<?php
include("program-files/class.php");
$inst = new Classes();
$inst->createDB();
$inst->createTableSize();
$inst->createTableResolution();
$inst->createTableBrand();
$inst->createTableMonitor();
$inst->foreignKeys();
$inst->dataUpload();
$piece=rand(50,120);
for($i=0;$i<$piece;$i++)
{
    $inst->dataUpload2(20);
}
$class->list();
?>