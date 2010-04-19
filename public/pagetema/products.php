<?
require_once("admin/util/products.php");
require_once("admin/util/rest_client.php");

class Produkter extends View 
{
  var $menu;
  var $dba;
  var $products;
  var $producer;
  var $alfabet;
  var $isVareGroupALeaf;
  var $title;

  function rightmenu()
  {
    // this used to contain google ads
  }

  function Produkter()
  {
    $this->pub = new publication();
    $this->action='products';
    $this->headLine = '';
    $this->head= 'Produkter';
    $this->dba = new dba();
    $this->branche = ($_SESSION['branche'])? $_SESSION['branche']:'general';
    $this->page = 'index.php?action=';

    $this->menu = array(
                        'varegrupper'=>'Byggevarer',
                        'produkter'=>'Produkter',
                        'producenter'=>'Producenter'
                        //'anvisninger'=>'Byggeteknisk vejledning'
                       );
    // $this->products = new products( $this->dba );
    $this->products = new BuildInRESTClient();
    $this->loadAlphabet();
    $this->loadPage();
  }
  function loadAlphabet()
  {
    $alfa = range('A','Z');
    $alfa[count($alfa)] = '&AElig;';
    $alfa[count($alfa)] = '&Oslash;';
    $alfa[count($alfa)] = '&Aring;';
    $alfa[count($alfa)] = '0-9';
    
    $this->alfabet = Array();
    for($i=0;$i<count($alfa);$i++) $this->alfabet[$alfa[$i]] = array();
  }
  function renderAlphabet($mode = 'split_pages')
  {
    $a = '<table width="90%" id="alfabet" border="0" cellpadding="3" cellspacing="0"><tr>';
    if($mode == 'split_pages')
    {
      foreach($this->alfabet as $key=>$value) $a.='<td><a href="/'.$this->page.$this->action.'&section='.
          $this->getCurrentItem().'&l='.$key.'">'.$key.'</a></td>';
    }
    else
    {
      foreach($this->alfabet as $key=>$value){ if($value) $a.='<td><a href="#'.$key.'">'.$key.'</a></td>';}
    }
    return $a .'</tr></table>'; 
  }
  function loadPage()
  {
    if($_REQUEST['section']=='varegrupper')
    {
      $this->head= $this->menu['varegrupper'];
      if($_REQUEST['id']) 
      {
        $this->isVareGroupALeaf = $this->products->isVareGroupALeaf($_REQUEST['id']);
        $this->title= $this->convertCase($this->products->getVaregruppeName($_REQUEST['id']));
        if($this->isVareGroupALeaf) return;
      }
    }
    if($_REQUEST['section']=='producenter')
    {
      $this->head= $this->menu[$_REQUEST['section']];
      $this->title= 'A';
      if($_REQUEST['l'])
      {
        $this->title= $_REQUEST['l'];
      }
      if($_REQUEST['id']) 
      {
        $this->producer = $this->products->loadProducer($_REQUEST['id']);
        $this->title= $this->producer['name'];
        return;
      }
    }
    if($_REQUEST['section']=='produkter')
    {
      $this->head= $this->menu[$_REQUEST['section']];
      $this->title= 'A';
      if($_REQUEST['l'])
      {
        $this->title= $_REQUEST['l'];
      }
      if($_REQUEST['id']) 
      {
        $this->producer = $this->products->loadProducerByProductId($_REQUEST['id']);
        $this->title= $this->producer['name'];
        return;
      }
    }
    if($_REQUEST['section']=='anvisninger')
    {
      $this->head= $this->menu[$_REQUEST['section']];
      return;
    }
    if(!$_REQUEST['section']) $this->head = $this->menu['varegrupper'];
  }
  function isURL($str)
  {
    if(!trim($str)) return false;
    if(substr($str,3) =='www') return 'http://'.$str;
    return $str;
  }
  function getVareGruppe($varegruppe_id)
  {
    $s = '<h1>'. $this->title .'</h1>';

    $group = $this->products->getProductsForVaregruppeGroupByProducer($varegruppe_id);
    if(!$group) return $s.='<p>Ingen produkter under denne varegruppe</p>';
    $s.='<div id="varer">';  
    foreach($group as $key=>$producenterne)
    {
      $s.= '<div class="category">'.$producenterne['producer_name'].'</div>'; 
      foreach($producenterne['products'] as $p_id=>$products)
      {
        $s.= $this->renderProduct($products);
      }
    }
    return $s .'</div>';
  }
  function getProducentInfo()
  {
    $info = array('adresse'=>'Adresse',
                  'telefon'=>'Telefon',
                  'mail'=>'E-mail',
                  'home_page'=>'Website',
                  'fax'=>'Fax',
                  'CVR'=>'CVR'
                  );
    $str='';
    foreach($info as $key=>$value)
    {
      if(!$this->producer[$key]) continue;
      $str.= '<tr>
                <td valign="top" class="left">'.$value.':</td>
                <td class="right">';

      if($key=='home_page') $str.='<a target="_blank" href="'.$this->isURL($this->producer[$key]).'">';
      if($key=='mail') $str.='<a href="mailto:'. $this->producer[$key].'">';
      $str.= $this->producer[$key];

      if($key=='mail' || $key=='home_page') $str.='</a>'; 
      $str.='</td></tr>';
    }
    
    if($str) $str='<table cellpadding="0" cellspacing="0" border="0" id="poster" width="90%">'.$str.'</table>';
    return $str;
  }
  function renderProduct($product)
  {
    $url = $product['home_page'];
    // $url = $this->isURL($product['home_page']);
    $usage = $this->isURL($product['usage_description']);
    $producer = $product['producer_name'];
    $str = '<div class="catcontent">';
    $str.='<table border="0" cellspacing="0" width="100%" cellpadding="0"><tr><td valign="top">';
    $str.='<p><strong>';

    if($url) $str.='<a href="'.$url.'">';
    $str.= $product['name'];
    if($url) $str.='</a>';  
    $str.='</strong> <em class="generic_name">'.$product['generic_name'].'</em></p>';
    
    if($product['description']) $str.='<p>'. $product['description'] .'</p>';
    if($product['observation']) $str.='<p bgcolor="#ff9900">'. $product['observation'] .'</p>';

    if($usage) $str.= $this->pub->renderLink($usage,'Udf&oslash;relses vejledning','_blank');
    if($producer) $str.= $this->pub->renderLink('?action=products&section=producenter&id='.$product['producer_id'],$producer); 

    $str.='</td><td valign="top" align="right">';

    // if(($product['advertise_deal'] == 'trial' || $product['advertise_deal'] == 'active')
    //     && $product['logo_url'] 
    //     && file_exists(realpath('logo').'/'.$product['logo_url'])  ) 
    if($product['logo_url'])
    {
      $str.='<img src="'.$product['logo_url'].'" align="top" 
            border="0" style="margin-top:10px;margin-bottom:10px"/>';
    }
    $str.='&nbsp;</td></tr></table>';
    $str.='</div>';
    return $str;
  }
  function getProducent($id)
  {
    $s = '<h1>';
    // if(($this->producer['advertise_deal'] == 'trial' || $this->producer['advertise_deal'] == 'active')
    //     && $this->producer['logo_url'] 
    //     && file_exists(realpath('logo').'/'.$this->producer['logo_url'])  ) 
    if($this->producer['logo_url'])
    {
      $s.='<img src="'.$this->producer['logo_url'].'" align="top" 
            border="0" style="float:right;margin-right:20px;margin-top:-20px"/>';
    }
    $s.= $this->producer['name'] .'</h1>';

    $s.='<div id="varer">';  
    if($this->producer['description']) $str = stripslashes($this->producer['description']).'<br><br>';
    $s.= $this->getProducentInfo(); 

    // load the categories and products
    $categories_and_products = $this->products->loadCategoriesAndProducts($id);
    
    $s.= '';
    foreach($categories_and_products as $key=>$value)
    {
      if(!count($value['products'])) continue;
      $catName = $value['name'];
      if($catName =='root') $catName = 'Diverse';
      
      if(!count($value['products'])) continue;
      $s.= '<div class="category">'.$catName.'</div>'; 
    
      for($i=0;$i<count($value['products']);$i++)
      {
        $s.= $this->renderProduct($value['products'][$i]);
      }
    }
    return $s.'</div>';
  }
  function getProduct()
  {
    // $s = '<h1>';
   
    // if(($this->producer['advertise_deal'] == 'trial' || $this->producer['advertise_deal'] == 'active')
    //     && $this->producer['logo_url'] 
    //     && file_exists(realpath('logo').'/'.$this->producer['logo_url'])  ) 
   
    // if($this->producer['logo_url'])
    // {
    //   $s.='<img src="'.$this->producer['logo_url'].'" align="top" 
    //         border="0" style="float:right;margin-right:20px;margin-top:-20px"/>';
    // }
    // $s.= $this->producer['name'] .'</h1>';
    // 
    // $s.='<div id="varer">';  
    // if($this->producer['description']) $str = stripslashes($this->producer['description']).'<br><br>';
    // $s.= $this->getProducentInfo(); 
    // $s.='</div>';
    
    $s = '  <div id="buildin"><div id="buildin-menu"></div><div id="buildin-content"></div><div id="buildin-clear"></div></div>';
    $s.= '  <link href="http://'.$this->products->api_key.'.embed.'.$this->products->host.'/embed.css" media="screen" rel="stylesheet" type="text/css" />'; 
    $s.= '  <script src="http://'.$this->products->api_key.'.embed.'.$this->products->host.'/embed.js" type="text/javascript"></script>';
    
    return $s;
  }
  function producenter()
  {
    if($_REQUEST['id']) return $this->getProducent($_REQUEST['id']); 

    $s = '<h1>Producenter</h1>';
    $s.= $this->renderAlphabet();
    $s.='<div id="varer">';  

    $producentArray = $this->products->loadProducenter($_REQUEST['l']);
    // $productCountForProducent = $this->products->antalProdukterForProducent();

    $initial = $this->translateInitial($_REQUEST['l']);
    for($i=0;$i<count($producentArray);$i++)
    {
      $name = $producentArray[$i]['name'];   
      $initial = $this->translateInitial(strtoupper($name{0}));
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $producentArray[$i];
    }

    foreach($this->alfabet as $key=>$value)
    {
      if(!count($value) && $key != $initial) continue; 

      $s.= '<div class="category">'.$key.'</div><ul>'; 

      if(!count($value)) $s.="<li><p>Ingen producenter fundet</p>"; 

      for($i=0;$i<count($value);$i++)
      {
        // $hasProducts = $productCountForProducent[$value[$i]['id']];
        $hasProducts = $value[$i]['antal_produkter'];

        $s.='<li><a href="/'.$this->page.$this->action.'&section='.
            $this->getCurrentItem().'&id='.$value[$i]['id'].'">';
        $s.= $value[$i]['name']; 
        $s.='</a>';

        if($hasProducts)
        {
            $s.='<span style="font-size:9px;font-weight:900">('.
                $value[$i]['antal_produkter'].
               ')</span>';
        }
        $s.='</li>';
      }
      $s.='</ul>';
    }
    return  $s.'</div>';
  }
  function vareGrupper()
  {
    $id = 0;
    $s = '';
    if($_REQUEST['id']) 
    {
      //find out if this is a second level group
      if($this->isVareGroupALeaf) return $this->getVareGruppe($_REQUEST['id']); 
      $id = $_REQUEST['id'];
    }

    $varegrupper = $this->products->loadVaregrupper($id);
    // $productCountForVareGroup = $this->products->antalProdukterForGruppe();

    $initial = $this->translateInitial($_REQUEST['l']);
    for($i=0;$i<count($varegrupper);$i++)
    {
      $name = $varegrupper[$i]['name'];   
      $initial = $this->translateInitial(strtoupper($name{0}));
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $varegrupper[$i];
    }
    
    $s = '<h1>Byggevarer</h1>';
    $s.= $this->renderAlphabet('one_page');
    $s.='<div id="varer">';  
    foreach($this->alfabet as $key=>$value)
    {
      if(!count($value) && $key != $initial) continue;

      $s.= '<a name="'.$key.'" /><div class="category"><a class="tiltop" href="javascript:toppen()">til top &uarr; </a> '.$key.'</div><ul>'; 

      if(!count($value)) $s.="<p>Ingen varegrupper fundet</p>"; 

      for($i=0;$i<count($value);$i++)
      {
        $hasProducts = $value[$i]['antal_produkter'];

        $s.='<li><a href="/'.$this->page.$this->action.'&section='.
            $this->getCurrentItem().'&id='.$value[$i]['id'].'">';
        $s.= $this->convertCase($value[$i]['name']); 
        $s.='</a>';

        if($hasProducts)
        {
            $s.=' <span>['.  $value[$i]['antal_produkter'] . ']</span>';
        }
        $s.='</li>';

      }
      $s.='</ul>';
    }
    return $s.'</div>';


  }
  function producter()
  {
    if($_REQUEST['buildin']) return $this->getProduct(); 

    $produkter = $this->products->loadProducts($_REQUEST['l']);
    $initial = $this->translateInitial($_REQUEST['l']);
    for($i=0;$i<count($produkter);$i++)
    {
      $name = $produkter[$i]['name'];
      $initial = $this->translateInitial(strtoupper($name{0}));
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $produkter[$i];
    }

    $s = '<h1>Produkter</h1>';
    $s.= $this->renderAlphabet();
    $s.='<div id="varer" style="width:590px">';  
    foreach($this->alfabet as $key=>$value)
    {
      if(!count($value) && $key != $initial) continue; 
      $s.= '<div class="category">'.$key.'</div>'; 

      if(!count($value)) $s.="<p>Ingen produkter fundet</p>"; 

      for($i=0;$i<count($value);$i++)
      {
        $s.= $this->renderProduct($value[$i]);
      }
    }
    return $s.'</div>';
   
  }

  function anvisninger()
  {
    $anvisninger = $this->products->getAnvisningerGroupByProducenter();
    $s = '<h1>Byggeteknisk vejledninger</h1>';
    $s.= '<table id="poster" border="0" cellspacing="1" cellpadding="0">';

    foreach($anvisninger as $key=>$value)
    {
      $cat = false; 

      foreach($value as $k=>$v)
      {
        if(!$cat)
        {
          $s.= '<tr><td colspan="2" class="category" align="right">'. $v['kilde_leverandor_name'].'</td></tr>';

          //$s.= '<tr><td colspan="2" class="category" align="right"><a href="javascript:toggling(\''.$v['kilde_leverandor_id'].'\')">';
          //$s.= $v['kilde_leverandor_name'].'<!--<img src="tema/graphics/arrowdown.gif" align="absmiddle" border="0" /></a></td></tr>';
          $cat = true;
        }

        unset($v['kilde_leverandor_id']);
        $s.= $this->pub->renderPublication($v);
      }
    }
    $s.= '</table>';
    return $s;
  }

  function anvisninger_by_alphabet()
  {
    //pull first level
    $anvisninger = $this->products->getAnvisninger();

    for($i=0;$i<count($anvisninger);$i++)
    {
      $name = $anvisninger[$i]['title'];   
      $initial = translateInitial(strtoupper($name{0}));
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $anvisninger[$i];
    }
    foreach($this->alfabet as $key=>$value)
    {
      $s.= $this->pub->renderCategory($key,$key);

      for($i=0;$i<count($value);$i++)
      {
        //render videns leverandor
        $s.= $this->pub->renderPublication($value[$i]);
      }

      $s.= '</div></div>';
    }
    return $s;
  }
  function convertCase($str)
  {
    $str = str_replace('&AElig;','&aelig;',$str);
    $str = str_replace('&Oslash;','&oslash;',$str);
    $str = str_replace('&Aring;','&Aring;',$str);
    $str = str_replace(' & ',' og ',$str);
    $str = strtolower($str);
    return $str;
  }
  function Content()
  {
    $meat = '';
    $ci = $this->getCurrentItem();
    if( $ci == 'varegrupper') $meat = $this->vareGrupper();
    if( $ci == 'producenter') $meat = $this->producenter();

    if( $ci == 'produkter') $meat = $this->producter();
    if($ci== 'anvisninger') $meat = $this->anvisninger();

    return '
            <style>#left_content {margin:0;padding:0 }
                    .page_list { margin:20px; }
                    .page_list li { margin-bottom:10px; }
            </style>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="top" width="200">
                        '.$this->LeftMenu().'
                    </td>
                    <td valign="top" >
                        '.$meat.'
                    </td>
               </tr>
            </table>
            ';
  }
  function getCurrentItem()
  {
    if($_REQUEST['section']) return $_REQUEST['section'];
    return 'varegrupper';
  }
  function LeftMenu()
  {
    $str = '<div id="left_menu">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td><img src="graphics/transp.gif" width="10" height="300" /></td>
                        <td valign="top">
                            <ul id="tema_menu">';
    foreach($this->menu as $key=>$value)
    {
      $sel = ($key == $this->getCurrentItem())?'class="selected"':'';
      $str.= '<li><a href="/'.$this->page.$this->action.'&section='.$key.'" '.$sel.'>'. $value .'</a></li>';
    }
    $str.='</ul></td></tr></table></div>';
    return $str;
  }
  function translateInitial($initial)
  {
    if(preg_match("/[0-9]/",$initial)) return "0-9";
    if(preg_match("/([åÅ]|\\305)/",$initial)) return "&Aring;";
    if(preg_match("/([øØ]|\\330)/",$initial)) return "&Oslash;";
    if(preg_match("/([æÆ]|\\306)/",$initial)) return "&AElig;";
    return $initial;
  }
}
?>
