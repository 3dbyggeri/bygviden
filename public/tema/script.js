function size() 
{
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  return new Array( myWidth,myHeight );
}
function resizing()
{
    var header_height = document.getElementById('header').offsetHeight;
    var footer_height = document.getElementById('footer').offsetHeight;
    var total_height = size()[1];
    
    var h = total_height - (header_height + footer_height);
    document.getElementById('content').style.height = h +'px'; 
    var c = document.location.href.split('#');
    if(c.length != 2) return;
    document.location.href = '#'+ c[1];

}
function editTemaMenu()
{
   GB_show('Redigere Tema Menu', SERVER_NAME +'/pagetema/editTemaMenu.php',700,600);
}
function editing(name)
{
   GB_show('Rediger '+ name, SERVER_NAME +'/pagetema/edit.php?n='+ name,700,600);
}
function opretTema()
{
   var params = escape(SERVER_NAME +'/'+ SCRIPT_NAME +'?action=tema&tema=-1');
   var msg = escape('For at oprette et tema skal du bruge dit log in');
   var url = SERVER_NAME +'/pagetema/login.php?msg='+ msg +'&ff='+ params;
   GB_show('Log in',url ,700,600);
}
function updateIconClass(list,element)
{
    var icon = list.options[list.selectedIndex].value;
    if(icon == '-') return;
    document.getElementById(element).className='icon_'+ icon;
}

function addListItem(editor_name,tema_id,w,h)
{
   if(w == undefined) w = 500;
   if(h == undefined) h = 500;
   displayListEditor(editor_name,tema_id,'-1',w,h);
}

function editListItem(editor_name,list_name,tema_id,w,h)
{
   if(w == undefined) w = 500;
   if(h == undefined) h = 500;

   var list = document.getElementById('select_'+ list_name);
   if(!list) return; 

   var id = list.options[list.selectedIndex].value;
   if(isNaN(parseInt(id)))
   {
        alert('Intet valg');
        return;
   } 
   displayListEditor(editor_name,tema_id,id,w,h);
}

function displayListEditor(editor_name,tema_id,item_id,w,h)
{
   if(w == undefined) w = 500;
   if(h == undefined) h = 500;
   var url = SERVER_NAME +'/pagetema/editTema'+ editor_name +'.php?tema_id='+ tema_id +'&id='+ item_id; 
   GB_show('Tema Bygningsdel',url,w,h);
}

function newListItem(list_name,item_id,item_name)
{
    var p = document.getElementById('select_'+ list_name);
    var h = document.getElementById('list_'+ list_name);
    p.options[p.options.length] = new Option(unescape(item_name),item_id); 
    h.value= doubleListBox.values(p);
}

function updateListItem(list_name,item_name)
{
    var p = document.getElementById('select_'+ list_name);
    p.options[p.selectedIndex].text = unescape(item_name);
}
function editTemaBox(nr)
{
   var url = SERVER_NAME +'/pagetema/editTemaBox.php?id='+ nr; 
   GB_show('Tema Box',url,500,600);
}
function editEditor(nr)
{
   if(nr=='0')
   {
     var el =  document.getElementById('editorlist');
     nr = el.options[el.selectedIndex].value;
     if(!nr) return;
   }
   var url = SERVER_NAME +'/pagetema/editEditor.php?id='+ nr; 
   GB_show('Administration',url,380,600);
}
function removingEditor()
{
     var el =  document.getElementById('editorlist');
     if( el.options[el.selectedIndex].value == '-') return;
     el.options[el.selectedIndex] = null; 
     el.selectedIndex = 0; 
     loadeditor();
}
function addEditor(id,name)
{
    var el =  document.getElementById('editorlist');
    var idx = el.options.length;
    el.options[idx] = new Option(name,id);
    el.options.selectedIndex = idx;
}
function removeEditor()
{
     var el =  document.getElementById('editorlist');
     nr = el.options[el.selectedIndex].value;
     if(!nr) return;
     var url = SERVER_NAME +'/pagetema/editEditor.php?remove='+ nr; 
    GB_show('Administration',url,300,300);
}
function loadeditor()
{
     var el =  document.getElementById('editorlist');
     var id= el.options[el.selectedIndex].value;
     if(!id) return;

    document.getElementById("author_id").value = '';
    document.getElementById("author_title").innerHTML = '';
    document.getElementById("author_name").innerHTML = '';
    document.getElementById("author_email").innerHTML = '';
    document.getElementById("author_resume").innerHTML = '';
    
    document.getElementById("author_portrait").src='tema/graphics/transp.gif';
    document.getElementById("author_portrait").width =1;
    document.getElementById("author_portrait").height =1;
     
    if(!editors[id]) return;
    
    document.getElementById("author_id").value = id;
    document.getElementById("author_title").innerHTML = editors[id]['title'];
    document.getElementById("author_name").innerHTML = editors[id]['name'];
    document.getElementById("author_email").innerHTML = editors[id]['email'];
    document.getElementById("author_resume").innerHTML = editors[id]['resume'];
    document.getElementById("author_portrait").src=editors[id]['portrait'];
}
function checksize(img)
{
    if(img.src.indexOf('transp.gif') >0 ) return;
    img.width =93;
    img.height =130;
}
function hidePortrait(img)
{
    img.width = 1;
    img.height = 1;
}
function removetema(id)
{
    if(!confirm('Slet tema '+ id +'?')) return;
    document.location.href='?action=tema&removing='+ id;
}
function toppen()
{
    var objDiv = document.getElementById("content");
    objDiv.scrollTop = '0';
}

function toggleCat(id)
{
    var el = document.getElementById('img_'+id);

    document.getElementById('pubs_'+ id).style.display = (el.src.indexOf('up.gif') > 0 )? 'none':'';
    el.src = (el.src.indexOf('up.gif') > 0 )? 'tema/graphics/arrowdown.gif':'tema/graphics/arrowup.gif';
}
function set_author()
{
    var id = document.getElementById('author_id').value;
    if(!isNaN(parseInt(id))) document.location.href='?action=tema&set_author='+ id;
}

function displaydoc( id, betaling, url )
{
  if( betaling == -1 )
  {
    alert('Publikationen er ikke tilg?ngelig i digitalt udgave');
    return;
  }

  url = ( url )? url:'';
  w = window.open('view/document.php?pub='+ id +'&uri='+ url,'Publication');
  w.focus();
}
