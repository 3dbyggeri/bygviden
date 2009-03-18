<?php
require_once('../admin/util/bruger.php');
require_once('../admin/util/dba.php');
require_once('../admin/util/tema.php');
require_once('../config.php');
session_start();        

$id = $_REQUEST['id'];
$tema = new temaDoc(new dba());

if( !$tema->isEditor()  ) die('<script>top.GB_hide()</script>');

if($_POST['saving']=='1')
{
    $page_id = $tema->savePage($id,$_POST['tema_id'],$_POST['name'],$_POST['body']);

    $add = 'top.newListItem(\'sider\','.$page_id.',escape(\''.$_POST['name'].'\'));';
    $edit ='top.updateListItem(\'sider\',escape(\''.$_POST['name'].'\'));';

    $str = '<script>';
    $str.= 'if(top.page_edit) { top.location.reload(false); } else { ';
    $str.= ($id == '-1')? $add:$edit;
    $str.= '} top.GB_hide()</script>';
    die($str);
}

$props = $tema->pageProperties($id);
?>
<html>
    <head>
        <title>Edit Page</title>
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

        </script>
        <style>
            body { font-size:12px;font-family:arial,sans-serif; }
        </style>
    </head>
    <body bgcolor="#FFFFFF" onload="document.getElementById('name').focus()">
        <form name="myform" method="post">
            <input type="hidden" name="saving" value="1">
            <input type="hidden" name="tema_id" value="<?=$_REQUEST['tema_id']?>">

            <div style="margin:18px">
                Side navn<br>
                <input type="text" name="name" id="name" value="<?=$props['name']?>" style="width:550px" />
            </div>

            <center>
            <textarea name="body" class="input" style="height:530px;width:550px"><?=$props['body']?></textarea>

            <br />
            <input type="submit" value="Fortryd" 
                onclick="top.GB_hide()"
                name="submited" class="knap">
            <input type="submit" 
                value="Gemt" name="submited" class="knap">
            </center>
        </form>
    </body>
</html>
