<?php
/*utf-8 check: äöüß */

initvar('multilang');
if ($multilang) {
	// Make CMSimple variables accessible
	global $sn,$sv,$sl,$pth,$cf,$plugin,$plugin_tx;
	
	$txp = $plugin_tx['multilang'];
	
	// Detect the foldername of the plugin.
	$plugin=basename(dirname(__FILE__),"/");

	$admin = isset($_GET['admin']) ? $_GET['admin'] : '';
	$admin .= isset($_POST['admin']) ? $_POST['admin'] : '';
	
	// Parameter "ON"  shows the Plugin Main Tab.
	// Blank "" or "OFF" does not show the Plugin Main Tab.
	$o.=print_plugin_admin('ON');
	
	$o .= tag('br');
	
	// First page when loading the plugin.
	if ($admin == '' || $admin == 'plugin_main') {

	    // create a list of available languages
		$r = array();
        $fd = @opendir($pth['folder']['base']);
        while (($p = @readdir($fd)) == true ) {
            if (@is_dir($pth['folder']['base'].$p)) {
			    if (preg_match('/^[A-z]{2}$/', $p))$r[] = $p;
		    }
    	}
    	
    	$lang_list = $r;
    		
	    // collect unified_names for all languages
        $names = array();
        foreach($lang_list as $lang ) {
            include($pth['folder']['base'].$lang.'/content/pagedata.php');
	        foreach( $page_data as $i => $page ) {
	            $names[$lang][$i] = $page['unified_name'];
	        }
        }
	    
	    // add the default language
	    include($pth['folder']['base'].'/content/pagedata.php');
	    foreach( $page_data as $i => $page ) {
	        $names[$cf['language']['default']][$i] = $page['unified_name'];
	    }
	    array_splice($lang_list, 0, 0, $cf['language']['default']);

	    // reset page_data
	    include('content/pagedata.php');
	    	    
	    //$u_de = languagemenu2_read_menu('de');
	    
		$o.='<table border="1">'.
		'<tr><th>'.$tx['search']['pgsingular'].' '.tag('img src="'.$pth['folder']['flags'].'/'.$sl.'.gif'.'" alt="'.$sl.'" title="&nbsp;'.$sl.'&nbsp;" class="flag"').'</th><th>'.$txp['cf_unified_name'].'</th>';
		
		// table header
		foreach($lang_list as $lang ) {
		    if( $lang != $sl ) {
		        $o .= '<th>';
		        if( $lang == $cf['language']['default'] ) {
		            $o .= '<a href="'.$pth['folder']['base'].'?&amp;multilang&amp;normal">';
		            $o .= tag('img src="'.$pth['folder']['flags'].'/'.$lang.'.gif'.'" alt="'.$lang.'" title="&nbsp;'.$lang.'&nbsp;" class="flag"');
		            $o .= '</a> (default)';
		        } else {
		            $o .= '<a href="'.$pth['folder']['base'].$lang.'/?&amp;multilang&amp;normal">';
		            $o .= tag('img src="'.$pth['folder']['flags'].'/'.$lang.'.gif'.'" alt="'.$lang.'" title="&nbsp;'.$lang.'&nbsp;" class="flag"');
		            $o .= '</a>';
		        }
		        $o .= '</th>';
		    }
		}
		$o .= '</tr>';
		
		// process all pages
		foreach( $h as $id => $title ) {
		    $unified_name = $page_data[$id]['unified_name'];
		    $o .= '<tr><td>'.a($id). $title.'</a></td>';
		    if( $unified_name ) {
    		    $o .= '<td>'.$unified_name.'</td>';
    		    // all other languages
    		    foreach($lang_list as $lang ) {
    		        if( $lang != $sl ) {
            		    $o .= '<td>';
        		        // $translated_page = array_search($unified_name, $names[$lang]);
            		    if( in_array($unified_name, $names[$lang]) ) {
    	        	        $o .= '+';
    	        	    }
    	    	        $o .= '</td>';
    	    	    }
	    	    }
		    }
		    $o .= '</tr>';
		}
		
		$o.='</table>';
		

	} else {
		$hint=array();
		$hint['mode_donotshowvarnames'] = false;
		$o.=plugin_admin_common($action, $admin, $plugin, $hint);
	}
}
?>
