<?php
require_once('../admin/util/bruger.php');
require_once('../admin/util/dba.php');
include_once('../admin/util/paragraph.php');
require_once('../config.php');
session_start();        

$name = $_REQUEST['n'];
$paragraph = new Paragraph(new dba() );

if( !$paragraph->isEditor()  ) die('<script>top.GB_hide()</script>');

if($_POST['saving'])
{
    $paragraph->save($name,$_POST['body']);
    die('<script>top.location.reload(true)</script>');
}

$para_id = 0;
$para_body= '';
$para = $paragraph->getByName($name);

if($para)
{
    $para_id = $para['id'];
    $para_body = $para['body'];
}
?>
<html>
    <head>
        <title>Edit Paragraph</title>
        <script language="javascript" type="text/javascript" src="../admin/home/tiny_mce/tiny_mce.js"></script>
        <script language="javascript" type="text/javascript">
            tinyMCE.init({
                mode : "textareas",
                theme : "advanced",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align: "left",
                theme_advanced_buttons1 : "formatselect,removeformat,separator,bold,italic,separator,link,unlink,separator,bullist,numlist,separator,image,cleanup,code,separator,undo,redo",
                theme_advanced_buttons2 : "tablecontrols",
                theme_advanced_buttons3 : "",
                plugins : "table"
            });

            function saving()
            {
                document.myform.submit();
            }
        </script>
    </head>
    <body bgcolor="#FFFFFF">
        <center>
        <form name="myform" method="post">
            <input type="hidden" name="saving" value="1">
            <textarea name="body" class="input" style="height:530px;width:550px"><?=$para_body?></textarea>

            <br />
            <input type="submit" value="Fortryd" 
                onclick="top.GB_hide()"
                name="submited" class="knap">
            <input type="submit" 
                onclick="saving()"
                value="Gemt" name="submited" class="knap">
        </form>
        </center>
    </body>
</html>
