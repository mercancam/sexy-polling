<div class="purchase_block">
	<div class="purchase_block_txt">Get Sexy Polling Pro and gain access to unlimited polls, unlimited answers, no powered by text, more features and professional support.</div>
    <a href="http://2glux.com/projects/sexypolling" id="wpsxp_buy_pro" target="_blank">Get Sexy Polling PRO</a>
    <div class="pro_wrapper">
		<a href="http://2glux.com"  target="_blank" title="2GLux" >
			<img src="<?php echo plugins_url( '../images/gspeech_txt.png' , __FILE__ );?>" class="pro_img" />
		</a>
	</div>
</div>
<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'sexypolling';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : 0;
//get the active text
switch ($page) {
	case 'sexypolling':
		$active_text = 'Overview';
		break;
	case 'sexypolls':
		$active_text = $act == '' ? 'Polls' : ($act == 'new' ? 'Polls : New' : 'Polls : Edit');
		break;
	case 'sexyanswers':
		$active_text = $act == '' ? 'Answers' : ($act == 'new' ? 'Answers : New' : 'Answers : Edit');
		break;
	case 'sexycategories':
		$active_text = $act == '' ? 'Categories' : ($act == 'new' ? 'Categories : New' : 'Categories : Edit');
		break;
	case 'sexypollingtemplates':
		$active_text = 'Templates';
		break;
	case 'sexystatistics':
		$active_text = 'Statistics';
		break;
}
?>
    <div id="wpsxp_logo" class="icon32"></div>
    <h2>Sexy Polling : <?php echo $active_text;?></h2>
    <p></p>
    <div id="wpsxp-toolbar">
        <ul id="wpsxp-toolbar-links">
	        <li><div class="wpsxp-toolbar-link-bg" id="wpsxp-toolbar-link-overview<?php echo $page == 'sexypolling' ? '_active' : '';?>" style="margin-left: 5px;"></div><a class="<?php echo $page == 'sexypolling' ? 'wpsxp-toolbar-active' : '';?>" href="admin.php?page=sexypolling">Overview</a></li>
	        <li><div class="wpsxp-toolbar-link-bg" id="wpsxp-toolbar-link-polls<?php echo $page == 'sexypolls' ? '_active' : '';?>"></div><a class="<?php echo $page == 'sexypolls' ? 'wpsxp-toolbar-active' : '';?>" href="admin.php?page=sexypolls">Polls</a></li>
	        <li><div class="wpsxp-toolbar-link-bg" id="wpsxp-toolbar-link-answers<?php echo $page == 'sexyanswers' ? '_active' : '';?>"></div><a class="<?php echo $page == 'sexyanswers' ? 'wpsxp-toolbar-active' : '';?>" href="admin.php?page=sexyanswers">Answers</a></li>
	        <li><div class="wpsxp-toolbar-link-bg" id="wpsxp-toolbar-link-categories<?php echo $page == 'sexycategories' ? '_active' : '';?>"></div><a class="<?php echo $page == 'sexycategories' ? 'wpsxp-toolbar-active' : '';?>" href="admin.php?page=sexycategories">Categories</a></li>
	        <li><div class="wpsxp-toolbar-link-bg" id="wpsxp-toolbar-link-templates<?php echo $page == 'sexypollingtemplates' ? '_active' : '';?>"></div><a class="<?php echo $page == 'sexypollingtemplates' ? 'wpsxp-toolbar-active' : '';?>" href="admin.php?page=sexypollingtemplates">Templates</a></li>
	        <li><div class="wpsxp-toolbar-link-bg" id="wpsxp-toolbar-link-statistics<?php echo $page == 'sexystatistics' ? '_active' : '';?>"></div><a class="<?php echo $page == 'sexystatistics' ? 'wpsxp-toolbar-active' : '';?>" href="admin.php?page=sexystatistics">Statistics</a></li>
        </ul>
    </div>
    <div style="clear:both;"></div>