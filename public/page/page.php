<?php
class View 
{
  var $action;
  var $currentPage;
  var $leftMenu;
  var $breadCrumb;
  var $headLine;
  var $content;
  var $pub; 

  function CurrentPage()
  {
    return 'index.php?action='.$this->action;
  }
  function LeftMenu()
  {
    return $this->leftMenu;
  }
  function BreadCrumb()
  {
    return $this->breadCrumb;
  }
  function Headline()
  {
    return $this->headLine;
  }
  function Content()
  {
    return $this->content;
  }
}
?>
