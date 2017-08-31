<?php
class Products
{	
	var $itemsperpage = 3;
	
	public function __construct()
	{
	}
	public function drowProducts($pid,$page)
    {
    	Global $db;
    	$db->query("SELECT * FROM productsentries WHERE parent_id='".$pid."'");
    	$total = $db->num_rows;
		if($total==0)
			return 'В данной категории пока товаров нет!';
		$i=0;
		$list = '<ul>';
		$results = $db->query("SELECT * FROM productsentries WHERE parent_id='".$pid."' LIMIT ".(($page)*$this->itemsperpage).",".$this->itemsperpage."");
		while ($row = $db->fetch_array($results))
		{
			$href = 'index.php?p=products&amp;pid='.$row['parent_id'].'&amp;id='.$row['id'].'&amp;d='.$page;
			$image = (empty($row['img']) || !file_exists('data/'.$row['img']))?'':'<a href="'.$href.'"><img src="thumbnail.php?thumb='.$row['img'].'&amp;Thumbheight=218" alt="" /></a>';
					$list.= '
					 <li>
				    	'.$image.'
				    	<div class="product-info">
				    		<h3><a href="'.$href.'">'.$row['title'].'</a></h3>
				    		<div class="product-desc">
				    			<p>'.$row['short_description'].'</p>
				    		</div>
				    	</div>
			    	</li>
					';
		}
		$list .= '</ul>
		';
		if ($total>$this->itemsperpage)
		{
			require_once('system/classes/Pagination.class.php');
			$Pager = new pagination();
			$outPager = $Pager->drowPager($total, $this->itemsperpage,$page ,'index.php?p=products&amp;pid='.$pid.'','d');
		}
      return $list.$outPager;
    }
	public function drowCategories($actCat)
    {
     	global $db;
    	$results = $db->query("SELECT * FROM products");
    	$total = $db->num_rows;
		if($total==0)
			return;
		$i=0;
		while ($row = $db->fetch_array($results))
		{
		
		  	$LastClass = ($total == $i)?'class="last"':'';
		  	$actClass = ($row['id'] == $actCat)?'class="act"':'';
		   	$list.= '<li '.$LastClass.'> <a '.$actClass.' href="index.php?p=products&amp;pid='.$row['id'].'">'.$row['title'].'</a></li>';
		   		$i++;
		}
		return '
      		<div class="box categories">
				<h2>Категории <span></span></h2>
				<div class="box-content">
					<ul>
					   '.$list.'
					</ul>
				</div>
			</div>
      ';
		
      
    }
 	function drowProductMore($id,$page)
    {
     	Global $db;
		$results = $db->query("SELECT * FROM productsentries WHERE id='".$id."' ");
		$row = $db->fetch_array($results);
		$href = 'index.php?p=products&amp;pid='.$row['parent_id'].'&amp;d='.$page;
		$image = (empty($row['img']) || !file_exists('data/'.$row['img']))?'':'<a href="data/'.$row['img'].'" class="fancybox"><img id="tozoom" src="thumbnail.php?thumb='.$row['img'].'&amp;Thumbwidth=300" alt="" /></a>';
		$out.= '
		
		<div style="padtop20">
			<div class="left moreimage">
		    	'.$image.'
    		</div>
    		<div class="left moredesc">
    			<h2>'.$row['title'].'</h2>
    			<p>'.$row['short_description'].'</p><br>
    			<p>'.$row['description'].'</p>
    			<a href="'.$href.'" >Назад</a>
    		</div>
		</div>
		';
      return $out;
    }

}

?>
