<?php
include("install.php");
$inst = new Install();
$inst->createDB();
$inst->staticDataUpload();
$inst->randomDataUpload();
if (isset($_POST["action"]) && ($_POST["action"] == "cmd_search"))
{
    $inst->list();
}
else{
    $inst->list();
}
?>