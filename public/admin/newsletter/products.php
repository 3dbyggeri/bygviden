<?
$_SESSION['offset'] = is_numeric( $_GET['offset'] )?  $_GET['offset']: ( ( $_SESSION['offset'] )? $_SESSION['offset']:0 );
$_SESSION['row_number'] = $_GET['row_number']?  $_GET['row_number']: ( $_SESSION['row_number']? $_SESSION['row_number']:10 );
$_SESSION['prod_sorting_order'] =   $_GET['prod_sorting_order']?  $_GET['prod_sorting_order']: ( $_SESSION['prod_sorting_order']? $_SESSION['prod_sorting_order']:'desc' );
$_SESSION['prod_sorting_column'] =   $_GET['prod_sorting_column']?  $_GET['prod_sorting_column']: ( $_SESSION['prod_sorting_column']? $_SESSION['prod_sorting_column']:'created' );

$products = $advertiser->products($edit);
?>
