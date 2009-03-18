 function getItems( destList )
 {
    var termId = '';

    for( len = 0; len < destList.options.length; len++ ) 
    {
        if ( destList.options[ len ] != null )
        {
            if( termId !='' ) termId +=',';
            termId += destList.options[len].value;
        }
    }
    return termId;
 }

 function addItem( srcList, destList )
{
    //nothing is selected
    if ( ( srcList.selectedIndex == -1 ) ) return;

    var newDestList = new Array( destList.options.length );
    var len = 0;

    //read the current destination list on an option array
    for( len = 0; len < destList.options.length; len++ ) 
    {
        if ( destList.options[ len ] != null )
        {
            newDestList[ len ] = new Option( destList.options[ len ].text, destList.options[ len ].value, destList.options[ len ].defaultSelected, destList.options[ len ].selected );
        }
    }

    //read the source list and incorporate the selected items into the new array
    for( var i = 0; i < srcList.options.length; i++ ) 
    { 
        if ( srcList.options[i] != null && ( srcList.options[i].selected == true ) )
        {
            if( isNotThere( destList, srcList.options[i].value ) )
            {
                newDestList[ len ] = new Option( srcList.options[i].text, srcList.options[i].value, srcList.options[i].defaultSelected, srcList.options[i].selected );
                len++;
            }
        }
    }
    // Populate the destination with the items from the new array
    for ( var j = 0; j < newDestList.length; j++ ) 
    {
        if ( newDestList[ j ] != null )
        {
            destList.options[ j ] = newDestList[ j ];
        }
    }
}
function removeItem( srcList )
{
    // Remove selected elements from the list
    for( var i = srcList.options.length - 1; i >= 0; i-- ) 
    { 
        if ( srcList.options[i] != null && ( srcList.options[i].selected == true ) )
        {
            srcList.options[i]       = null;
        }
    }
}
function isNotThere( destList, itemValue )
{
    var len = 0;

    for( len = 0; len < destList.options.length; len++ ) 
    {
        if ( destList.options[ len ] != null )
        {
            if( itemValue == destList.options[ len ].value ) return false;
        }
    }
    return true;
}

