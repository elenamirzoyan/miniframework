<!-- Main -->
<?php
include('system/classes/Products.class.php');
$d = $_GET['d'];
$d = (empty($d )|| $d<0)?0:$d;
$id =  $_GET['id'];
$pid =  $_GET['pid'];
$pid =  empty($pid)?1:$pid;

$prod = new Products();
if (!empty($id))
{
	$content = $prod->drowProductMore($id,$d);
}
else if(empty($id) && !empty($pid))
{
	
	$content = $prod->drowProducts($pid,$d); //(parent_id, curent_page)
}
 
?>
	<div id="main">
		<div class="cl">&nbsp;</div>
		
		<!-- Content -->
		<div id="content">
			
			
			
			<!-- Products -->
			<div class="products">
				<div class="cl">&nbsp;</div>
				<?php echo $content;?>
				   
			    	
				<div class="cl">&nbsp;</div>
			</div>
			<!-- End Products -->
			
		</div>
		<!-- End Content -->
		
		<!-- Sidebar -->
		<div id="sidebar">
			
			
			
			<!-- Categories -->
			<?php echo $prod->drowCategories($pid);?>
			<!-- End Categories -->
			
		</div>
		<!-- End Sidebar -->
		
		<div class="cl">&nbsp;</div>
	</div>
	<!-- End Main -->