<?
include_once('aci.php');

$aci = new ACI();
$aci->debug = true;

if($_GET['pub_id']) $contents = $aci->hitsfordoc($_GET['pub_id']);
?>
<form>
<input type="text" name="pub_id">
<input type="submit">
</form>
<hr>
<xmp>
    <?print_r($contents)?>
</xmp>
