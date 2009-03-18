<?php
class temaDoc
{
  var $id;
  var $props = Array();
  
  function pageExtendedProperties($page_id)
  {
    $props = $this->pageProperties($page_id);
    $pages = $this->pagesForTema($props['tema_id']);
    $prev = array();
    $next = array();
    for($i=0;$i<count($pages);$i++)
    {
        if($page_id != $pages[$i]['id']) continue;
        if($i > 0) $prev = $pages[$i - 1];
        if($i + 1 < count($pages)) $next = $pages[$i + 1];
    }
    $props['prev'] =$prev;
    $props['next'] =$next;

    return $props;
  }
  function temaDoc($dba)
  {
     $this->dba = $dba;
     $this->p   = $this->dba->getPrefix();
     $this->table = $this->p .'tema';
     $this->page_table = $this->p .'tema_page';
     $this->kilde_table = $this->p .'tema_kilde';
  }
  function pageProperties($id)
  {
    $sql = "SELECT ".$this->page_table .".*,
            ".$this->table.".name as tema_name, 
            ".$this->table.".icon as tema_icon
            FROM ".$this->page_table.",".$this->table." 
            WHERE ".$this->page_table.".id=".$id." 
            AND ".$this->table.".id = ".$this->page_table.".tema_id";
    $props = $this->dba->singleArray( $sql );
    if($props)
    {
      $props['name'] = stripslashes($props['name']);
      $props['body'] = stripslashes($props['body']);
      $this->props = $props;
    }
    return $props;
  }

  function pagesForTema($id)
  {
    $sql = "SELECT * FROM ".$this->page_table." WHERE tema_id=".$id." ORDER BY position";
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    
    $pg =array(); 
    for( $i = 0; $i < $n; $i++ )
    {
      $pg[$i] = $this->dba->fetchArray( $result );
      $pg[$i]['name'] = stripslashes($pg[$i]['name']);
      $pg[$i]['body'] = stripslashes($pg[$i]['body']);
      $pg[$i]['key'] = $pg[$i]['id'];
      $pg[$i]['value'] = $pg[$i]['name'];
    }
    return $pg;
  }
  function maxPosition($table,$tema_id)
  {
    $max = $this->dba->singleQuery("SELECT MAX( position ) FROM ". $table." WHERE tema_id=". $tema_id);
    if(!is_numeric($max)) $max = 0;
    $max++;
    return $max;
  }
  function saveBuilding($id,$tema_id,$node_id)
  {
    if($id=='-1')
    {
        $sql = "INSERT INTO ".$this->p."tema_bygning (tema_id,node_id,position) 
                VALUES ($tema_id,'". $node_id."',".$this->maxPosition($this->p.'tema_bygning',$tema_id) .")"; 
        $this->dba->exec( $sql );
        return $this->dba->last_inserted_id();
    }

    $this->dba->exec("UPDATE ". $this->p."tema_bygning SET node_id= '". $node_id ."' WHERE id=". $id);
    return $id;
  }
  function saveKilde($id,$tema_id,$name,$url,$comment,$kilde_type,$bib_kilde_id)
  {
    $is_bibliotek = ($kilde_type=='extern')? 'n':'y';
    if($id=='-1')
    {
        $max = $this->maxPosition($this->kilde_table,$tema_id);
        $sql = "INSERT INTO ".$this->kilde_table." (tema_id,name,url,comment,is_bibliotek,kilde_id,position) 
                VALUES ($tema_id,'". addslashes($name)."','". addslashes($url)."',
                '". addslashes($comment) ."','". $is_bibliotek ."','". $bib_kilde_id ."',". $max .")";
        $this->dba->exec( $sql );
        return $this->dba->last_inserted_id();
    }

    $sql = "UPDATE 
                ". $this->kilde_table ." 
            SET 
                name= '". addslashes( $name ) ."', 
                url = '". addslashes( $url) ."', 
                is_bibliotek = '". $is_bibliotek."', 
                kilde_id = '". $bib_kilde_id."', 
                comment = '". addslashes( $comment) ."' 
            WHERE id=". $id;
    $this->dba->exec( $sql );
    return $id;


  }
  function temaBoxProperties($id)
  {
    $sql = "SELECT * FROM ".$this->p."tema WHERE forside_slot=$id";

    $props = $this->dba->singleArray( $sql );
    if($props)
    {
      $props['forside_name'] = stripslashes($props['forside_name']);
      $props['forside_category'] = stripslashes($props['forside_category']);
      $props['forside_resume'] = stripslashes($props['forside_resume']);
    }
    return $props;
  }

  function getForsideTema()
  {
    $sql = "SELECT * FROM ".$this->p."tema WHERE forside_slot IS NOT NULL";
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    
    $temaer=array(); 
    for( $i = 0; $i < $n; $i++ )
    {
      $rec = $this->dba->fetchArray( $result );
      
      $idx =  $rec['forside_slot'];
      $temaer[$idx] = $rec;
      $temaer[$idx]['name'] = stripslashes($rec['name']);
      $temaer[$idx]['icon'] = stripslashes($rec['icon']);
      $temaer[$idx]['resume'] = stripslashes($rec['resume']);
      $temaer[$idx]['forside_name'] = stripslashes($rec['forside_name']);
      $temaer[$idx]['forside_category'] = stripslashes($rec['forside_category']);
      $temaer[$idx]['forside_resume'] = stripslashes($rec['forside_resume']);
    }
    return $temaer;
  }
  function saveTemaBox($id,$tema,$name,$category,$resume)
  {
    $sql = "UPDATE 
                ".$this->p."tema 
            SET 
                forside_slot = NULL
            WHERE
                forside_slot =$id";
    $this->dba->exec( $sql );

    //save
    $sql = "UPDATE 
                ".$this->p."tema 
            SET 
                forside_slot=$id, 
                forside_name='". addslashes($name)."',
                forside_category ='". addslashes($category)."',
                forside_resume ='". addslashes($resume)."'
            WHERE
                id=$tema";
    $this->dba->exec( $sql );
  }
  function savePage($id,$tema_id,$name,$body)
  {
    if($id=='-1')
    {
        $sql = "INSERT INTO ".$this->page_table." (tema_id,name,body,position) 
                VALUES ($tema_id,'". addslashes($name)."'
                ,'". addslashes($body)."'
                ,".$this->maxPosition($this->page_table,$tema_id).")";
        $this->dba->exec( $sql );
        return $this->dba->last_inserted_id();
    }
    $sql = "UPDATE 
                ". $this->page_table ." 
            SET 
                name= '". addslashes( $name ) ."', 
                body= '". addslashes( $body ) ."' 
            WHERE id=". $id;
    $this->dba->exec( $sql );
    return $id;
  }
  function allPublic()
  {
    $sql = "SELECT * FROM ".$this->table." WHERE publish='y' AND private !='y' ORDER BY position";
    return $this->getAll($sql);
  }
  function allPublicAndOwned($uid)
  {
    $sql = "SELECT * FROM ".$this->table." WHERE publish='y' AND (private ='n' OR creator=". $uid.") ORDER BY private,position";
    return $this->getAll($sql);
  }
  function allOwned($uid)
  {
    $sql = "SELECT * FROM ".$this->table." WHERE publish='y' AND private ='y' AND creator=". $uid." ORDER BY position";
    return $this->getAll($sql);
  }

  function getAll($sql)
  {
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    
    $temaer=array(); 
    for( $i = 0; $i < $n; $i++ )
    {
      $rec  = $this->dba->fetchArray( $result );
      $temaer[$i] = $rec;
      $temaer[$i]['name'] = stripslashes($temaer[$i]['name']);
      $temaer[$i]['icon'] = stripslashes($temaer[$i]['icon']);
      $temaer[$i]['resume'] = stripslashes($temaer[$i]['resume']);
      $temaer[$i]['key'] = $temaer[$i]['id'];
      $temaer[$i]['value'] = $temaer[$i]['name'];
    }
    return $temaer;
  }
  function all()
  {
    $sql = "SELECT * FROM ".$this->table." WHERE publish='y' ORDER BY private,position";
    return $this->getAll($sql);
  }
  function getByName($name)
  {
    $sql = "SELECT * FROM ".$this->table." WHERE name='".addslashes($name)."'";
    return $this->loading($sql);
  }
  function getById($id)
  {
    $sql = "SELECT 
                ".$this->table.".*, 
                ".$this->p."tema_editor.id as author_id,
                ".$this->p."tema_editor.name as author_name,
                ".$this->p."tema_editor.email as author_email,
                ".$this->p."tema_editor.resume as author_resume,
                ".$this->p."tema_editor.title as author_title
            FROM ".$this->table." 
            LEFT JOIN
                ".$this->p."tema_editor
            ON
                ".$this->table.".editor_id = ".$this->p."tema_editor.id
            WHERE ".$this->table.".id=".$id;
    return $this->loading($sql);
  }
  function editing() 
  { 
    if(!isAnyoneLogged()) return false;
    return $this->id =='-1' || $_REQUEST['edit']; 
  }
  function authorPortrait()
  {
    $file = 'tema/graphics/portraits/'.$this->prop('author_id').'.jpg';
    return '<img src="'.$file.'" id="author_portrait" onerror="hidePortrait(this)" 
                style="margin-right:3px"
                onload="checksize(this)" align="left" valign="absbottom">';
  }
  function authorTitle($edit=0)
  {
    $str = $this->prop('author_title','');
    if($edit) return '<div style="font-weight:900" id="author_title">'.$str.'</div>';
    if(!$str) return;

    return '<div style="font-weight:900">'.$str.'</div>';
  }
  function authorName($edit=0)
  {
    $n = $this->prop('author_name','');
    if($edit) return '<div style="font-weight:900" id="author_name">'.$n.'</div>';
    if(!$n) return;
    return '<strong>'.$n.'</strong><br>';
  }
  function authorDescription($edit=0)
  {
    $str = $this->prop('author_resume');
    if($edit) return '<div id="author_resume">'.$str.'</div>';
    if(!$str) return;
    return '<div>'.$str.'</div>';
  }
  function authorEmail($edit=0)
  {
    $str = $this->prop('author_email');
    if($edit) return '<div id="author_email">'.$str.'</div>';
    if(!$str) return;
    return '<a href="mailto:'.$str.'" class="link">'.$str.'</a>';
  }
  function EditorProperties($id)
  {
    $sql ="SELECT * FROM ".$this->dba->getPrefix()."tema_editor  WHERE id=$id";
    return $this->dba->singleArray($sql);
  }
  function UpdateEditor($id,$name,$title,$email,$resume)
  {
    if($id=='-1') 
    {
        $this->dba->exec("INSERT  INTO ".$this->dba->getPrefix()."tema_editor (name) VALUES('new editor')");
        $id = $this->dba->last_inserted_id();
    }
    $sql = "UPDATE 
                ". $this->dba->getPrefix()."tema_editor 
            SET 
                name= '". addslashes( $_REQUEST['name'] ) ."', 
                title= '". addslashes( $_REQUEST['title'] ) ."', 
                email= '". addslashes( $_REQUEST['email'] ) ."', 
                resume = '". addslashes( $_REQUEST['resume'] ) ."' 
            WHERE id=". $id;
    $this->dba->exec( $sql );
    return $id;
  }

  function removeEditor($id) 
  {
    $this->dba->exec("DELETE FROM ".$this->p."tema_editor WHERE id=$id");
  }
  function authorSelector()
  {
    $result = $this->dba->exec("SELECT * FROM ".$this->dba->getPrefix()."tema_editor ORDER BY name");
    $n = $this->dba->getN( $result );
    
    $str = '<br />
            <script>var editors = {}; </script>
            <strong>Redakt&oslash;r</strong>
            <a href="javascript:editEditor(-1)"><img src="admin/graphics/add.png" align="absbottom" border="0" /></a>
            <a href="javascript:removeEditor()"><img src="admin/graphics/delete.png" align="absbottom" border="0" /></a>
            <a href="javascript:editEditor(0)"><img border="0" src="tema/graphics/edit.png" align="absbottom" /></a>
            <br />
            <select style="width:200px" id="editorlist" onchange="loadeditor()">
                <option value="-">Valg</option>
            ';
    for( $i = 0; $i < $n; $i++ )
    {
      $rec = $this->dba->fetchArray( $result );
      $rec['name'] = stripslashes($rec['name']);
      $rec['title'] = stripslashes($rec['title']);
      $rec['email'] = stripslashes($rec['email']);
      $rec['resume'] = stripslashes($rec['resume']);
      
      $ph = 'tema/graphics/portraits/'.$rec['id'].'.jpg';
      $sel = '';
      if($rec['id'] == $this->prop('author_id') ) $sel = 'selected';

      $str.='<option value="'.$rec['id'].'" '.$sel.'>'.$rec['name'].'</option>';
      $str.='<script>editors["'.$rec['id'].'"] = {"id":"'. $rec['id'].'",
                                                  "name":"'.$rec['name'].'",
                                                  "portrait":"'.$ph.'",
                                                  "title":"'.$rec['title'].'",
                                                  "email":"'.$rec['email'].'",
                                                  "resume":"'.$rec['resume'].'"};</script>';
    }
    $str.='</select>';
    return $str;
  }
  function ownerEdit()
  {
    $name = stripslashes($this->prop('creator_name'));
    $mail = stripslashes($this->prop('creator_email'));
    return '<div class="kilder">
                <h2>Kontakt Person</h2>

                <table cellpadding="3" cellspacing="0" border="0">
                    <tr>
                        <td> Navn: </td>
                        <td>
                            <input type="text" name="creator_name" value="'.$name.'"/>
                        </td>
                   </tr>
                    <tr>
                        <td> Email: </td>
                        <td>
                            <input type="text" name="creator_email" value="'.$mail.'"/>
                        </td>
                   </tr>
                  </table>
                
            </div>';
  }
  function ownerDisplay($edit)
  {
    $name = stripslashes($this->prop('creator_name'));
    $mail = stripslashes($this->prop('creator_email'));
     if(!$name && !$mail) return;
     return '<div class="right_section">
                    <h2>Kontakt Person</h2>
                    <a href="mailto:'.$mail.'">'.$name.'</a>
            </div>';
  }
  function author($edit=0)
  {
    if($this->isNormalUser() && $this->isOwner() ) return $this->ownerDisplay($edit); 

    $name = $this->authorName($edit);
    $sel = ($edit)? $this->authorSelector():'';

    if(!$name && !$sel) return;
    return '<div class="right_section">
                <h2>Kontakt</h2>
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            '.$this->authorPortrait($edit).'
                        </td>
                        <td valign="bottom">
                            '.$this->authorTitle($edit).'
                            '.$name.'
                            '.$this->authorDescription($edit).'
                            '.$this->authorEmail($edit).'
                        </td>
                   </tr>
                </table>
                '.$sel.'
            </div>';
  }
  function className()
  {
    return $this->prop('icon','obs');
  }
  function classChooser()
  {
    global $ICONS;
    if(!$this->editing()) return;
    $str = '<select name="icon" onchange="updateIconClass(this,\'tema_header\')" style="width:150px" >
                <option value="-">V&aelig;lg ikon</option>';
    foreach($ICONS as $key=>$value)
    {
        $sel = ($this->prop('icon') == $key)? 'selected':'';
        $str.='<option value="'.$key.'" '.$sel.'>'.$key.'</option>';
    }
    $str.= '</select>';
    return $str;
  }
  function kildeProperties($id)
  {
    $sql ="SELECT 
          ".$this->kilde_table.".*, 
          ".$this->p."kildestyring.name as kilde_name
        FROM 
          ".$this->kilde_table." 
        LEFT JOIN
          ".$this->p."kildestyring
        ON 
            ".$this->kilde_table.".kilde_id = ".$this->p."kildestyring.id
        WHERE 
            ".$this->kilde_table.".id=".$id; 

    $props = $this->dba->singleArray( $sql );
    if($props)
    {
      $props['name'] = stripslashes($props['name']);
      $props['url'] = stripslashes($props['url']);
      $props['comment'] = stripslashes($props['comment']);
    }
    return $props;
  }
  function kilderForTema($id)
  {

    $sql ="SELECT 
          * 
        FROM 
          ".$this->kilde_table." 
        WHERE 
            ".$this->kilde_table.".tema_id=".$id." 
        ORDER BY position";

    $bib_ids = '';
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    
    $pg =array(); 
    for( $i = 0; $i < $n; $i++ )
    {
      $pg[$i] = $this->dba->fetchArray( $result );
      if($pg[$i]['is_bibliotek']=='y') 
      {
        if($bib_ids  != '') $bib_ids.=',';
        $bib_ids.= $pg[$i]['kilde_id'];
      }

      $pg[$i]['name'] = stripslashes($pg[$i]['name']);
      $pg[$i]['comment'] = stripslashes($pg[$i]['body']);
      $pg[$i]['url'] = stripslashes($pg[$i]['url']);
      $pg[$i]['key'] = $pg[$i]['id'];
      $pg[$i]['value'] = $pg[$i]['name'];
    }

    //get kilderne
    if($bib_ids !='')
    {
        $bib_names = $this->getBiBNames($bib_ids);
        for($i=0;$i< count($pg);$i++)
        {
            if(!$bib_names[$pg[$i]['kilde_id']]) continue;
            $pg[$i]['name'] = $bib_names[$pg[$i]['kilde_id']]['name'];
            $pg[$i]['lev_id'] = $bib_names[$pg[$i]['kilde_id']]['lev_id'];
            $pg[$i]['value'] = $pg[$i]['name'];
        }
    }
    return $pg;
  }
  function getBiBNames($bib_ids)
  {
    $kilder = array();
    $sql = "SELECT
                pub.id  as pub_id,
                CONCAT_WS(' - ',lev.name,kat.name,pub.name) as name,
                lev.id as lev_id
            FROM
                ".$this->p."kildestyring as pub,
                ".$this->p."kildestyring as kat,
                ".$this->p."kildestyring as lev
            WHERE
                pub.parent = kat.id
            AND
                kat.parent = lev.id
            AND
                pub.id IN (".$bib_ids.")";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    
    for( $i = 0; $i < $n; $i++ )
    {
      $rec = $this->dba->fetchArray( $result );
      $kilder[$rec['pub_id']] = $rec;
    }
    return $kilder;
  }
  function kildeList($id)
  {
    $kilder = $this->kilderForTema($id);
    $addcallback = "addListItem('Kilde','".$id."')";
    $editcallback = "editListItem('Kilde','kilder','".$id."')";

    if($this->editing()) return listWidget('kilder',$kilder,$addcallback,$editcallback);

    if(!$kilder) return;
    $str='<ul class="page_list">';
    $hidden_links = '';
    for($i=0;$i<count($kilder);$i++)
    {
       $url = ($kilder[$i]['is_bibliotek']=='y')?'?action=bibliotek&lev='.$kilder[$i]['lev_id'].'#pub_'.$kilder[$i]['kilde_id']:$kilder[$i]['url'];
       $target=($kilder[$i]['is_bibliotek']=='y')? '':'target="_blank"';

       if($i > 2)
       {
        $hidden_links.='<li><a href="'.$url.'" '.$target.'>'.$kilder[$i]['name'].'</a></li>';
       }
       else
       {
        $str.='<li><a href="'.$url.'" '.$target.'>'.$kilder[$i]['name'].'</a></li>';
       }
    }

    if($hidden_links)
    {
        $str.='<li><a class="readmore" href="javascript:toggleCat(\'kilde\')">L&aelig;s mere <img align="absbottom" src="tema/graphics/arrowdown.gif" id="img_kilde" border="0" /></a></li>';
        $hidden_links = '<ul class="page_list" id="pubs_kilde" style="display:none">'.$hidden_links.'</ul>';
    }
    $str.='</ul>';
    $str.=$hidden_links;
    return $str;
  }
  function temaForLeverandor($id)
  {
    $sql = "SELECT 
                DISTINCT ".$this->p."tema.id,
                ".$this->p."tema.*
            FROM
                ".$this->p."tema,
                ".$this->p."tema_kilde,
                ".$this->p."kildestyring as lev,
                ".$this->p."kildestyring as cat,
                ".$this->p."kildestyring as pub
            WHERE
                ".$this->p."tema.id = ".$this->p."tema_kilde.tema_id
            AND
                ".$this->p."tema_kilde.is_bibliotek = 'y'
            AND
                pub.parent = cat.id
            AND
                cat.parent = lev.id
            AND
                ".$this->p."tema_kilde.kilde_id = pub.id
            AND
                lev.id=".$id;

    if($_SESSION['bruger_id']) $sql.=' AND '.$this->p.'tema.creator='. $_SESSION['bruger_id'];
    $sql.=" ORDER BY ".$this->p."tema.private, ".$this->p."tema.position ";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    
    $pg =array(); 
    for( $i = 0; $i < $n; $i++ )
    {
      $pg[$i] = $this->dba->fetchArray( $result );
      $pg[$i]['key'] = $pg[$i]['id'];
      $pg[$i]['value'] = $pg[$i]['name'];
    }
    return $pg;
  }
  function temaForBygningsdel($id)
  {
    $sql = "SELECT 
                DISTINCT ".$this->p."tema.id,
                ".$this->p."tema.* 
            FROM
                ".$this->p."tema,
                ".$this->p."tema_bygning
            WHERE
                ".$this->p."tema.id = ".$this->p."tema_bygning.tema_id
            AND
                ".$this->p."tema_bygning.node_id = ".$id;
    if($_SESSION['bruger_id']) $sql.=' AND '.$this->p.'tema.creator='. $_SESSION['bruger_id'];
    $sql.=" ORDER BY ".$this->p."tema.private, ".$this->p."tema.position ";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    
    $pg =array(); 
    for( $i = 0; $i < $n; $i++ )
    {
      $pg[$i] = $this->dba->fetchArray( $result );
      $pg[$i]['key'] = $pg[$i]['id'];
      $pg[$i]['value'] = $pg[$i]['name'];
    }
    return $pg;
  }
  function bygningsdelForTema($id)
  {
    $sql ="SELECT 
          ".$this->p."tema_bygning.id,
          ".$this->p."tema_bygning.tema_id,
          ".$this->p."buildingelements.name,
          ".$this->p."branche_tree.id as byg
          FROM
            ".$this->p."buildingelements,
            ".$this->p."branche_tree,
            ".$this->p."tema_bygning
          WHERE 
            ".$this->p."tema_bygning.tema_id=".$id." 
          AND
            ".$this->p."tema_bygning.node_id = ".$this->p."branche_tree.id
          AND
            ".$this->p."branche_tree.element_id = ".$this->p."buildingelements.id
          ORDER BY ".$this->p."tema_bygning.position";
    
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    
    $pg =array(); 
    for( $i = 0; $i < $n; $i++ )
    {
      $pg[$i] = $this->dba->fetchArray( $result );
      $pg[$i]['key'] = $pg[$i]['id'];
      $pg[$i]['value'] = $pg[$i]['name'];
    }
    return $pg;
  }
  function bygningsdel($id)
  {
    $addcallback = "addListItem('Bygningsdel','".$id."')";
    $editcallback = "editListItem('Bygningsdel','bygningsdel','".$id."')";

    $bygningsdele = $this->bygningsdelForTema($id);

    if($this->editing()) return listWidget('bygningsdel',$bygningsdele,$addcallback,$editcallback);

    return $this->bygningList($bygningsdele); 
  }
  function bygningList($bygningsdele)
  {
    if(!$bygningsdele) return; 
    $str='<ul class="page_list">';
    $hidden_links = '';
    for($i=0;$i<count($bygningsdele);$i++)
    {
        if($i > 2)
        {
            $hidden_links.='<li><a href="?byg='.$bygningsdele[$i]['byg'].'">'.$bygningsdele[$i]['name'].'</a></li>';
        }
        else
        {
            $str.='<li><a href="?byg='.$bygningsdele[$i]['byg'].'">'.$bygningsdele[$i]['name'].'</a></li>';
        }
    }
    if($hidden_links)
    {
        $str.='<li><a class="readmore" href="javascript:toggleCat(\'building\')">L&aelig;s mere <img align="absbottom" src="tema/graphics/arrowdown.gif" id="img_building" border="0" /></a></li>';
        $hidden_links = '<ul class="page_list" id="pubs_building" style="display:none">'.$hidden_links.'</ul>';
    }
    $str.='</ul>';
    $str.=$hidden_links;
    return $str;
  }
  function pageList($pages)
  {
    if(!$pages) return; 
    $str='<ul class="page_list">';
    for($i=0;$i<count($pages);$i++)
    {
       $str.='<li><a href="?tema='.$pages[$i]['tema_id'].'&page='.$pages[$i]['id'].'">'.$pages[$i]['name'].'</a></li>';
    }
    $str.='</ul>';
    return $str;
  }
  function siderAdministration($id)
  {
    $pages = $this->pagesForTema($id);
    if(!$this->editing()) return $this->pageList($pages);
    if($this->isNormalUser()) return;

    $addcallback = "addListItem('Page','".$id."',700,600)";
    $editcallback = "editListItem('Page','sider','".$id."',700,600)";

    return '<div class="kilder">
                <h2>Sider</h2>
                '.listWidget('sider',$pages,$addcallback,$editcallback ).'
            </div>';
  }
  
  function resume()
  {
    if($this->editing()) 
    {
        if($this->isNormalUser())
        {
            return '<textarea name="resume" style="width:500px;height:150px">'.$this->prop('resume','Tema introduktion').'</textarea>';
        }
        return '
        <script language="javascript" type="text/javascript" src="admin/home/tiny_mce/tiny_mce.js"></script>
        <script language="javascript" type="text/javascript">
            tinyMCE.init({
                mode : "textareas",
                theme : "advanced",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align: "left",
                theme_advanced_buttons1 : "formatselect,removeformat,separator,bold,italic,separator,link,unlink,separator,bullist,numlist,separator,image,cleanup,code,separator,undo,redo",
                theme_advanced_buttons2 : "tablecontrols",
                theme_advanced_buttons3 : "",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,
                plugins : "table"
            });
        </script>
        <textarea name="resume" style="width:600px;height:150px">'.$this->prop('resume','Tema introduktion').'</textarea>';
    }
    
    return $this->prop('resume','Tema introduktion');
  }
  function name()
  {
    if($this->editing()) return '<input type="text" name="name" value="'.$this->prop('name','Tema navn').'">';
    return $this->prop('name','Tema navn');
  }

  function prop($n,$default='')
  {
    $v = $this->props[$n];
    if(!$v) $v = ($default)? $default:'';
    return $v;
  }
  function load($id)
  {
    $this->id = $id;
    if($id == '-1') return;
    $this->getById($id);
  }
  function isOwner()
  {
    if(!$_SESSION['bruger_id']) return false;
    return ($this->prop('creator') == $_SESSION['bruger_id'] );
  }
  function loading($sql)
  {
      $props = $this->dba->singleArray( $sql );
      if($props)
      {
          $props['name'] = stripslashes($props['name']);
          $props['body'] = stripslashes($props['body']);
          $this->props = $props;
      }
      return $props;
  }

  function updateTemaList($temaer,$original_list)
  {
    $original = explode(',',$original_list);
    $new_list = explode(',',$temaer);
    $tobdeleted = array();

    for($i=0;$i<count($original);$i++)
    {
        if(in_array($original[$i],$new_list)) continue;
        $tobdeleted[] = $original[$i];
    }
    $tobdeleted = implode(',',$tobdeleted);
    
    if($tobdeleted) 
    {
        $this->dba->exec("DELETE FROM ".$this->table." WHERE id IN(".$tobdeleted.")");
        $this->dba->exec("DELETE FROM ".$this->p."tema_kilde WHERE tema_id IN(".$tobdeleted.")");
        $this->dba->exec("DELETE FROM ".$this->p."tema_bygning WHERE tema_id IN(".$tobdeleted.")");
        $this->dba->exec("DELETE FROM ".$this->p."tema_page WHERE tema_id IN(".$tobdeleted.")");
    }

    for($i=0;$i<count($new_list);$i++)
    {
        if(!$new_list[$i]) continue;
        $this->dba->exec("UPDATE ".$this->table." SET position=".$i." WHERE id=".$new_list[$i]);
    }
  }
  
  function saveList($id,$table_name,$list_name)
  {
    //remove from list
    $ids = $_REQUEST['list_'.$list_name];
    $sql = "DELETE FROM ".$table_name." WHERE tema_id=".$id;
    if($ids) $sql.=" AND id NOT IN(".$ids.")";

    $this->dba->exec( $sql );

    if(!$ids) return;

    //update the order
    $ids = explode(',',$ids);
    for($i=0;$i<count($ids);$i++)
    {
        $this->dba->exec("UPDATE ".$table_name." SET position=".$i." WHERE id=".$ids[$i]);
    }
  }
  function remove($id)
  {
       $this->dba->exec("DELETE FROM ".$this->table." WHERE id=$id");
  }
  function frontPageAuthor($id)
  {
    $sql = "UPDATE 
                ". $this->table ." 
            SET 
                editor_id = '". $id."' 
            WHERE id=0";
    $this->dba->exec( $sql );
  }

  function save($id)
  {
    $sql = "UPDATE 
                ". $this->table ." 
            SET 
                name= '". addslashes( $_REQUEST['name'] ) ."', 
                icon= '". addslashes( $_REQUEST['icon'] ) ."', 
                resume = '". $_REQUEST['resume'] ."',
                editor_id = '". $_REQUEST['author_id']."',
                creator_name = '". addslashes( $_REQUEST['creator_name'] ) ."', 
                creator_email = '". addslashes( $_REQUEST['creator_email'] ) ."' 
            WHERE id=". $id;
    $this->dba->exec( $sql );

    $this->saveList($id,$this->page_table,'sider');
    $this->saveList($id,$this->kilde_table,'kilder');
    $this->saveList($id,$this->p.'tema_bygning','bygningsdel');

    return $id;
  } 
  function getMax()
  {
    $max = $this->dba->singleQuery("SELECT MAX( position ) FROM ". $this->table);
    if(!is_numeric($max)) $max = 0;
    $max++;
    return $max;
  }
  function isNormalUser()
  {
    if($_SESSION['bruger_id']) 
    {
        if(!$_SESSION['temaeditor'])
        {
            $bruger = new bruger( new dba() );
            $bruger->setId( $_SESSION['bruger_id'] );
            $props = $bruger->loadBruger();
            $_SESSION['temaeditor'] = $props['temaeditor'];
        }
        return $_SESSION['temaeditor'] == 'n';
    }
    return false;
  }
  function create($name='Ny Tema')
  {
    $pox = $this->getMax();
    $sql = "INSERT INTO ".$this->table." (name,position) VALUES ('". addslashes($name)."',".$pox.")";

    //check if this is normal user
    if($_SESSION['bruger_id']) 
    {
        $bruger = new bruger( new dba() );
        $bruger->setId( $_SESSION['bruger_id'] );
        $props = $bruger->loadBruger();
        $priv= 'y';
        if($props['temaeditor'] == 'y') $priv= 'n';
        
        $sql = "INSERT INTO ".$this->table." 
                    (name,private,creator,position) 
                VALUES ('". addslashes($name)."','".$priv."',".$_SESSION['bruger_id'].",".$pox.")";
    }

    $this->dba->exec( $sql );
    return $this->dba->last_inserted_id();
  }
  function isTemaEditor()
  {
    if(!$_SESSION['bruger_id']) return false;

    $bruger = new bruger( new dba() );
    $bruger->setId( $_SESSION['bruger_id'] );
    $props = $bruger->loadBruger();
    return ($props['temaeditor'] == 'y');
  }
  function isEditor() 
  { 
    return ($_SESSION['admin_id'] || $this->isTemaEditor()); 
  }
   
  function editable($name)
  {
    if(!$this->isEditor()) return;
    return '<a href="javascript:editing(\''.$name.'\')" class="editing"><img align="absmiddle" border="0"  src="tema/graphics/edit.gif"> Rediger "'.$name.'"</a>';
  }
  function body($name)
  {
    $str= $this->editable($name);
    $para = $this->getByName($name);
    if($para) $str.= '<div id="para">'.$para['body'].'</div>';
    return $str;
  }
   
}
?>
