<?php
function sed_import($name, $source, $filte)
	{
	switch($source)
		{
		case 'PG':
		$v = $_POST[$name]?$_POST[$name]:$_GET[$name];
		$log = TRUE;
		break;
		
		case 'GP':
		$v = $_GET[$name]?$_GET[$name]:$_POST[$name];
		$log = TRUE;
		break;
		
		case 'G':
		$v = $_GET[$name];
		$log = TRUE;
		break;
		case 'P':
		$v = $_GET[$name];
		$log = TRUE;
		break;
		}
	if ($v=='')
       	{ return(''); }
		
	if ($log == true)
		$v = stripslashes($v);

	
	$pass = FALSE;
	$defret = NULL;
	$filter = ($filter=='STX') ? 'TXT' : $filter;

	switch($filter)
		{
			case 'INT':
			if (is_numeric($v)==TRUE && floor($v)==$v)
		       	{ $pass = TRUE; }
			break;
	
			case 'NUM':
			if (is_numeric($v)==TRUE)
		       	{ $pass = TRUE; }
			break;
		}
	}

function print_pre($a)
{
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}
?>