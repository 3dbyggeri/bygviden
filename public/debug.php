<?php
function show_php($var,$indent='&nbsp;&nbsp;',$niv='0')
{
    $str='';
    if(is_array($var))    {
        $str.= "<b>[array][".count($var)."]</b><br />";
        foreach($var as $k=>$v)        {
            for($i=0;$i<$niv;$i++) $str.= $indent;
            $str.= "$indent<em>\"{$k}\"=></em>";
            $str.=show_php($v,$indent,$niv+1);
        }
    }
    else if(is_object($var)) {

        $str.= "<b>[object]-class=[".get_class($var)."]-method=[";
        $arr = get_class_methods($var);
           foreach ($arr as $method) {
               $str .= "[function $method()]";
           }
        $str.="]-";
        $str.="</b>";
        $str.=show_php(get_object_vars($var),$indent,$niv+1);
    }
    else {
        $str.= "<em>[".gettype($var)."]</em>=[{$var}]<br />";
    }
    return($str);
}

function show_debug($obj){
  echo "<xmp>";
  print_r($obj);
  echo "</xmp>";
}

function xml2phpArray($xml,$arr){
    $iter = 0;
        foreach($xml->children() as $b){
                $a = $b->getName();
                if(!$b->children()){
                        $arr[$a] = trim($b[0]);
                }
                else{
                        $arr[$a][$iter] = array();
                        $arr[$a][$iter] = xml2phpArray($b,$arr[$a][$iter]);
                }
        $iter++;
        }
        return $arr;
}
?>