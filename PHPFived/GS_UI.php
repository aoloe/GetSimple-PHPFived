<?php
/**
 * UI related functions that are offered by GS.
 * Currently it only allows the plugin to get javascript files loaded only once.
 * TODO: implement, reading the existing info from plugin_functions.php in initialize
 * TODO: implement all the methods GS already has in plugin_functions.php
 */

define('GS_UI_JAVASCRIPT_LIBRARY_LOAD_HEADER', 1);
define('GS_UI_JAVASCRIPT_LIBRARY_LOAD_NOW', 2);
define('GS_UI_JAVASCRIPT_LIBRARY_LOAD_FOOTER', 3);

class GS_UI {
    protected static $javascript_library = array();
    protected static $javascript_library_queue = array();

    public static function initialize() {
        // TODO: for now read in the GS own js from the global variables...
        // files with "load == true" and "in_footer == false" have already been loaded
        // those with "load == true" and "in_footer == true" will be loaded for GS at the end of the page
        // those with "where == 2 (GSBACK)", are only needed by GS for the backend.
        global $GS_scripts;
        // debug('GS_scripts', $GS_scripts);
        foreach ($GS_scripts as $key => $value) {
            self::$javascript_library[$key] = array(
                'name' => $value['name'],
                'version' => $value['ver'],
                'url' => $value['src'],
                'onready' => $value['in_footer'], // no idea what this is good for... replace by onload()?
                'frontend' => $value['where'] & GSBACK,
                'backend' => !($value['where'] & GSBACK),
                'loaded' => true,
                // here are the other values...
                'where' => $value['where'],
                'load' => $value['load'],
                'in_footer' => $value['in_footer'], // no idea what this is good for... replace by onload()?
            );
            self::$javascript_library[$key]['loaded'] = $GS_scripts;
        }
    }
    /**
     * Put the path for this javascript module in the list of the available ones.
     * Only the first path matching a specific scirpt will be used.
     * (Not sure wether enabling multiple version is an interesting feature...)
     * @param string $name the name refering the plugin (its lowercase version will be used)
     * @param string $path
     */
    public static function register_javascript_library($name, $path, $version = null) {
        if (!array_key_exists($name, self::$javascript_library)) {
            if (is_null($version)) {$version = '0.0';}
            self::$javascript_library[strtolower($name)] = array (
                'name' => $name,
                'version' => $version,
                'url' => $path,
                'loaded' => false,
                'onready' => true, // for now all the scripts are added "onready"
            );
        }
    }

    /**
     * Add the link to the scripts:
     * - in the headers (not implemented yet)
     * - as soon as possible
     * - at the end of the document 
     * @param string $name the name matching the values in $javascript_load
     * @param integer $position one 
     * @return string
     */
    public static function load_javascript_library($name, $position = GS_UI_JAVASCRIPT_LIBRARY_LOAD_HEADER) {
        $render = '';
        if (
            array_key_exists($name, self::$javascript_library) &&
            !self::$javascript_library[$name]['loaded']
        ) {
            if ($position == GS_UI_JAVASCRIPT_LIBRARY_LOAD_NOW) {
                if (array_key_exists($name, self::$javascript_library_queue)) {
                }
                self::render_javascript_library($name);
            } else {
            }
            self::$javascript_library[$name]['loaded'] = true;
            if (array_key_exists($name, self::$javascript_library_queue)) {
            } else {
                self::$javascript_library_queue[] = $name;
            }
        }
        // if the headers have not been output, put it there, otherwise now.
    }
    public static function render_javascript_library($name = null) {
        if (isset($name)) {
            if (array_key_exists($name, self::$javascript_library)) {
                self::$javascript_library[$name]['loaded'] = true;
                echo('<script type="text/javascript" src="'.self::$javascript_library[$name]['url'].'"></script>'."\n");
            }
        } else {
            foreach (self::$javascript_library_queue as $item) {
                self::render_javascript_library($item);
            }
            self::$javascript_library_queue = array();
        }
    }
}
/*
// jquery
$jquery_ver    = '1.7.1';
$jqueryui_ver = '1.10.0';

$GS_script_assets['jquery']['cdn']['url']      = '//ajax.googleapis.com/ajax/libs/jquery/'.$jquery_ver.'/jquery.min.js';
$GS_script_assets['jquery']['cdn']['ver']      = $jquery_ver;

$GS_script_assets['jquery']['local']['url']    = $SITEURL.$GSADMIN.'/template/js/jquery/jquery-'.$jquery_ver.'.min.js';
$GS_script_assets['jquery']['local']['ver']    = $jquery_ver;

// jquery-ui
$GS_script_assets['jquery-ui']['cdn']['url']   = '//ajax.googleapis.com/ajax/libs/jqueryui/'.$jqueryui_ver.'/jquery-ui.min.js';
$GS_script_assets['jquery-ui']['cdn']['ver']   = $jqueryui_ver;

$GS_script_assets['jquery-ui']['local']['url'] = $SITEURL.$GSADMIN.'/template/js/jqueryui/js/jquery-ui-'.$jqueryui_ver.'.custom.min.js';
$GS_script_assets['jquery-ui']['local']['ver'] = $jqueryui_ver;

// misc
$GS_script_assets['fancybox']['local']['url']  = $SITEURL.$GSADMIN.'/template/js/fancybox/jquery.fancybox.pack.js';
$GS_script_assets['fancybox']['local']['ver']  = '2.0.4';

$GS_style_assets['fancybox']['local']['url']   =  $SITEURL.$GSADMIN.'/template/js/fancybox/jquery.fancybox.css';
$GS_style_assets['fancybox']['local']['ver']   = '2.0.4';

// $GS_style_assets['jquery-ui']['local']['url']   =  $SITEURL.$GSADMIN.'/template/js/jqueryui/css/getsimple/jquery-ui-1.8.20.gs.css';
$GS_style_assets['jquery-ui']['local']['url']   =  $SITEURL.$GSADMIN.'/template/js/jqueryui/css/custom/jquery-ui-1.10.0.custom.min.css';
$GS_style_assets['jquery-ui']['local']['ver']   = '1.10.0';

// Register shared javascript/css scripts for loading into the header
if (!getDef('GSNOCDN',true)){
	register_script('jquery', $GS_script_assets['jquery']['cdn']['url'], $GS_script_assets['jquery']['cdn']['ver'], FALSE);
	register_script('jquery-ui',$GS_script_assets['jquery-ui']['cdn']['url'],$GS_script_assets['jquery-ui']['cdn']['ver'],FALSE);
} else {
	register_script('jquery', $GS_script_assets['jquery']['local']['url'], $GS_script_assets['jquery']['local']['ver'], FALSE);
	register_script('jquery-ui',$GS_script_assets['jquery-ui']['local']['url'],$GS_script_assets['jquery-ui']['local']['ver'],FALSE);
}
register_script('fancybox', $GS_script_assets['fancybox']['local']['url'], $GS_script_assets['fancybox']['local']['ver'],FALSE);
register_style('fancybox-css', $GS_style_assets['fancybox']['local']['url'], $GS_style_assets['fancybox']['local']['ver'], 'screen');

register_style('jquery-ui', $GS_style_assets['jquery-ui']['local']['url'], $GS_style_assets['jquery-ui']['local']['ver'], 'screen');

// Queue our scripts and styles for the backend
queue_script('jquery', GSBACK);
queue_script('jquery-ui', GSBACK);
queue_script('fancybox', GSBACK);
queue_style('fancybox-css',GSBACK);
queue_style('jquery-ui',GSBACK);
queue_style('jquery-ui-theme',GSBACK);

// Register a script to include in Themes
function register_script($handle, $src, $ver, $in_footer=FALSE){
	global $GS_scripts;
	$GS_scripts[$handle] = array(
	  'name' => $handle,
	  'src' => $src,
	  'ver' => $ver,
	  'in_footer' => $in_footer,
	  'where' => 0
	);
}

// De-Register Script
function deregister_script($handle){
	global $GS_scripts;
	if (array_key_exists($handle, $GS_scripts)){
		unset($GS_scripts[$handle]);
	}
}

// Queue a script for loading
function queue_script($handle,$where){
	global $GS_scripts;
	if (array_key_exists($handle, $GS_scripts)){
		$GS_scripts[$handle]['load']=true;
		$GS_scripts[$handle]['where']=$GS_scripts[$handle]['where'] | $where;
	}
}

// Remove a queued script
function dequeue_script($handle, $where){
	global $GS_scripts;
	if (array_key_exists($handle, $GS_scripts)){
		$GS_scripts[$handle]['load']=false;
		$GS_scripts[$handle]['where']=$GS_scripts[$handle]['where'] & ~ $where;
	}
}

// Echo and load scripts
function get_scripts_frontend($footer=FALSE){
	global $GS_scripts;
	if (!$footer){
		get_styles_frontend();
	}
	foreach ($GS_scripts as $script){
		if ($script['where'] & GSFRONT ){
			if (!$footer){
				if ($script['load']==TRUE && $script['in_footer']==FALSE ){
					 echo '<script src="'.$script['src'].'?v='.$script['ver'].'"></script>';
					 cdn_fallback($script);		 					 
				}
			} else {
				if ($script['load']==TRUE && $script['in_footer']==TRUE ){
					 echo '<script src="'.$script['src'].'?v='.$script['ver'].'"></script>';
					 cdn_fallback($script);		 					 
				}
			}
		}
	}
}

// Echo and load scripts
function get_scripts_backend($footer=FALSE){
	global $GS_scripts;
	if (!$footer){
		get_styles_backend();
	}

	# debugLog($GS_scripts);
	foreach ($GS_scripts as $script){
		if ($script['where'] & GSBACK ){	
			if (!$footer){
				if ($script['load']==TRUE && $script['in_footer']==FALSE ){
					 echo '<script src="'.$script['src'].'?v='.$script['ver'].'"></script>';
					 cdn_fallback($script);		 
				}
			} else {
				if ($script['load']==TRUE && $script['in_footer']==TRUE ){
					 echo '<script src="'.$script['src'].'?v='.$script['ver'].'"></script>';
					 cdn_fallback($script);		 					 
				}
			}
		}
	}
}

// Add javascript for cdn fallback to local
function cdn_fallback($script){
	GLOBAL $GS_script_assets, $GS_asset_objects;	
	if (getDef('GSNOCDN',true)) return; // if nocdn skip
	if($script['name'] == 'jquery' || $script['name'] == 'jquery-ui'){
		echo "<script>";
		echo "window.".$GS_asset_objects[$script['name']]." || ";
		echo "document.write('<!-- CDN FALLING BACK --><script src=\"".$GS_script_assets[$script['name']]['local']['url'].'?v='.$GS_script_assets[$script['name']]['local']['ver']."\"><\/script>');";
		echo "</script>";
	}					
}

// Queue a Style for loading
function queue_style($handle,$where=1){
	global $GS_styles;
	if (array_key_exists($handle, $GS_styles)){
		$GS_styles[$handle]['load']=true;
		$GS_styles[$handle]['where']=$GS_styles[$handle]['where'] | $where;
	}
}

// Remove a queued Style
function dequeue_style($handle,$where){
	global $GS_styles;
	if (array_key_exists($handle, $GS_styles)){
		$GS_styles[$handle]['load']=false;
		$GS_styles[$handle]['where']=$GS_styles[$handle]['where'] & ~$where;
	}
}

// Register a Style to include in Themes
function register_style($handle, $src, $ver, $media){
	global $GS_styles;
	$GS_styles[$handle] = array(
	  'name' => $handle,
	  'src' => $src,
	  'ver' => $ver,
	  'media' => $media,
	  'where' => 0
	);	
}

// Echo and load Styles in the Theme header
function get_styles_frontend(){
	global $GS_styles;
	foreach ($GS_styles as $style){
		if ($style['where'] & GSFRONT ){
				if ($style['load']==TRUE){
				 echo '<link href="'.$style['src'].'?v='.$style['ver'].'" rel="stylesheet" media="'.$style['media'].'">';
				}
		}
	}
}
//
// Get Styles Backend
// Echo and load Styles on Admin
function get_styles_backend(){
	global $GS_styles;
	foreach ($GS_styles as $style){
		if ($style['where'] & GSBACK ){
				if ($style['load']==TRUE){
				 echo '<link href="'.$style['src'].'?v='.$style['ver'].'" rel="stylesheet" media="'.$style['media'].'">';
				}
		}
	}
}
*/
