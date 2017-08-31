<?
/*

.pagination {
    clear: both;
    padding: 20px 0 0;
    text-align: center;
}
.pagination a {
    background: none repeat scroll 0 0 #E8E8E8;
    border-radius: 3px 3px 3px 3px;
    color: #003143;
    display: inline-block;
    font-size: 11px;
    margin-right: 2px;
    padding: 2px 8px;
    text-decoration: none;
}
.pagination a.current, .pagination a:hover {
    background: none repeat scroll 0 0 #5CD100;
    color: #FFFFFF;
}

 */
class pagination
{
	public function __construct()
	{
	}
	public function drowPager($total_rows, $rows_per_page, $page_num,$url,$getname,$blocks = 4)
	{
		$pager = '<div class="pagination">';
	    $pcount = ceil($total_rows/$rows_per_page);
	    $pn=(!empty($page_num))?floor($page_num/$blocks):0;
	    $max=($pn+1)*$blocks;
	    $max = ($max<$pcount)?$max:$pcount;
	    $pager .= (($page_num-$blocks)>-1)?'<a href="'.$url.'&amp;'.$getname.'='.(($page_num-$blocks)-$page_num%$blocks).'"  title="">&lt;&lt;</a> <a href="'.$url.'&amp;'.$getname.'=0">1</a>&nbsp;...&nbsp;':'';
	    for ($i=($max-$blocks<0)?0:$max-$blocks;$i<$max;$i++)
	    {
			if($page_num  == $i)
		  		$pager .= '<a class="current">'.($i+1).'</a>';
		    else 
		   		$pager .= '<a href="'.$url.'&amp;'.$getname.'='.$i.'">'.($i+1).'</a>';
	    }
	    $pager .= ($pcount-$max>0)?'&nbsp;...&nbsp;<a href="'.$url.'&amp;'.$getname.'='.($pcount-1).'">'.$pcount.'</a><a href="'.$url.'&amp;'.$getname.'='.$max.'" title="" >&gt;&gt;</a>':'';
	    $pager .= '</div>';
		
		
		return $pager;
	}
	
}
?>