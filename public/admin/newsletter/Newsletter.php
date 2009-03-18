<?php
class newsletter
{
    var $dba;
    var $p;
    var $id;
    var $table;

    function newsletter( $dba )
    {
      $this->dba = $dba;
      $this->p   = $this->dba->getPrefix();
      $this->table = $this->p.'newsletter';
    }
    function add()
    {
      $n = $this->dba->singleQuery("SELECT MAX(id) FROM ".$this->table);
      $n = intval($n) + 1;
      $sql = "INSERT INTO ". $this->table ." ( name,created) VALUES ('Nyhedsbrev #".$n."',NOW())";
      $this->dba->exec( $sql );
      return $this->dba->last_inserted_id();
    }
    function load($id)
    {
        $r = $this->dba->singleArray("SELECT * FROM ".$this->table." WHERE id=".$id);
        $r['name'] = stripslashes($r['name']);
        return $r;
    }
    function getNews($news)
    {
        $sql = "SELECT
                  n.id,
                  n.title,
                  n.body,
                  n.website,
                  n.image,
                  n.created,
                  n.producent,
                  p.name as producer
                FROM
                  ".$this->p."adnews as n,
                  ".$this->p."producer as p
                WHERE
                  p.id = n.producent
                AND 
                  n.id IN(".$news.")";
        $r = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );
          $r['p_'.$rec['id']] = $rec; //index by p-id to mantain the order
        }
        return $r;
    }
    function paragraphPosition($id)
    {
        $sql = "SELECT position 
                FROM ".$this->p."newsletter2paragraph 
                WHERE paragraph=".$id;
        $n = $this->dba->singleQuery($sql);
        return intval($n);
    }
    function formatNewsBody($rec)
    {
        $body='';
        $body.='<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                  <td valign="top">
                    <div><strong>'.$rec['title'].'</strong></div>
                    <div><a target="_blank" style="font-size:10px" 
                            href="http://www.bygviden.dk/?action=products&section=producenter&id='.$rec['producent'].'">
                            Fra '. $rec['producer'].'</a>
                    </div>
                    <p>'.$rec['body'].'</p>';
        if($rec['website'])
        {
            $body.='<div><a href="'.$rec['website'].'">L&aelig;s mere p&aring; '.$rec['website'].'</a></div>';
        }
        $body.='</td><td valign="top" align="right">';
        if($rec['image'] && file_exists(realpath('../../logo').'/'.$rec['image']))
        {
          $body.='<img src="http://www.bygviden.dk/logo/'.$rec['image'].'" border="0"/>';
        }
        $body.='</td></tr></table>';

        return $body;
    }
    function formatProfileBody($rec)
    {
        $body = '';
        if($rec['logo_url'] && file_exists(realpath('../../logo').'/'.$rec['logo_url']))
        {
          $body.= '<a class="profile_logo" target="_blank" style="font-size:10px" 
                           href="http://www.bygviden.dk/?action=products&section=producenter&id='.$rec['id'].'">
                  <img src="http://www.bygviden.dk/logo/'.$rec['logo_url'].'" border="0" /></a>';
        }
        $body.='<div><strong>'.$rec['name'].'</strong></div>';
        $body.='<p>'.$rec['description'].'</p>';

        $body.= '<div><a target="_blank" style="font-size:10px" ';
        $body.='href="http://www.bygviden.dk/?action=products&section=producenter&id='.$rec['id'].'">';
        $body.='Se produkterne</a></div>';

        if($rec['home_page'])
        {
            $body.='<div><a href="'.$rec['home_page'].'">L&aelig;s mere p&aring; '.$rec['home_page'].'</a></div>';
        }
        return $body;
    }
    function updateProfile($id,$rec)
    {
        $sql="UPDATE ".$this->p."newsletterparagraph 
              SET body='".addslashes($this->formatProfileBody($rec))."',
              reference=".$rec['id'].",
              referencetype='P'
              WHERE id=".$id;
        $this->dba->exec($sql);
    }
    function insertProfile($rec)
    {
        $sql="INSERT INTO 
                ".$this->p."newsletterparagraph 
              (created,body,reference,referencetype) 
              VALUES
              (
                NOW(),
                '".addslashes($this->formatProfileBody($rec))."',
                ".$rec['id'].",'P'
               )";
        $this->dba->exec($sql);
        return $this->dba->last_inserted_id();
    }
    function updateNews($id,$rec)
    {
        $sql="UPDATE ".$this->p."newsletterparagraph 
              SET body='".addslashes($this->formatNewsBody($rec))."',
              reference=".$rec['producent'].",
              referencetype='N'
              WHERE id=".$id;
        $this->dba->exec($sql);
    }
    function insertNews($rec)
    {
        $sql="INSERT INTO 
                ".$this->p."newsletterparagraph 
              (created,body,reference,referencetype) 
              VALUES
              (
                NOW(),
                '".addslashes($this->formatNewsBody($rec))."',
                ".$rec['producent'].",'N'
               )";
        $this->dba->exec($sql);
        return $this->dba->last_inserted_id();
    }
    function maxParagraphPosition($id,$column)
    {
        $sql="SELECT MAX(position) FROM 
             ".$this->p."newsletter2paragraph 
             WHERE newsletter=".$id." AND col =".$column;
        echo 'max:'.$sql.'<br>';
        $n=$this->dba->singleQuery($sql);
        if(!$n) $n= "0";
        $n = intval($n);
        $n+=1;
        
        return $n;
    }
    function paragraphsAfter($id,$column,$position)
    {
        $sql="SELECT 
                paragraph 
             FROM 
             ".$this->p."newsletter2paragraph 
             WHERE 
                newsletter=".$id." 
             AND 
                col =".$column."
             AND
                position > ".$position." 
            ORDER 
                BY position";

        $r = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );
          $r[$i] = $rec['paragraph']; 
        }
        return $r;
    }
    function updateParagraphPositions($position,$ids)
    {
        for($i=0;$i<count($ids);$i++)
        {
             $sql="UPDATE ".$this->p."newsletter2paragraph 
                   SET position=".$position." 
                   WHERE paragraph=".$ids[$i];
             echo 'new pos:'.$sql.'<br>';
             $this->dba->exec($sql);
             $position++;
        }
    }
    function getProfiles($profiles)
    {
        $r = array();
        $sql = "SELECT * FROM ".$this->p."producer WHERE id IN(".$profiles.")";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );

          $rec['name'] = stripslashes($rec['name']);  
          $rec['description'] = stripslashes($rec['description']);  
          $r['p_'.$rec['id']] = $rec; //index by p-id to mantain the order
        }
        return $r;
    }
    function updatePosition($col1,$col2)
    {
        if(trim($col1))
        {
            $col1 = split(',',$col1);
            for($i=0;$i<count($col1);$i++)
            {
                $sql="UPDATE ".$this->p."newsletter2paragraph SET position=". ($i+1) ." WHERE paragraph=".$col1[$i];
                $this->dba->exec($sql);
            }
        }

        if(trim($col2))
        {
            $col2 = split(',',$col2);
            for($i=0;$i<count($col2);$i++)
            {
                $sql="UPDATE ".$this->p."newsletter2paragraph SET position=". ($i+1) ." WHERE paragraph=".$col2[$i];
                $this->dba->exec($sql);
            }
        }
    }
    function addProfiles($id,$column,$profiles,$paragraph_id)
    {
        $records = $this->getProfiles($profiles);
        $p_after = array();
        if($paragraph_id!='-1') 
        {
            $pos = $this->paragraphPosition($paragraph_id);
            $p_after = $this->paragraphsAfter($id,$column,$pos);
            $n = $pos;
        }

        $profiles= split(',',$profiles);
        for($i=0;$i<count($profiles);$i++)
        {
            $rec = $records['p_'.$profiles[$i]];

            if($paragraph_id!='-1' && $i==0)
            {
                $this->updateProfile($paragraph_id,$rec);
                continue;
            }

            $p_id = $this->insertProfile($rec);
            if($paragraph_id !='-1')
            {
                $n++;
            }
            else
            {
                $n= $this->maxParagraphPosition($id,$column);
            }
            $sql = "INSERT INTO ".$this->p."newsletter2paragraph 
                    (newsletter,paragraph,position,col) 
                    VALUES(".$id.",".$p_id.",".$n.",".$column.")";
            $this->dba->exec($sql);
        }
        
        $pos = $pos + count($news);
        $this->updateParagraphPositions($pos,$p_after);
    }

    function addNews($id,$column,$news,$paragraph_id)
    {
        $records = $this->getNews($news);
        $p_after = array();
        if($paragraph_id!='-1') 
        {
            $pos = $this->paragraphPosition($paragraph_id);
            $p_after = $this->paragraphsAfter($id,$column,$pos);
            $n = $pos;
        }

        $news = split(',',$news);
        for($i=0;$i<count($news);$i++)
        {
            $rec = $records['p_'.$news[$i]];

            if($paragraph_id!='-1' && $i==0)
            {
                $this->updateNews($paragraph_id,$rec);
                continue;
            }

            $p_id = $this->insertNews($rec);
            if($paragraph_id !='-1')
            {
                $n++;
            }
            else
            {
                $n= $this->maxParagraphPosition($id,$column);
            }
            $sql = "INSERT INTO ".$this->p."newsletter2paragraph 
                    (newsletter,paragraph,position,col) 
                    VALUES(".$id.",".$p_id.",".$n.",".$column.")";
            $this->dba->exec($sql);
        }
        
        $pos = $pos + count($news);
        $this->updateParagraphPositions($pos,$p_after);
    }
    function createParagraph($id,$column)
    {
        $sql="INSERT INTO ".$this->p."newsletterparagraph (created,reference,referencetype) VALUES(NOW(),0,'T')";
        $this->dba->exec( $sql );
        $p_id = $this->dba->last_inserted_id();

        $sql="SELECT 
                 MAX(position) FROM ".$this->p."newsletter2paragraph 
              WHERE 
                newsletter=".$id."
              AND
                col =".$column;
        $n = $this->dba->singleQuery($sql);
        if(!$n) $n= "0";
        $n = intval($n);
        $n++;

        $sql = "INSERT INTO ".$this->p."newsletter2paragraph 
                (newsletter,paragraph,position,col) 
                VALUES(".$id.",".$p_id.",".$n.",".$column.")";
        $this->dba->exec($sql);
        return $p_id;
    }
    function updateParagraph($p_id,$body)
    {
        $sql = "UPDATE ".$this->p."newsletterparagraph 
                SET body='".addslashes($body)."' WHERE id=".$p_id;
        $this->dba->exec($sql);
    }
    function loadParagraph($p_id)
    {
        $sql= "SELECT * FROM ".$this->p."newsletterparagraph  WHERE id=".$p_id;
        $r = $this->dba->singleArray($sql);
        $r['body'] = stripslashes($r['body']);
        return $r;
    }
    function loadParagraphs($n_id)
    {
        $p = array('1'=>array(),'2'=>array());
        $sql= "SELECT 
                    p.id,p.body,n2p.col 
               FROM 
                ".$this->p."newsletterparagraph  as p, 
                ".$this->p."newsletter2paragraph  as n2p 
                WHERE 
                    n2p.paragraph= p.id
                AND
                    n2p.newsletter=".$n_id."
                ORDER BY
                    n2p.col,n2p.position";

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $r= $this->dba->fetchArray( $result );
          $r['body'] = stripslashes($r['body']);
          $p[$r['col']][ count($p[$r['col']]) ]= $r;
       }
       return $p;
    }

    function update($id,$name)
    {
        $this->dba->exec("UPDATE ".$this->table." SET name='". addslashes($name)."' WHERE id='".$id."'");
    }
    function mailed($id,$chimp_id)
    {
        $this->dba->exec("UPDATE ".$this->table." SET mailed=NOW(), chimp_id='".$chimp_id."' WHERE id='".$id."'");
    }
    function remove($id)
    {
        $this->dba->exec("DELETE FROM ".$this->table." WHERE id=".$id);
        $this->dba->exec("DELETE FROM ".$this->p."newsletter2paragraph WHERE newsletter=".$id);
    }
    function removeParagraph($id)
    {
        $this->dba->exec("DELETE FROM ".$this->p."newsletterparagraph WHERE id=".$id);
        $this->dba->exec("DELETE FROM ".$this->p."newsletter2paragraph WHERE paragraph=".$id);
    }
    function total()
    {
        return $this->dba->singleQuery($sql ="SELECT COUNT(*) FROM ".$this->table." WHERE active='y'");
    }
    function all( $offset = 0, $row_number = 20, $sorting_order ='asc' , $sorting_colum='id')
    {
        $r = array();
        $sql ="SELECT 
                    *
              FROM
                ". $this->table ."
              ORDER BY 
                $sorting_colum $sorting_order
              LIMIT $offset, $row_number";
       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $r[$i] = $this->dba->fetchArray( $result );
       }
       return $r;
    }
    function find($email)
    {
      $r = array();
      $sql = "SELECT * FROM ". $this->table ." WHERE email LIKE '%".$search."%' ";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
         $r[$i] = $this->dba->fetchArray( $result );
      }
      return $r;
    }
    function setActive( $id,$state)
    {
      $sql = "UPDATE 
                ". $this->table ."
              SET
                active = '". $state ."'
              WHERE
                id = ". $id; 
      $this->dba->exec( $sql );
    }


 }
?>
