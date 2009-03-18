/* START OF Functions used by the tree in backend */
function toggling( id )
{
    document.tree.toggle.value = id;
    document.tree.submit();
}
function shownode( id )
{
    obj = document.getElementById( id );
    obj.style.visibility ='visible';
}
function hidenode( id )
{
    obj = document.getElementById( id );
    obj.style.visibility ='hidden';
}
function lightup( name, id )
{
    document[ name + id ].src = 'graphics/'+ name +'_on.gif'; 
}
function grayout( name, id )
{
    document[ name + id ].src = 'graphics/'+ name +'_off.gif'; 
}
function renaming( id )
{
    document.tree.rename.value= id;
    document.tree.submit();
}
function removing( id )
{
    if( confirm('Delete?') )
    {
        document.tree.remove.value= id;
        document.tree.submit();
    }
}
/* END OF Functions used by the tree in backend */

function showSite() // Function used to open the website form backend
{
	if ( screen.width < 1024 )
	{
		screenwidth = screen.width-50;
		screenheight = screen.height-100;
	}
	else
	{
		screenheight = 768;
		screenwidth = 1024;
	}
	var siteWindow = null;
	siteWindow = window.open(  	'../../index.php','thesite','scrollbars=yes,toolbar=yes,status=yes,menubar=yes,location=yes,directories=yes,resizable=yes,width='+screenwidth+',height='+screenheight+'');

  LeftPosition = 5;
  TopPosition = 5;
  siteWindow.moveTo(LeftPosition,TopPosition)
  siteWindow.focus();
}
