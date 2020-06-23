<?php
/**
* Install class to index
*/
include("install.php");
$inst = new Install();

/**
 * data UPLOAD
 */
$inst->createDB();
$inst->staticDataUpload();
$inst->randomDataUpload();

/**
 * test data
 */
$inst->testcase(50);

/**
 * LIST data from database
 */
if (isset($_POST["action"]) && ($_POST["action"] == "cmd_search"))
{
    $inst->list();
}
else{
    $inst->list();
}
?>