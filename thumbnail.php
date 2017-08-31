<?php

if (isset($_REQUEST['thumb'])) {
    include_once('system/classes/easyphpthumbnail.class.php');
    $thumb = new easyphpthumbnail;
    if (isset($_REQUEST['Thumbheight']))
   		$thumb -> Thumbheight =$_REQUEST['Thumbheight'];
   	else if(isset($_REQUEST['Thumbwidth']))
   		$thumb -> Thumbwidth =$_REQUEST['Thumbwidth'];
    $thumb -> Createthumb('data/'.$_REQUEST['thumb']);
}

?>