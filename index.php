<?php
/* utf8-marker = äöüß */

if(!defined('PLUGINLOADER_VERSION')){die('Plugin '. basename(dirname(__FILE__)) . ' requires a newer version of the Pluginloader. No direct access.');} 

$pd_router -> add_interest('unified_name');

$pd_router -> add_tab('Translation', $pth['folder']['plugins'].'multilang/multilang_view.php');

// link only to existing pages in other languages (needs "unified_name" page property) - by jzedlitz 2011-01-08
function languagemenu2() {
	global $pth, $cf, $sl, $s ,$u,$pd_current;
	$t = '';
	$r = array();
	$fd = @opendir($pth['folder']['base']);
	while (($p = @readdir($fd)) == true ) {
		if (@is_dir($pth['folder']['base'].$p)) {
			if (preg_match('/^[A-z]{2}$/', $p))$r[] = $p;
		}
	}
	$unified_name = $pd_current['unified_name'];
	if($unified_name == '') return '';

	if ($fd == true)closedir($fd); if(count($r) == 0)return ''; if($cf['language']['default'] != $sl) {
		$t .= languagemenu2_find_page('', $unified_name );
	}
	$v = count($r); 
	for($i = 0;$i < $v;$i++) {
		if ($sl != $r[$i]) {
		    $t .= languagemenu2_find_page($r[$i], $unified_name );
		}
	}

	return ''.$t.'';
}
// END new function languagemenu2() - by jzedlitz 2011-01-08

function languagemenu2_find_page($language,$unified_name) {
    global $pth,$cf;
    
    $t = '';
    
    include($pth['folder']['base'].$language.'/content/pagedata.php');

    foreach( $page_data as $p_id => $page ) {
        if( $page['unified_name'] == $unified_name ) {
            $u = languagemenu2_read_menu($language);

            if( $language == '' ) {
                $url = $pth['folder']['base'].'?'.$u[$p_id];
                $flag = $pth['folder']['flags'].'/'.$cf['language']['default'].'.gif';
            } else {
                $url = $pth['folder']['base'].$language.'/?'.$u[$p_id];
                $flag = $pth['folder']['flags'].'/'.$language.'.gif';
            }
            if (is_file($flag)) {
                $t = '<a href="'.$url.'">'.tag('img src="'.$flag.'" alt="'.$language.'" title="&nbsp;'.$language.'&nbsp;" class="flag"').'</a> ';
            } else {
                $t = '<a href="'.$url.'">['.$language.']</a> ';
            }
        }
    }

    return $t;
}

// This is basicly a copy of the "rfc" function.
function languagemenu2_read_menu($language) {
    global $pth, $cf;
      
    $u = array();
    $l = array();
    $empty = 0;
    $duplicate = 0;

    $content = file_get_contents($pth['folder']['base'].$language.'/content/content.htm');
    $stop = $cf['menu']['levels'];
    $split_token = '#@CMSIMPLE_SPLIT@#';

    $content = preg_split('~</body>~i', $content);
    $content = preg_replace('~<h[1-' . $stop . ']~i', $split_token . '$0', $content[0]);
    $content = explode($split_token, $content);
    array_shift($content);

    foreach ($content as $page) {
        preg_match('~<h([1-' . $stop . ']).*>(.*)</h~isU', $page, $temp);
        $l[] = $temp[1];
        $temp_h[] = trim(strip_tags($temp[2]));
    }

    $ancestors = array(); 
    foreach ($temp_h as $i => $heading) {
        $temp = trim(strip_tags($heading));
        if ($temp == '') {
            $empty++;
            $temp = $tx['toc']['empty'] . ' ' . $empty;
        }
        $ancestors[$l[$i] - 1] = uenc($temp);
        $ancestors = array_slice($ancestors, 0, $l[$i]);
        $url = implode($cf['uri']['seperator'], $ancestors);
        $u[] = substr($url, 0, $cf['uri']['length']);
    }

    return $u;
}

?>
