<?php
require_once('../admin/util/bruger.php');
include_once('../admin/util/products.php');
require_once('../admin/util/dba.php');
require_once('../config.php');
session_start();        

$dba = new dba();
$producenter = new products($dba);
$news_id = $_REQUEST['news_id'];

if($_POST['save_news'])
{
    if($news_id=='-1') $news_id =$producenter->addNew($_SESSION['bruger_id']);
    if($_FILES['logo']['name'])
    {
        $msg = '';
        $path_info = pathinfo($_FILES['logo']['name']);
        $logo = 'n_'.$news_id.'.'.$path_info['extension'];
        $target_path = realpath('../logo').'/'.$logo;

        list($width, $height, $type, $attr) = getimagesize($_FILES['logo']['tmp_name']); 

        if ((($_FILES["logo"]["type"] == "image/gif")
        || ($_FILES["logo"]["type"] == "image/jpeg")
        || ($_FILES["logo"]["type"] == "image/png")
        || ($_FILES["logo"]["type"] == "image/pjpeg"))
        && ($_FILES["logo"]["size"] < 30000)
        && ($width<200 || $height<250))
        {
          if ($_FILES["logo"]["error"] > 0)
          {
            $msg ="Det var ikke mulig at uploade filen " . $_FILES["logo"]["error"];
          }
          else
          {
            if(move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) 
            {
                $dba->exec("UPDATE dev_adnews SET image='".$logo."' WHERE id=".$news_id);
            } 
            else
            {
                $msg ="Det var ikke mulig at uploade filen";
            }
          }
        }
        else
        {
            $msg = "<div style='text-align:center;margin:30px;font-family:sans-serif;'>";
            $msg.= "<p>Billedet skal v&aelig;re under 200 x 250 pixel.</p>";
            $msg.= "<p>Fil st&oslash;rrelse m&aring; ikke v&aelig;re over 30KB.</p>";
            $msg.= "<p>Formatet skal v&aelig; enten gif, png eller jpg.</p>";
            $msg.='<p><a href="editProduct.php?product_id='.$product_id.'">Pr&oslash;v igen</a></p>';
            $msg.='</div>';
        }
        if($msg) die($msg);
    }

    $producenter->updateNew($news_id,$_POST['title'],$_POST['body'],$_POST['website']);
    die('<script>top.location.reload(true);;top.GB_hide();</script>'); 
}

$props = $producenter->loadNew($news_id);
?>
<html>
    <head>
        <title>Rediger nyhed</title>
        <style>
            body, td { font-size:12px;font-family:arial,sans-serif; }
            .helptext
            {
              margin-top:30px;
              margin-bottom:20px;
              padding:10px;
              background-color:#F5EDA9;
              position:relative;
              width:550px;
            }
            .help_top
            {
              position:absolute;
              top:-15px; 
              left:515px;
              background-image:url(/graphics/help.png);
              background-repeat:no-repeat;
              width:32px;
              height:32px;
            }

        </style>
    </head>
    <body bgcolor="#FFFFFF">

      <div class="helptext">
          <div class="help_top"></div>
          <p>
          Skriv overskrift.  Inds&aelig;t en kort nyhedstekst.
          <br />
          Inds&aelig;t web adressen til der hvor folk kan l&aelig;se mere om nyheden, 
          p&aring; din egen hjemmeside.
          <br />
          Inds&aelig;t et billede. 
          <br />
          Klik p&aring; Gem.
          </p>
      </div>
      <form name="myform" method="post" enctype="multipart/form-data" action="editNews.php">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td valign="top">
      
      <input type="hidden" name="news_id" value="<?=$news_id?>" />

      <p>
        Overskrift:<br />
        <input type="text" name="title" value="<?=$props['title']?>"  style="width:350px"/>
      </p>

      <p>
        Tekst:<br />
        <textarea name="body" style="width:350px;height:150px"><?=$props['body']?></textarea>
      </p>

      <p>
        Web adresse til <i>L&aelig;s mere</i> p&aring; egen hjemmeside:<br />
        <input type="text" name="website" value="<?=$props['website']?>"  style="width:350px"/>
      </p>

      <p>

        <input type="button" value="Fortryd" 
            onclick="top.GB_hide()"
            name="submited" class="knap">
        <input type="submit" value="Gemt" name="save_news" class="knap" style="width:150px">
      </p>

    </td>
    <td valign="top" style="padding-left:15px">

              <div style="margin-top:3px;margin-bottom:3px">Billede:</div>
              <?
                if($props['image'] && file_exists(realpath('../logo').'/'.$props['image']) )
                {
                    echo('<img src="../logo/'.$props['image'].'" style="margin:10px" /><br />');
                }
              ?>
              <input type="file" name="logo" />
              <div style="margin-top:5px;font-size:10px;color:#999">
                Billedet skal v&aelig;re under 200 x 250 pixel.<br>
                Fil st&oslash;rrelse m&aring; ikke v&aelig;re over 20KB.<br>
                Formatet skal v&aelig;re enten gif, png eller jpg.<br>
              </div>

      


    </td>
 </tr>
</table>

      </form>
    </body>
</html>
