<?
 $branche = $_SESSION['branche']; 
 $brancheLabel = ($brancher[$branche])? $brancher[$branche]:$brancher['general'];

 function getRightLinks($url,$label,$alt='')
 {
    return '
            <p><a href="'.$url.'" title="'.$alt.'"><img 
                src="graphics/link_arrows.gif" class="imgarrow" 
                alt="'.$alt.'" height="5" width="7" 
                border="0" align="left" />'. $label.'</a>
            </p>';
 }
 function logIndForm()
 {
  return '
          <form name="myform" action="index.php?action='.findAction().'" method="post">
            Brugernavn <br>
            <input type="text" class="textfield" name="name" id="user_name"><br>
            Password <br>
            <input type="password" class="textfield" name="password"><br>
            <input style="float:right;margin-top:5px;" type="submit" name="log_in" class="button" value="Log ind">
          </form>
        ';
 }
 function checkFailureMessage()
 {
  if(isLogged()) return;
  if(!$_POST['log_in']) return;
  return '
            <p style="color:red">Forkert navn eller password</p>
            <script>addLoadEvent(login_form);</script>
         ';
 }
?>
<div id="log_in_form">
  <?if($_SESSION['update_password']):?>
        <?if($UPDATE_PASSWORD_MSG):?>
            <p style="color:#cc3300"><?=$UPDATE_PASSWORD_MSG?></p>
        <?endif?>
        <p>
            Det er f&oslash;rste gang du logger ind p&aring; din konto, skift venligst dit password
        </p>
      <form name="myform" action="index.php?action=<?=findAction()?>" method="post">
        Password <br>
        <input type="password" class="textfield" name="password1"><br>
        Gentag password<br>
        <input type="password" class="textfield" name="password2"><br>
        <input style="margin-top:5px" type="submit" name="update_password" class="button" value="Opdater">
      </form>
  <?else:?>
    <?=checkFailureMessage()?>
    <?=logIndForm()?>
    <br />
  <?endif?>
</div>

<?if($GODE_RAAD_FROM_BYGVIDEN):?>
<div id="gode_raad" class="right_element">
    <div class="rightheadline">
       Gode r&aring;d
    </div>
    <div id="right_col">
        <?=$GODE_RAAD_FROM_BYGVIDEN?>
    </div>
</div>
<?endif?>

<?if($RELATED_PRODUCTS):?>
<div id="related_products" class="right_element">
    <div class="rightheadline">
       Varegrupper
    </div>
    <br>
    <div id="right_col">
        <?=$RELATED_PRODUCTS?>
    </div>
</div>
<?endif?>

<?if(isLogged()):?> 
<div id="personal_menu" class="right_element">
    <div class="rightheadline"> 
      Personlig genvej 
    </div>

    <div class="rightcontent">
        <?=getRightLinks($_SERVER['PHP_SELF'].'?action=minside','Personlige indstillinger','Personlige indstillinger')?>
        <?=getRightLinks($_SERVER['PHP_SELF'].'?log_out=1&action='.findAction(),'Log af','Log af')?>
    </div>
</div>
<?endif?>

<?if($current_action == 'bygningsdel' && $_SESSION['element'] == 1):?>

<style>
    #post { font-size:11px;margin-bottom:10px;margin-top:5px;color:#333; }
    #post .ptitle {font-weight:900; }
    #post .date {font-size:9px;color:#999; }
</style>
<?
require_once("admin/util/blog.php");
$blog = new blog(new dba());
$blogs = $blog->listing(1);
?>
<?if(count($blogs)):?>
<div id="posts" class="right_element" style="margin-top:10px">

    <div class="rightheadline" style="margin-bottom:15px"> 
      Nyt p&aring; Bygviden
    </div>
    <?for($i=0;$i<count($blogs);$i++):?>
        <div id="post">
        <?  $b = $blogs[$i]; ?>
        <div class="ptitle"><?=$b['title']?></div>
        <div class="date"><?=$b['edited']?></div>
        <div class="post"><?=$b['post']?></div>
        </div>
    <?endfor?>
</div>
<?endif?>
<?endif?>

<div class="rightcontent" style="padding:10px">
    <table cellpadding="0" width="200" cellspacing="0" border="0" style="margin-top:5px">
        <tr>
            <td>
                <a href="http://www.krakbyg.dk" target="_blank"><img 
                    border="0" align="absbottom" src="graphics/search_krak_cropped.gif" /></a>
            </td>
            <td align="right">
              <input type="button" style="margin-left:5px;margin-right:3px;width:160px" value="S&oslash;g p&aring; KrakByg.dk"
                class="button" onclick="window.open('http://www.krakbyg.dk')">
            </td>
        </tr>
  </table>
</div>
