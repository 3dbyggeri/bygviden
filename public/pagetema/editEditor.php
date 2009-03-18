<?php
require_once('../admin/util/dba.php');
require_once('../admin/util/tema.php');
require_once('../admin/util/bruger.php');
require_once('../config.php');
session_start();        

$id = $_REQUEST['id'];
$tema = new temaDoc(new dba());
if( !$tema->isEditor()  ) die('<script>top.GB_hide()</script>');

if(is_numeric($_POST['removing']) )
{
    $tema->removeEditor($_POST['removing']); 
    die('<script>top.removingEditor();top.GB_hide()</script>');
}

if($_POST['saving']=='1' )
{
    $id = $tema->UpdateEditor($id,
                                 $_REQUEST['name'],
                                 $_REQUEST['title'],
                                 $_REQUEST['email'],
                                 $_REQUEST['resume']
                              );
    $close = false;
    if($_FILES["portrait"]["name"])
    {
      if(stristr($_FILES['portrait']['type'],'jpeg') || stristr($_FILES['portrait']['type'],'jpg')) 
      {
          $f = realpath("../tema/graphics/portraits/") .'/'.$id.'.jpg';
          move_uploaded_file($_FILES["portrait"]["tmp_name"],$f);
      }
      else
      {
          echo '<strong>Filen skal v&aelig;re i jpg format</strong>';
          $close = false;
      }
    }
    if($close) 
    {
        $str = '';
        $ph = 'tema/graphics/portraits/'.$id.'.jpg';
        if($_REQUEST['id'] != $id)
        {
            $str = 'top.addEditor("'.$id.'","'.$_REQUEST['name'].'");';
        }

        die('<script>
                top.editors["'.$id.'"] = {"id":"'. $id.'",
                                          "name":"'.$_REQUEST['name'].'",
                                           "portrait":"'.$ph.'",
                                           "title":"'.$_REQUEST['title'].'",
                                           "email":"'.$_REQUEST['email'].'",
                                           "resume":"'.$_REQUEST['resume'].'"};
                '.$str.'
                top.loadeditor();
                top.GB_hide();
            </script>');
    }
}

//$temaer = $tema->all();
if($_REQUEST['remove']) $id = $_REQUEST['remove'];
$props = $tema->EditorProperties($id);
?>
<html>
    <head>
        <title>Edit Page</title>
        <style>
            body,td { font-size:12px;font-family:arial,sans-serif; }
        </style>
    </head>
    <body bgcolor="#FFFFFF">
        <?if($_REQUEST['remove']):?>
            <div style="margin:20px">
            <form name="myform" method="post"  action="editEditor.php">
            <input type="hidden" name="removing" value="<?=$_REQUEST['remove']?>">
                Er du sikker p&aring; at du vil slette redakt&oslash;ren '<?=$props['name']?>'?
                
                <br><br>
                <input type="submit" value="Fortryd" 
                    onclick="top.GB_hide()"
                    name="submited" class="knap">
                <input type="submit" 
                    value="Slet" name="submited" class="knap">
                
            </form>
            </div>
        </body>
        </html>
        <?die();?>
        <?endif?>

        <form name="myform" method="post" enctype="multipart/form-data" action="editEditor.php">
            <input type="hidden" name="saving" value="1">
            <input type="hidden" name="id" value="<?=$id?>">

            <div style="margin:18px">
            
                <table width="100%" cellpadding="4" cellspacing="0" border="0">
                    <tr>
                        <td valign="top">
                            Navn<br>
                            <input type="text" name="name" id="name" value="<?=$props['name']?>" style="width:300px" />
                            <br />
                            Titel<br>
                            <input type="text" name="title" id="title" value="<?=$props['title']?>" style="width:300px" />
                            <br />
                            Email<br>
                            <input type="text" name="email" id="email" value="<?=$props['email']?>" style="width:300px" />
                            <br />

                            Resume</br />
                            <textarea name="resume" style="height:100px;width:300px"><?=$props['resume']?></textarea>
                        </td>
                        <td valign="bottom">
                            <?
                                $img = '';
                                $f = '../tema/graphics/portraits/'.$id.'.jpg';
                                if(file_exists($f)) $img = '<img src="'.$f.'" width="93" height="130" border="0" hspace="3" vspace="3" /><br />';
                            ?>
                            <?=$img?>
                            Portr&aelig;t 
                            <div style="font-size:10px;color:#999">(st&oslash;rrelse: 93 x 130 px format:jpg)</div>
                            <input type="file" name="portrait" />
                        </td>
                   </tr>
                </table>


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
