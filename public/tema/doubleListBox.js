var doubleListBox = {};
doubleListBox.values = function(obj)
{
    var values= '';
    for( var i = 0; i < obj.options.length; i++ ) 
    {
        if ( obj.options[i] != null )
        {
            if( values !='' ) values +=',';
            values += obj.options[i].value;
        }
    }
    return values;
}

doubleListBox.deleting = function(obj_id,hiddenField)
{
  var obj = document.getElementById(obj_id);
  var v = obj.options[obj.selectedIndex].text; 
  if(!v) return;
  if(!confirm('Slet "'+ v +'"?')) return; 
  var hiddenField = document.getElementById(hiddenField);
  doubleListBox.remove(obj);
  hiddenField.value= doubleListBox.values(obj);
}
doubleListBox.up = function(obj_id,hiddenField)
{
  var current;
  var current_val;
  var reverse;
  var reverse_val;
  var obj = document.getElementById(obj_id);
  var hiddenField = document.getElementById(hiddenField);

  if(obj.options.selectedIndex==-1) return;
  if(obj.options[obj.options.selectedIndex].index > 0)
  {
    current = obj.options[obj.options.selectedIndex].text;
    current_val = obj.options[obj.options.selectedIndex].value;

    reverse = obj.options[obj.options[obj.options.selectedIndex].index-1].text;
    reverse_val = obj.options[obj.options[obj.options.selectedIndex].index-1].value;

    obj.options[obj.options.selectedIndex].text = reverse;
    obj.options[obj.options.selectedIndex].value = reverse_val;

    obj.options[obj.options[obj.options.selectedIndex].index-1].text = current;
    obj.options[obj.options[obj.options.selectedIndex].index-1].value = current_val;
    self.focus();
    obj.options.selectedIndex--;
  }
  
  hiddenField.value= doubleListBox.values(obj);
}

doubleListBox.down = function(obj_id,hiddenField)
{
  var current;
  var current_val;
  var next;
  var next_val;
  var obj = document.getElementById(obj_id);
  var hiddenField = document.getElementById(hiddenField);

  if(obj.options.selectedIndex==-1) return;
  if(obj.options[obj.options.selectedIndex].index != obj.length-1)
  {
    current = obj.options[obj.options.selectedIndex].text;
    current_val = obj.options[obj.options.selectedIndex].value;

    next = obj.options[obj.options[obj.options.selectedIndex].index+1].text;
    next_val = obj.options[obj.options[obj.options.selectedIndex].index+1].value;

    obj.options[obj.options.selectedIndex].text =  next;
    obj.options[obj.options.selectedIndex].value =  next_val;
    obj.options[obj.options[obj.options.selectedIndex].index+1].text = current;
    obj.options[obj.options[obj.options.selectedIndex].index+1].value = current_val;

    self.focus();
    obj.options.selectedIndex++;
  }
  hiddenField.value= doubleListBox.values(obj);
}

doubleListBox.add = function(srcList,destList)
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
            if( doubleListBox.isNotThere( destList, srcList.options[i].value ) )
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
doubleListBox.remove = function(obj)
{
    for( var i = obj.options.length - 1; i >= 0; i-- ) 
    { 
        if( obj.options[i] != null && ( obj.options[i].selected == true ) )
        {
            obj.options[i] = null;
        }
    }
}
doubleListBox.isNotThere = function( destList, itemValue )
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
doubleListBox.move = function(from_id,to_id,hidden_id,right2left)
{
    var f = document.getElementById(from_id);
    var t = document.getElementById(to_id);
    var h = document.getElementById(hidden_id);
    doubleListBox.add(f,t);
    doubleListBox.remove(f);
    h.value= doubleListBox.values((right2left)? t:f);
}
