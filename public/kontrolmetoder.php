<?php
$KONTROL_METODER = 7;
$IDS = '';

$db = mysql_connect('localhost','tolerancer','MaioicTE');
mysql_select_db('tolerancer',$db);

$allchildrens = array();


function get_childrens($pid)
{
    global $db,$IDS;

    $childrens = array();
    $sql = "SELECT uid FROM pages WHERE pid=". $pid;
    $result= mysql_query($sql,$db);
    $n = mysql_num_rows($result);
    $start_koma = True;

    for( $i = 0; $i < $n; $i++ )
    {
        $row = mysql_fetch_row($result);
        $uid = $row[0];
        if($IDS!='') $IDS.=',';
        $IDS.= $uid;
        get_childrens($uid);
    }
}
get_childrens($KONTROL_METODER);
?>
<script>
var kontrolmetoder = [<?=$IDS?>];

function is_kontrol_method(url)
{
    var u = url.split('?');
    var keyvalue = u[u.length - 1].split('&');
    for(var i=0;i < keyvalue.length; i++)
    {
        var props = keyvalue[i].split('=');
        if(props[0]!='id') continue;
        id = parseInt(props[1]);
        if(isNaN(id)) contineu;
        for(var j=0;j < kontrolmetoder.length; j++)
        {
            if(id == kontrolmetoder[j]) return true;
        }
    }
    return false;
}
function testing()
{
    alert(is_kontrol_method(document.getElementById('url').value));
}
</script>
<input type="text" id="url">
<button onclick="testing()" value="test">
