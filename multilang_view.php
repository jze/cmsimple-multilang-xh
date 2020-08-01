<?php
/* utf8-marker = äöüß */

function multilang_view($page){
	global $plugin_tx, $sn, $su, $pth;
	
	$lang = $plugin_tx['multilang'];
	$help_icon = tag('img src = "'.$pth['folder']['plugins']. 'multilang/css/help_icon.png"');
	
	$view ="\n".'<form action="'.$sn.'?'.$su.'" method="post" id = "multilang" name = "multilang">';	

	$view .= "\n\t".'<p><b>'.$lang['title'].'</b></p>';

############# multilang_value ##################
	$view .= "\n\t".'<a class="pl_tooltip" href="#">'.$help_icon.'<span>'.$lang['hint_unified_name'].'</span></a>';
	$view .= "\n\t".'<span class = "pp_label">'.$lang['cf_unified_name'].'</span>'.tag('br');
	
	$view .= "\n\t\t".tag('input type="text" size="50" name="unified_name" id="other_unified_name" value="'. str_replace('"', '&quot;', $page['unified_name']).'"').tag('br');
	$view .= "\n\t";
################################################
	$view .= tag('br');
	$view .= "\n\t".tag('input name = "save_page_data" type = "hidden"');
	$view .= "\n\t".'<div style="text-align: right">';
	$view .= "\n\t\t".tag('input type="submit" value="Submit"').tag('br');
	$view .= "\n\t".'</div>';
	$view .= "\n".'</form>';
	return $view;
}
?>
