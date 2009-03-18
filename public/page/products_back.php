<?
require_once("admin/util/products.php");

class Produkter extends View 
{
  var $menu;
  var $dba;
  var $products;
  var $producer;
  var $alfabet;
  var $isVareGroupALeaf;

  function Produkter()
  {
    $this->pub = new publication();
    $this->action='products';
    $this->headLine = 'Produkter';
    $this->dba = new dba();
    $this->branche = ($_SESSION['branche'])? $_SESSION['branche']:'general';
    $this->page = $_SERVER['PHP_SELF'].'?action=';

    $this->menu = array(
                        'varegrupper'=>'Varegrupper',
                        'producenter'=>'Producenter',
                        'produkter'=>'Produkter',
                        'anvisninger'=>'Byggeteknisk vejledning'
                       );
    $this->products = new products( $this->dba );
    $this->loadPage();
  }
  function getAlphabet()
  {
    $alfa = range('A','Z');
    $alfa[count($alfa)] = '&AElig;';
    $alfa[count($alfa)] = '&Oslash;';
    $alfa[count($alfa)] = '&Aring;';
    

    $this->alfabet = Array();
    for($i=0;$i<count($alfa);$i++) $this->alfabet[$alfa[$i]] = array();
    
    $a = '';
    foreach($this->alfabet as $key=>$value) $a.='<a href="#'.$key.'">'.$key.'</a>&nbsp;&nbsp;';
    return $a; 
  }
  function getFloaterBoxJavascript()
  {
    return '
            <style type="text/css">
            #alfabet{
              font-size: 12px;
              font-weight: bold;
              background-color: #F6F7E7;
              padding:4px;
              position:absolute; visibility:hidden;
              height:auto;
              margin:0; padding:4px;
            }
            </style>
            <script type="text/javascript">
            window.onload = function()
            {
                if (window.winOnLoad) window.winOnLoad();
            }
            </script>
            <script type="text/javascript" src="x_core.js"></script>
            <script type="text/javascript" src="x_slide.js"></script>
            <script type="text/javascript" src="x_event.js"></script>
            <script type="text/javascript">
            var slideTime = 700;
            var topMargin;
            var leftMargin;

            function winOnLoad()
            {
              topMargin = xPageY(\'content\');
              leftMargin = xPageX(\'content\');
              winOnResize(); // set initial position
              xAddEventListener(window, \'resize\', winOnResize, false);
              xAddEventListener(window, \'scroll\', winOnScroll, false);
              return;
            }
            function winOnResize() {
              xMoveTo(\'alfabet\', (leftMargin), topMargin);
              xShow(\'alfabet\');
              winOnScroll(); // initial slide
            }
            function winOnScroll() {
              y =  (xPageY(\'floater\') < xScrollTop() )? xScrollTop():xScrollTop() + topMargin;
              xSlideTo(\'alfabet\', (leftMargin), y, slideTime);
            }
            </script>';
  }
  function loadPage()
  {
    if($_REQUEST['section']=='varegrupper')
    {
      $this->headLine = $this->menu[$_REQUEST['section']];
      if($_REQUEST['id']) 
      {
        $this->headLine = $this->convertCase($this->products->getVaregruppeName($_REQUEST['id']));
        $this->isVareGroupALeaf = $this->products->isVareGroupALeaf($_REQUEST['id']);
        if($this->isVareGroupALeaf) return;
      }
    }
    if($_REQUEST['section']=='producenter')
    {
      $this->headLine = $this->menu[$_REQUEST['section']];
      if($_REQUEST['id']) 
      {
        $this->producer = $this->products->loadProducer($_REQUEST['id']);
        $this->headLine = $this->producer['name'];
        return;
      }
    }
    if($_REQUEST['section']=='anvisninger')
    {
      $this->headLine = $this->menu[$_REQUEST['section']];
    }
    $this->headLine.= '</div><div id="alfabet">'. $this->getAlphabet();
    $this->headLine.= $this->getFloaterBoxJavascript();
  }
  function getVareGruppeIllustration()
  {
    $houses = array();
    $houses['product'] = array(
                            array( 
                                'coords'=>array(
                                    '91,143,176,83,243,83,331,143,320,143,241,91,177,93,96,151',
                                    '214,151,329,150,329,163,211,165',
                                    '90,248,206,248,207,252,90,253'
                                 ),
                                'links'=>array('Lofter'=>154)
                            ),
                            array( 
                                'coords'=>array(
                                    '50,141,209,28,375,135,357,164,243,81,176,82,68,160'
                                 ),
                                'links'=>array('Tag'=>153)
                            ),
                            array( 
                                'coords'=>array(
                                    '74,168,100,168,100,216,74,216',
                                    '73,270,96,270,96,343,73,343',
                                    '90,118,118,94,134,114,104,133'
                                 ),
                                'links'=>array('Vinduer'=>161,'D&oslash;re'=>161)
                            ),
                            array( 
                                'coords'=>array(
                                    '91,235,206,233,206,245,245,91',
                                    '85,339,329,339,329,346,85,346'
                                ),
                                'links'=>array('Gulve'=>162)
                            ),
                            array( 
                                'coords'=>array(
                                    '18,354,74,354,76,431,18,414'
                                ),
                                'links'=>array('Kloak'=>160,'Afl&oslash;b'=>160,'Dr&aelig;n'=>160)
                            ),
                            array( 
                                'coords'=>array(
                                    '205,347,340,347,344,391,323,392,324,356,209,361'
                                ),
                                'links'=>array('Ter&aelig;nd&aelig;k'=>157,'Fundamenter'=>157)
                            ),
                            array( 
                                'coords'=>array(
                                    '165,252,214,251,214,346,162,347'
                                ),
                                'links'=>array('V&aring;drum'=>159)
                            ),
                            array( 
                                'coords'=>array(
                                    '215,241,250,241,293,284,330,284,329,299,295,299,248,344,229,343,275,293,241,255,214,256'
                                ),
                                'links'=>array('Etaged&aelig;k'=>156,'Trapper'=>156)
                            ),
                            array(
                                'coords'=>array(
                                  '79,212,93,212,93,271,81,271',
                                  '328,253,341,252,341,345,328,345'
                                ),
                                'links'=>array('V&aelig;gge'=>155)
                            )
                          );
    $str = '<style>
              .context_menu {
                width:150px;
                font-size: 12px;
                font-weight: bold;
                background-color: #ff9900;
                padding:4px;
                position:absolute; 
                visibility:hidden;
                height:auto;
                margin:0; padding:4px;
                border-right:1px solid #666;
                border-bottom:1px solid #666;
              }
              .context_menu a {
                font-size:10px;
                text-decoration:none;
                }
            </style>
            ';
    $str .= '<map name="varegrupper">';

    $str .= '<script>  
              var IE = document.all?true:false;
              var tempX = 0
              var tempY = 0
              if (!IE) document.captureEvents(Event.MOUSEMOVE)
              document.onmousemove = getMouseXY;
              var hiding = \'\';

              function getMouseXY(e) {
                if (IE) { 
                  tempX = event.clientX + document.body.scrollLeft;
                  tempY = event.clientY + document.body.scrollTop;
                } else {  
                  tempX = e.pageX;
                  tempY = e.pageY;
                }  
                if (tempX < 0) tempX = 0;
                if (tempY < 0) tempY = 0;  
                return true
              }
              function showContext(element_name)
              {
                el = document.getElementById(element_name);
                el.style.visibility = \'visible\'; 
                el.style.top = tempY +\'px\';
                el.style.left = (tempX + 10) +\'px\';
                if( hiding == element_name ) hiding = \'\';
                else hideContext();
              }
              function hidingContext(element_name)
              {
                hiding = element_name
                setTimeout("hideContext()", 1000);
              }
              function hideContext()
              {
                if(!hiding) return;
                document.getElementById(hiding).style.visibility = \'hidden\'; 
                hiding = \'\';
              }
             </script>
              ';
    $c = 0;
    $div = '';
    foreach($houses['product'] as $k=>$v)
    {
      $c++;
      foreach($v['coords'] as $k1=>$v1)
      {
        $str.='<area shape="poly" coords="'.$v1.'" 
                  onmouseover="showContext(\'context_'.$c.'\')"
                  onmouseout="hidingContext(\'context_'.$c.'\')"
                  href="javascript:showContext('.$c.')">
               ';
      }
      $div.= "\n".'<div id="context_'. $c .'" class="context_menu">';
      foreach($v['links'] as $k2=>$v2)
      {
        $div.='<a href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id='. $v2.'">';
        $div.= $k2.'</a><br>'."\n";
      }
      $div.='</div>'."\n";
    }
    $str.='</map>';
    $str.='<center>';
    $str.='<img usemap="#varegrupper" width="423" height="450" src="product_images/tegning_internet.jpg" border="0">';
    $str.='</center>';

    $str.= $div;

    return $str;

    return '
            <map name="varegrupper">
              <area shape="rect" coords="3,155,102,172" title="Gulve" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=162">

              <area shape="rect" coords="8,255,110,273" title="Vinduer og d&oslash;re" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=161">

              <area shape="rect" coords="94,354,190,367" title="Kloak" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=160">

              <area shape="rect" coords="173,266,234,283" title="V&aring;drum" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=159">

              <area shape="rect" coords="150,172,486,206" title="Sk&aelig;be k&oslash;kkner" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=160">

              <area shape="rect" coords="360,63,474,82" title="Tag" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=153">

              <area shape="rect" coords="393,147,493,164" title="Lofter" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=154">

              <area shape="rect" coords="403,178,486,209" title="V&aelig;gge" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=155">

              <area shape="rect" coords="393,300,493,317" title="T&aelig;rrand&aelig;ek" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=157">
            </map>
            <img usemap="#varegrupper" src="graphics/tegning_varegruppe.jpg" border="0" width="500" height="420">';
  }
  function isURL($str)
  {
    if(!trim($str)) return false;
    if(substr($str,3) =='www') return 'http://'.$str;
    return $str;
  }
  function getVareGruppe($varegruppe_id)
  {
    $group = $this->products->getProductsForVaregruppeGroupByProducer($varegruppe_id);
    if(!$group) return 'Ingen produkter under denne varegruppe';
    $p = ''; 
    foreach($group as $key=>$producenterne)
    {

      $p.= $this->pub->renderCategory($producenterne['producer_id'],$producenterne['producer_name'],'close');
      foreach($producenterne['products'] as $p_id=>$products)
      {
        $p.= $this->renderProduct($products);
      }
      $p.='</div></div>';
    }
    return $p;
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
                <td valign="top">'.$value.':</td>
                <td>';

      if($key=='home_page') $str.='<a target="_blank" href="'.$this->isURL($this->producer[$key]).'">';
      if($key=='mail') $str.='<a href="mailto:'. $this->producer[$key].'">';
      $str.= $this->producer[$key];

      if($key=='mail' || $key=='home_page') $str.='</a>'; 
      $str.='</td></tr>';
    }
    
    if($str) $str='<table cellpadding="0" cellspacing="0" border="0" class="formtable">'.$str.'</table>';
    return $str;
  }
  function renderProduct($product)
  {
    $url = $this->isURL($product['home_page']);
    $usage = $this->isURL($product['usage_description']);
    $producer = $product['producer_name'];

    $str = '<div class="catcontent"><p><strong>';
    if($url) $str.='<a href="'.$url.'" target="_blank">';
    $str.= $product['name'];
    if($url) $str.='</a>';  
    $str.='</strong></p>';
    
    if($product['description']) $str.='<p>'. $product['description'] .'</p>';
    if($product['observation']) $str.='<p bgcolor="#ff9900">'. $product['observation'] .'</p>';

    if($usage) $str.= $this->pub->renderLink($usage,'Udf&oslash;relses vejledning','_blank');
    if($producer) $str.= $this->pub->renderLink('index.php?action=products&section=producenter&id='.$product['producer_id'],$producer); 

    $str.='</div>';
    return $str;
  }
  function getProducent($id)
  {
    $str = '';

    if($this->producer['description']) $str = stripslashes($this->producer['description']).'<br><br>';
    $str.= $this->getProducentInfo(); 

    // load the categories and products
    $categories_and_products = $this->products->loadCategoriesAndProducts($id);
    
    $cat = '';
    foreach($categories_and_products as $key=>$value)
    {
      if(!count($value['products'])) continue;
      $catName = $value['name'];
      if($catName =='root') $catName = 'Diverse';
      
      $cat .= $this->pub->renderCategory($key,$catName,'close');

      for($i=0;$i<count($value['products']);$i++)
      {
        $cat.= $this->renderProduct($value['products'][$i]);
      }
      $cat.='</div></div>';
    }
    return $str.'<br>'.$cat;
  }
  function producenter()
  {
    if($_REQUEST['id']) return $this->getProducent($_REQUEST['id']); 

    $producentArray = $this->products->loadProducenter();
    $productCountForProducent = $this->products->antalProdukterForProducent();

    for($i=0;$i<count($producentArray);$i++)
    {
      $name = $producentArray[$i]['name'];   
      $initial = strtoupper($name{0});
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $producentArray[$i];
    }

    foreach($this->alfabet as $key=>$value)
    {
      $s.= $this->pub->renderCategory($key,$key);
      for($i=0;$i<count($value);$i++)
      {
        $hasProducts = $productCountForProducent[$value[$i]['id']];

        $s.='<a href="'.$this->page.$this->action.'&section='.
            $this->getCurrentItem().'&id='.$value[$i]['id'].'">';
        $s.= $value[$i]['name']; 
        $s.='</a>';

        if($hasProducts)
        {
            $s.='<span style="font-size:9px;font-weight:900">('.
                $productCountForProducent[ $value[$i]['id'] ].
               ')</span>';
        }

        $s.='<br>';
      }

      $s.= '</div></div>';
    }
    return '<br>'.$s;
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
    $productCountForVareGroup = $this->products->antalProdukterForGruppe();

    for($i=0;$i<count($varegrupper);$i++)
    {
      $name = $varegrupper[$i]['name'];   
      $initial = strtoupper($name{0});
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $varegrupper[$i];
    }
    
    if(!$_REQUEST['id']) $s = $this->getVareGruppeIllustration();
    foreach($this->alfabet as $key=>$value)
    {
      $s.= $this->pub->renderCategory($key,$key);

      for($i=0;$i<count($value);$i++)
      {
        $hasProducts = $productCountForVareGroup[$value[$i]['id']];

        $s.='<a href="'.$this->page.$this->action.'&section='.
            $this->getCurrentItem().'&id='.$value[$i]['id'].'">';
        $s.= $this->convertCase($value[$i]['name']); 
        $s.='</a>';

        if($hasProducts)
        {
            $s.='<span style="font-size:9px;font-weight:900">('.
                $productCountForVareGroup[ $value[$i]['id'] ].
               ')</span>';
        }

        $s.='<br>';
      }

      $s.= '</div></div>';
    }
    return '<br>'.$s;
  }
  function producter()
  {
    $produkter = $this->products->loadProducts();
    for($i=0;$i<count($produkter);$i++)
    {
      $name = $produkter[$i]['name'];   
      $initial = strtoupper($name{0});
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $produkter[$i];
    }

    $s = '';
    foreach($this->alfabet as $key=>$value)
    {
      $s.= $this->pub->renderCategory($key,$key);

      for($i=0;$i<count($value);$i++)
      {
        $s.= $this->renderProduct($value[$i]);
      }

      $s.= '</div></div>';
    }
    return $s;
  }

  function anvisninger()
  {
    //pull first level
    $anvisninger = $this->products->getAnvisninger();

    for($i=0;$i<count($anvisninger);$i++)
    {
      $name = $anvisninger[$i]['title'];   
      $initial = strtoupper($name{0});
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
    switch($this->getCurrentItem())
    {
      case('varegrupper'): return $this->vareGrupper();
      case('producenter'): return $this->producenter();
      case('produkter'): return $this->producter();
      case('anvisninger'): return $this->anvisninger();
    }
  }
  function getCurrentItem()
  {
    if($_REQUEST['section']) return $_REQUEST['section'];
    return 'varegrupper';
  }
  function LeftMenu()
  {
    $str = '<div id="navitree">';
    foreach($this->menu as $key=>$value)
    {
      $str.= '<div class="node">';
      if($key == $this->getCurrentItem()) $str.= '<strong>';
      
      $str.= '<a href="'.$this->page.$this->action.'&section='.$key.'">'. $value .'</a>';
      if($key == $this->getCurrentItem() ) $str.= '</strong>';

      $str.= '</div>';
    }
    $str.='</div>';
    return $str;
  }
}
?>
