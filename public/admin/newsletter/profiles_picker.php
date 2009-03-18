<?php
require_once('../util/products.php');
require_once('../util/dba.php');
$producenter = new products( new dba() );
$profiles = $producenter->loadProfiles();

if($_POST['save'])
{
  $newsletter->addProfiles($n_id,$column,$_POST['selected'],$p_id);
  echo '<script>opener.document.myform.submit();window.close();</script>';
  die();
}

function js($str)
{
    $str = str_replace("'","",$str);
    $str = str_replace("\n"," ",$str);
    return $str;
}
?>
<style>
#news
{
    border:1px solid #333;
    height:275px;
    width:530px;
    overflow:auto;
}
#selectednews
{
    height:100px;
    width:500px;
    margin-top:10px;
}

.new
{
    width:480px;
    border-bottom:1px dashed #e3e3e3;
    cursor:pointer;
    margin-bottom:20px;
}
.new h3 { padding:0;margin:0;font-size:11px;color:#333; }
.new h3 a  { text-decoration:none;color:#333 }
.new .date { font-size:10px;color:#999; }
.new .readmore { color:#333;text-decoration:none}
.new .producer { font-size:10px;float:right;font-weight:100;color:#999;}
</style>
<script src="../../tema/doubleListBox.js"></script>
<script>
function addnew(id,title)
{
   var el = document.getElementById('selectednews');
   el.options[el.options.length] = new Option(title,id);  
   var s = document.getElementById('selected');
   s.value += (s.value.length > 0)?',':'';
   s.value += id;
}
</script>
<form name="myform" method="post" action="picker.php">
<input type="hidden" name="p_id" value="<?=$p_id?>"/>
<input type="hidden" name="n_id" value="<?=$n_id?>"/>
<input type="hidden" name="column" value="<?=$column?>"/>
<input type="hidden" name="ctx" value="profiles"/>
<input type="hidden" id="selected" name="selected"/>
<div id="filer">
</div>
<div id="news">

    <?for( $i = 0; $i < count( $profiles); $i++ ):?>
        <div class="new" onclick="addnew('<?=$profiles[$i]['id']?>','<?=js($profiles[$i]['name'])?>')">
            <h3>
                <span style="font-size:10px;color:#999;float:right">
                    <?
                        $label = 'Ingen aftale';
                        if($profiles[$i]['advertise_deal']=='active') 
                        {
                            $label='Aftale forhandlet '.$profiles[$i]['advertise_signup'];
                        }
                        if($profiles[$i]['advertise_deal']=='trial') 
                        {
                            $label='Aftale p&aring; pr&oslash;vetid ('.$profiles[$i]['signup'].' dag)';
                        }
                    ?>
                    <?=$label?>
                </span>
                <?=$profiles[$i]['name']?>
            </h3> 

            <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
            <td valign="top">

            <p>
                <?=$profiles[$i]['description']?>
            </p>
            <?if($profiles[$i]['home_page']):?>
                <span style="font-size:10px;color:#999">website:<?=$profiles[$i]['home_page']?></span>
            <?endif?>

            </td>
            <td valign="top" align="right">
              <?if($profiles[$i]['logo_url'] && file_exists(realpath('../../logo').'/'.$profiles[$i]['logo_url'])):?>
                <img src="../../logo/<?=$profiles[$i]['logo_url']?>" style="margin:10px;margin-right:0"/>
              <?endif?>
            </td>
            </tr>
            </table>
        </div>
    <?endfor?>
    
</div>


<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <select id="selectednews" name="selectednews" size="10">
            </select>
        </td>
        <td valign="bottom" style="padding-left:10px">

            <a href="javascript:doubleListBox.up('selectednews','selected')"><img src="../graphics/pil_up.gif" border="0" /></a>

            <br />
            <br />

            <a href="javascript:doubleListBox.down('selectednews','selected')"><img src="../graphics/pil_down.gif" border="0" /></a>
            <br />
            <br />

            <a href="javascript:doubleListBox.deleting('selectednews','selected')"><img src="../graphics/delete.png" border="0" /></a>
        </td>
   </tr>
</table>
<div style="margin:10px;text-align:center" >
    <input type="submit" value="Fortryd" 
        onclick="window.close()"
        name="submited" class="knap">
    <input type="submit" 
        value="Gemt" name="save" class="knap">
</div>
<form>
