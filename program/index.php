<?php
include("install.php");
$inst = new Install();
/*$inst->createDB();
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
}*/
$inst->func();
if (isset($_POST["action"]) && ($_POST["action"] == "cmd_search"))
{
    $inst->list();
}
else{
    $inst->list();
}
?>