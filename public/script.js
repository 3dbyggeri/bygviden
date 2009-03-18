function controlCheckbox(group,checkbox)
{
  if(!checkbox.checked) return;

  el = document.forms['myform'].elements;
  for(i=0;i<el.length;i++)
  {
    if(el[i].name != group+'[]') continue;

    if(group=='fag' && el[i].value == 'general') el[i].checked= false;
    if(group=='db' && el[i].value == 'alle') el[i].checked= false;
  }
}
function toggleCategory(category_name)
{
  var cat = document.getElementById('container_'+ category_name); 
  var img = document.getElementById('img_'+ category_name); 

  if (cat.style.display=='none')
  {
    cat.style.display = 'block'; 
    img.src = 'graphics/minus.gif';
  }
  else
  {
    cat.style.display = 'none';
    img.src = 'graphics/plus.gif';
  }
}
function toggleElement(el) 
{
	if ( el.style.display != 'none' ) 
	{
		el.style.display = 'none';
	}
	else 
	{
		el.style.display = '';
	}
}
function getElementsByClass(searchClass,node,tag) 
{
	var classElements = new Array();
	if ( node == null ) node = document;
	if ( tag == null ) tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp('(^|\\s)'+searchClass+'(\\s|$)');
	for (i = 0, j = 0; i < elsLen; i++) 
	{
		if ( pattern.test(els[i].className) ) 
		{
			classElements[j] = els[i];
			j++;
		}
	}
       	return classElements;
}
function displaydoc( id, betaling, url )
{
  if( betaling == -1 )
  {
    alert('Publikationen er ikke tilgængelig i digitalt udgave');
    return;
  }

  url = ( url )? url:'';
  w = window.open('view/document.php?pub='+ id +'&uri='+ url,'Publication');
  w.focus();
}
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

function login_form()
{
    var el = document.getElementById('log_in_form');
    var e = document.getElementById('user_name');
    var a = document.getElementById('log_in');

    if(el.style.display != 'block')
    {
        el.style.display='block';
        e.select();
        e.focus();
        a.style.backgroundColor='#A5A5A5';
    }
    else
    {
        el.style.display='none';
        a.style.backgroundColor='#D9D9D9';
    }
}
