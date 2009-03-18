<?php
    require_once("../util/blog.php");
    $blog = new blog($dba);

    $postid = $_REQUEST['postid'];
   
    if(is_numeric($_REQUEST['del'])) $blog->remove($_REQUEST['del']);

    if($_REQUEST['saving']=='1')
    {
        if($postid=='0') $postid = $blog->add();
        $blog->update($postid,$_REQUEST['title'],$_REQUEST['posting']);
        $postid = '';
    }
    if(is_numeric($_REQUEST['publish'])) $blog->publish_state($_REQUEST['publish'],'y');
    if(is_numeric($_REQUEST['unpublish'])) $blog->publish_state($_REQUEST['unpublish'],'n');

    $blogs = $blog->listing();

    if(is_numeric($postid) && $postid != '0')
    {
        $props = $blog->load($postid);
        $title = $props['title'];
        $posting = $props['post'];
    }
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">Nyheder</td>
	</tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
           <tr>
                <td class="plainText" style="padding-left: 10px; padding-top: 5px;">


               <?if(is_numeric($postid)):?> 

                <script language="javascript" type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
                <!--<script language="javascript" type="text/javascript" src="tiny_mce/tiny_mce_gzip.php"></script>-->
                <script language="javascript" type="text/javascript">
                    // Notice: The simple theme does not use all options some of them are limited to the advanced theme
                    tinyMCE.init({
                        mode : "textareas",
                        theme : "advanced",
                        theme_advanced_buttons1 : "bold,italic,separator,link,unlink,separator,bullist,numlist,separator,cleanup,code",
                        theme_advanced_buttons2 : "",
                        theme_advanced_buttons3 : ""
                    });
                </script>
                <!-- /tinyMCE -->

                <form name="myform" method="post">
                <input type="hidden" name="saving" value="1">
                <input type="hidden" name="postid" value="<?=$postid?>">
                <input type="hidden" name="pane" value="<?=$_REQUEST['pane']?>">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td class="tdpadtext">Title</td>
                    </tr>
                    <tr>
                      <td class="tdpadtext"><input type="text" id="title"
                        name="title" class="input"  value="<?=$title?>"></td>
                    </tr>
                    <tr>
                        <td class="tdpadtext">Post</td>
                    </tr>
                    <tr>
                      <td class="tdpadtext"><textarea 
                        name="posting" class="input" style="height:200px"><?=$posting?></textarea>
                     </td>
                    </tr>
                </table>
                </form>
                <br>
                <?else:?>
                    <style>
                        #post { width:400px;padding-bottom:24px;margin-bottom:5px;margin-top:5px;
                                border-bottom:1px dashed #999; }
                        #post .title {font-weight:900; }
                        #post .date {font-size:9px;color:#999; }
                        #post .links {float:right; }
                        #post .links a {color:#cc3300;font-weight:900;text-decoration:none }
                    </style>
                    <?for($i=0;$i<count($blogs);$i++):?>
                        <div id="post">
                        <?  $b = $blogs[$i]; ?>
                        <div class="title"><?=$b['title']?></div>
                        <div class="date"><?=$b['edited']?></div>
                        <div class="post"><?=$b['post']?></div>

                        <span class="links">
                            <a href="javascript:removing('<?=$b['id']?>')">Slet</a> &nbsp;&nbsp;
                            <a href="?postid=<?=$b['id']?>&pane=<?=$_REQUEST['pane']?>">Redigere</a> &nbsp;&nbsp;
                            <?if($b['publish']=='y'):?>
                                <a href="?unpublish=<?=$b['id']?>&pane=<?=$_REQUEST['pane']?>">Nedtag</a>
                            <?else:?>
                                <a href="?publish=<?=$b['id']?>&pane=<?=$_REQUEST['pane']?>">Publicere</a>
                            <?endif?>
                        </span>
                        </div>
                    <?endfor?>
                    <br><br>

                <?endif?>

                </td>
           </tr>
        </table>


          <table cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td class="tdpadtext" nowrap style="padding-left:15px">
        
               <?if(is_numeric($postid)):?>
                    <input type="submit" value="Fortryd" 
                        onclick="document.location.href='?pane=<?=$_REQUEST['pane']?>'"
                        name="submited" class="knap">
                    <input type="submit" 
                        onclick="saving()"
                        value="Gemt" name="submited" class="knap">
               <?else:?>
                    <input type="submit" value="Tilf&oslash;j nyhed" style="width:145px;" 
                        onclick="document.location.href='?postid=0&pane=<?=$_REQUEST['pane']?>'"
                        name="submited" class="knap">
               <?endif?>
              </td>
            </tr>
          </table>


    </td>
  </tr>
  <tr>
  	<td>

      
		<table cellpadding="0" cellspacing="0" border="0" width="310">
			<tr>
				<td align="left" width="310" style="padding-top:10px; padding-left:10px;"> </td>
			</tr>
		</table>
	</td>
  </tr>
  <tr> 
   	<td><img src="../graphics/transp.gif" height="15" width="15"></td>
  </tr>
</table>

<script>
    function saving()
    {
        document.myform.submit();
    }
    function removing(id)
    {
        if(!confirm('Slet post?')) return;
        document.location.href='?del='+ id +'&pane=<?=$_REQUEST['pane']?>';
    }
    var el = document.getElementById('title');
    if(el) { el.select();el.focus(); }
</script>
