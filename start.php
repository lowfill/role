<?php
/**
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2008
 */

function role_init(){
    //1    register_plugin_hook('display', 'view', 'role_profile_links_overwrite_hook');
    register_page_handler('roles','role_page_handler');

    register_action('role/add',false,dirname(__FILE__)."/actions/add.php",true);
    register_action('role/edit',false,dirname(__FILE__)."/actions/edit.php",true);
    register_action('role/delete',false,dirname(__FILE__)."/actions/delete.php",true);
    register_action('role/assign',false,dirname(__FILE__)."/actions/assign.php",true);
}

function role_pagesetup(){
    if (get_context() == 'admin' && isadminloggedin()) {
        global $CONFIG;
        add_submenu_item(elgg_echo('role:admin'), $CONFIG->wwwroot . 'pg/roles/',"r");

    }
}

function role_page_handler($page){
    if(count($page)>=1){
        set_input('section',$page[0]);
        if(count($page)>=2){
            set_input('role_guid',$page[1]);
        }
    }
    @include (dirname(__FILE__)."/index.php");
    exit;
}


function role_has_role($roles,$user=null){
    if(empty($user)){
        $user = get_loggedin_user();
    }
    if(!is_array($roles)){
        $roles = array($roles);
    }

    $user_roles = $user->role;
    if(!is_array($user_roles)){
        $user_roles = array($user_roles);
    }
    foreach($roles as $role){
        return (in_array($role,$user_roles));
    }
    return false;
}

function role_plugins(){
    $plugins = get_installed_plugins();
    $plugins = array_keys($plugins);

//TODO Make the plugin autodiscover 'types' of plugins
    $ignored = array(
        'profile',
        'groups',
        'notifications',
        'messageboard',
        'htmlawed',
        'uservalidationbyemail',
    	'blog',
        'friends',
        'messages',
//        'members',
        'file',
        'tidypics',
        'tinymce',
        'embed',
        'reportedcontent',
        'riverdashboard',
        'thewire',
        'categories',
        'pages',
        'guidtool',
        'logbrowser',
        'garbagecollector',
        'logrotate',
        'crontrigger',
        'diagnostics',
        'captcha',
        'zaudio',
        'externalpages',
        'friendsuggest',
        'blogextended',
        'groupextended',
        'multisuggest',
        'messagesextended',
        'directory',
        'badges',
        'events',
        'itemicon',
        'productos_servicios',
        'counter',
        'grouplayout',
        'gmaplocationfield',
        'content_box',
        'role',
        'bavaria_profile',
        'bavaria_sectores',
        'bavaria_business',
        'bavaria_concurso',
        'o2obasetheme',
        'libform_utils',
        'fb_socialplugins',
        'marketplace',
        'bavariatheme2',
        'twitter',
        'custom_index',
        'defaultwidgets',
        'bookmarks',
        'invitefriends',
        'twitterservice',
        'bavariatheme'
    );
    return array_diff($plugins,$ignored);
}

//@todo Get JS profile/menu/links and profile/menu/links
//@todo make the function that extrats the profile/menu/ and menu info


function role_profile_links_overwrite_hook($hook, $entity_type, $returnvalue, $params){
    global $CONFIG;

    $rules_js = array(
    "profile/menu/actions",
    "profile/menu/links",
  	"messages/menu",
    "blog/menu",
    "service/menu",
    "project/menu",
    "offer/menu",
    "file/menu",
  	"tidypics/menu",
    );

    $rules_profile = array(
    "profile/menu/actions",
    "profile/menu/links",
    "messages/menu",
    "reportedcontent/user_report",
    "profile/menu/adminwrapper"
    );

    $view = $params["view"];
    $vars = $params["vars"];

    if($view =="profile/menu/links"){
        $viewtype = elgg_get_viewtype();
        if (isset($CONFIG->views->extensions[$view])) {
            $viewlist = $CONFIG->views->extensions[$view];
        } else {
            $viewlist = array(500 => $view);
        }
        if(!empty($vars["profile_page"])){
            $viewlist = $rules_profile;
        }
        else{
            $other = array_diff($viewlist,$rules_js);
            $viewlist = array_merge($rules_js, $other);
        }

        ob_start();

        foreach($viewlist as $priority => $view_file) {
            $view_location = elgg_get_view_location($view_file);
            if (file_exists($view_location . "{$viewtype}/{$view_file}.php") && !@include($view_location . "{$viewtype}/{$view_file}.php")) {
                $success = false;
                if ($viewtype != "default") {
                    if (@include($view_location . "default/{$view_file}.php")) {
                        $success = true;
                    }
                }
                if (!$success && isset($CONFIG->debug) && $CONFIG->debug == true) {
                    error_log(" [This view ({$view_file}) does not exist] ");
                }
            } else if (isset($CONFIG->debug) && $CONFIG->debug == true && !file_exists($view_location . "{$viewtype}/{$view_file}.php")) {
                error_log($view_location . "{$viewtype}/{$view_file}.php");
                error_log(" [This view ({$view_file}) does not exist] ");
            }
        }
        $content = ob_get_clean();
        return $content;
    }
}
register_elgg_event_handler('init','system','role_init');
register_elgg_event_handler('pagesetup','system','role_pagesetup');

?>
