<?php
$para = $newsletter->loadParagraph($p_id);
$body = $para["body"];

if($_POST['saving'])
{
    if($p_id=='-1') $p_id = $newsletter->createParagraph($n_id,$column);
    $newsletter->updateParagraph($p_id,$_POST['body']);
    echo '<script>opener.document.myform.submit();window.close();</script>';
    die();
}
?>
<script language="javascript" type="text/javascript" src="../home/tiny_mce/tiny_mce.js"></script>
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
<center>
<form name="myform" method="post">
    <input type="hidden" name="saving" value="1">
    <input type="hidden" name="p_id" value="<?=$p_id?>">
    <input type="hidden" name="n_id" value="<?=$n_id?>">
    <input type="hidden" name="column" value="<?=$column?>">
    <textarea name="body" class="input" style="height:400px;width:550px"><?=$body?></textarea>

    <br />
    <input type="submit" value="Fortryd" 
        onclick="window.close()"
        name="submited" class="knap">
    <input type="submit" 
        onclick="saving()"
        value="Gemt" name="submited" class="knap">
</form>
</center>
