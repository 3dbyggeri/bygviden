<?php
class house
{
    var $dba;
    var $p;
    var $id;
    var $table;
    var $arrows = array('1'=>array('x'=>80,'y'=>-50),
                        '2'=>array('x'=>60,'y'=>-30),
                        '3'=>array('x'=>80,'y'=>0),
                        '4'=>array('x'=>83,'y'=>0),
                        '5'=>array('x'=>80,'y'=>0),
                        '6'=>array('x'=>55,'y'=>0),
                        '7'=>array('x'=>-10,'y'=>0),
                        '8'=>array('x'=>-50,'y'=>5),
                        '9'=>array('x'=>-50,'y'=>0),
                        '10'=>array('x'=>-50,'y'=>-32),
                        '11'=>array('x'=>-20,'y'=>-60),
                        '12'=>array('x'=>55,'y'=>-60));

    function arrowImg($pointer_id)
    {
        $img_id = $pointer_id;
        if($pointer_id > 6 ) $img_id = abs(($pointer_id + 6) - 12);
        if($img_id == 0) $img_id = 12;
        return $img_id.'.gif';
    }
    function house($dba)
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->table = $this->p.'houses';
    }
    function props($id)
    {
        $sql ="SELECT * FROM ".$this->table." WHERE id=$id";
        return $this->dba->singleArray($sql);
    }
    function removing($id)
    {
        if(!is_numeric($id)) return;
        $this->dba->exec("DELETE FROM ".$this->table." WHERE id=$id");
    }
    function listing($branche)
    {
        $sql = "SELECT * FROM ".$this->table." WHERE branche_id='$branche'";
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );
    
        $a =array(); 
        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );
          $a[$i]=$rec;
        }
        return $a;
    }
    function save($id,$x,$y,$branche,$label,$link,$pointer)
    {
        if(!is_numeric($x)) $x = 190;
        if(!is_numeric($y)) $y = 190;
        if($id=='-1')
        {
            $sql = "INSERT INTO ".$this->table." (x,y,branche_id,label,link,pointer)
                    VALUES ('$x','$y','$branche','".addslashes($label)."','".addslashes($link)."','$pointer')";
            $this->dba->exec($sql);
            return $this->dba->last_inserted_id();
        }
        $sql = "UPDATE ".$this->table." SET 
                x=$x,
                y=$y, 
                branche_id='$branche',
                label='".addslashes($label)."',
                link='".addslashes($link)."',
                pointer='$pointer'
                WHERE id=$id";
        $this->dba->exec($sql);
        return $id;
    }
    
}
?>
