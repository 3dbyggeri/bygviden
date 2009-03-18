<?
$new = ($_GET['new'])? $_GET['new']:$_POST['new'];

if($new =='-1') $new =$producenter->addNew($pcr_id);


if($_POST['update_news'])
{
    $producenter->updateNew($new,$_POST['title'],$_POST['body'],$_POST['website']);
    $new = '';
}
if($_GET['rem']) $producenter->removeNew($_GET['rem']);

$news = $producenter->producerNews($pcr_id);
?>
<script>
    function removing(id)
    {
        if(!confirm('Er du sikkert?')) return;
        document.location.href='?pane=news&pcr_id=<?=$pcr_id?>&rem='+ id;
    }
</script>
<style>
.color1 td { font-size:11px;font-weight:100;padding-left:10px} 
</style>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr> 
      <td><img src="../graphics/transp.gif" height="20"></td>
    </tr> 
    <tr>
        <td class="header"><span class="alert_message"><?=$message?></span>
        </td>
    </tr>
    <?if($new):?>

        <?
            if($new) $props = $producenter->loadNew($new);
        ?>
        <form name="myform" method="post" action="index.php">
        <input type="hidden" name="pane" value="<?=$pane?>" />
        <input type="hidden" name="pcr_id" value="<?=$pcr_id?>" />
        <input type="hidden" name="new" value="<?=$new?>" />
        <tr>
            <td>

               <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
               <tr>
                    <td>
                        Overskrift<br />
                        <input type="text" name="title" value="<?=$props['title']?>"  style="width:350px"/>
                    </td>
                </tr>
                <tr>
                    <td>
                     
                        Post<br />
                        <textarea name="body" style="width:350px;height:150px"><?=$props['body']?></textarea>
                    </td>
                </tr>
               <tr>
                    <td>
                        L&aelig;s mere url<br />
                        <input type="text" name="website" value="<?=$props['website']?>"  style="width:350px"/>
                    </td>
                </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td style="padding:10px">
              <input type="submit" value="Gemt" name="update_news" class="knap" style="width:150px"> 
            </td>
        </tr>
        </form>
    <?else:?>
    <tr>
        <td align="right" style="padding-right:10px;padding-bottom:10px">
            <input type="button" onclick="document.location.href='?pane=news&pcr_id=<?=$pcr_id?>&new=-1'" value="Oprett nyhed" name="add" class="knap" style="width:150px"> 
        </td>
    </tr>
    <style>
        .new
        {
            width:400px;
            border-bottom:1px dashed #e3e3e3;
        }
        .new .tools { float:right; }
        .new h3 { padding:0;margin:0;font-size:12px;color:#333; }
        .new .date { font-size:10px;color:#999; }
        .new .readmore { color:#333;text-decoration:none}
    </style>
    <tr> 
      <td>
          <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">

            <?if( !count( $news) ):?>
              <tr>
                <td align="center"><br />Ingen nyheder<br /><br /></td>
              </tr>
            <?endif?>

            <?for( $i = 0; $i < count( $news); $i++ ):?>
                <tr>
                    <td>
                        <div class="new">
                        <span class="tools">
                            <a href="?pane=news&pcr_id=<?=$pcr_id?>&new=<?=$news[$i]['id']?>"><img src="../../tema/graphics/edit.png" border="0" /></a>
                            <a href="javascript:removing('<?=$news[$i]['id']?>')"><img src="../graphics/delete.png" border="0" /></a>
                        </span>
                        <h3><?=$news[$i]['title']?></h3> 
                        <div class="date"><?=$news[$i]['created']?></div>
                        <p>
                            <?=$news[$i]['body']?>
                        </p>
                        <?if($news[$i]['website']):?>
                            <a class="readmore" href="<?=$news[$i]['website']?>" target="_blank">&#187; L&aelig;s mere</a>
                        <?endif?>
                        </div>
                    </td>
                </tr>
            <?endfor?>
          </table>
      </td>
    </tr>
    <?endif?>
</table>
