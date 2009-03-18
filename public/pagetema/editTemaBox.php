<?php
require_once('../admin/util/bruger.php');
require_once('../admin/util/dba.php');
require_once('../admin/util/tema.php');
require_once('../config.php');
session_start();        

$id = $_REQUEST['id'];
$tema = new temaDoc(new dba());

if( !$tema->isEditor()  ) die('<script>top.GB_hide()</script>');

if($_POST['saving']=='1' && is_numeric($_POST['temaer']) )
{
    $tema->saveTemaBox($id,
                       $_POST['temaer'],
                       $_POST['forside_name'],
                       $_POST['forside_category'],
                       $_POST['forside_resume']);

    //die('<script>top.location.reload(false);top.GB_hide()</script>');
    die('<script>top.location.reload(false);</script>');
}

$temaer = $tema->allPublic();
$props = $tema->temaBoxProperties($id);
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
            
            var temaer = {}; 
            function updateTema(list)
            {
                var tema_id = list.options[list.selectedIndex].value;
                if(tema_id == '-') return;
                var tema = temaer[tema_id];
                if(!tema) return;
                document.myform.forside_name.value = tema['name'];
                document.myform.forside_category.value = tema['name'];
                document.myform.forside_resume.value = tema['resume'];
            }
        </script>
        <style>
            body { font-size:12px;font-family:arial,sans-serif; }
        </style>
    </head>
    <body bgcolor="#FFFFFF" onload="document.getElementById('temaer').focus()">
        <form name="myform" method="post">
            <input type="hidden" name="saving" value="1">
            <input type="hidden" name="id" value="<?=$_REQUEST['id']?>">

            <div style="margin:18px">
                <select name="temaer" onchange="updateTema(this)" style="width:450px;margin-bottom:10px;">
                    <option value="-">Valg Tema</option>
                    <?for($i=0;$i<count($temaer);$i++):?>
                        <?
                            $sel = ''; 
                            if($temaer[$i]['id'] == $props['id']) $sel = 'selected';
                        ?>
                        <option value="<?=$temaer[$i]['id']?>" <?=$sel?>><?=$temaer[$i]['name']?></option>
                        <script> temaer['<?=$temaer[$i]['id']?>']= {'name':'<?=$temaer[$i]['name']?>','resume':'<?=$temaer[$i]['name']?>'};</script>
                    <?endfor?>
                </select>
                <br>

                Box overskrift<br>
                <input type="text" name="forside_name" id="name" value="<?=$props['forside_name']?>" style="width:450px" />

                <br />
                Box kategori<br>
                <input type="text" name="forside_category" id="name" value="<?=$props['forside_category']?>" style="width:450px" />

                <br />
                Box resume
            <textarea name="forside_resume" class="input" style="height:300px;width:450px"><?=$props['forside_resume']?></textarea>

            <br />
            <input type="submit" value="Fortryd" 
                onclick="top.GB_hide()"
                name="submited" class="knap">
            <input type="submit" 
                value="Gemt" name="submited" class="knap">

            </div>
        </form>
    </body>
</html>
