<?php
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
 function isLogged()
 {
   return $_SESSION['bruger_id']? true:false;
 }
 function logIndForm()
 {
  return '
          <form name="myform" action="index.php?action='.findAction().'" method="post">
            Brugernavn &nbsp;&nbsp;<span style="color:#666;font-size:10px">- medlemsnummer</span><br>
            <input type="text" class="textfield" name="name"><br>
            Password &nbsp;&nbsp;<span style="color:#666;font-size:10px">- telefonnummer</span><br>
            <input type="password" class="textfield" name="password"><br>
            <input style="margin-top:5px" type="submit" name="log_in" class="button" value="Log ind">
          </form>
        ';
 }
 function checkFailureMessage()
 {
  if(!$_POST['log_in']) return;
  return '<p style="color:red">Forkert navn eller password</p>';
 }
?>
<div class="rightheadline"> 
  <?if(isLogged()):?>
    Du er logget ind som:
  <?elseif($_SESSION['update_password']):?>
    Skift dit password
  <?else:?>
    Du er ikke logget ind
  <?endif?>
</div>

<div class="rightcontent">
  <?if(isLogged()):?>
    <p><?=$_SESSION['bruger_navn']?></p>
    <?=getRightLinks($_SERVER['PHP_SELF'].'?action=minside','Personlig indstillinger','Personlige indstillinger')?>
    <?=getRightLinks($_SERVER['PHP_SELF'].'?log_out=1&action='.findAction(),'Log af','Log af')?>
  <?elseif($_SESSION['update_password']):?>
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
  <?endif?>
</div>

<div class="rightheadline">
  Du s&oslash;ger indenfor <?=strtolower($brancheLabel)?> 
</div>
<div class="rightcontent">
  <input type="button" style="margin-top:4px" value="Skift fag"
    class="button" onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?action=valgfag'">
</div>
