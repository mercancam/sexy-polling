<div id="wpsxp_content">
	<?php 
		if($page == 'sexypolling')
			include('overview.php');
		elseif($page == 'sexypolls') {
			if($act == '')
				include('polls.php');
			elseif($act == 'new' || $act == 'edit')
				include('poll.php');
		}
		elseif($page == 'sexyanswers') {
			if($act == '')
				include('answers.php');
			elseif($act == 'new' || $act == 'edit')
				include('answer.php');
		}
		elseif($page == 'sexycategories') {
			if($act == '')
				include('categories.php');
			elseif($act == 'new' || $act == 'edit')
				include('category.php');
		}
		elseif($page == 'sexypollingtemplates') {
			if($act == '')
				include('templates.php');
			elseif($act == 'new')
				include('template_add.php');
			elseif($act == 'edit')
				include('template_edit.php');
		}
		elseif($page == 'sexystatistics') {
			if($act == '')
				include('statistics.php');
			else
				include('show_statistics.php');
		}
	?>
</div>