<?php
/**
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2008
 */

function role_init(){
    register_plugin_hook('display', 'view', 'role_profile_links_overwrite_hook');
    register_page_handler('roles','role_page_handler');
}

function role_pagesetup(){
    if (get_context() == 'admin' && isadminloggedin()) {
        global $CONFIG;
        add_submenu_item(elgg_echo('role:admin'), $CONFIG->wwwroot . 'pg/roles/admin',"r");

    }
}

function role_page_handler($page){
    if(isset($page[0])){
        if($page[0]=="admin"){
            @include (dirname(__FILE__)."/roleadmin.php");
            exit;
        }
    }
}

function role_add_role($name,$contexts = array()){

    $role_count = get_entities_from_metadata('name',$name,'object','role',0,1,0,'',0,true);
    if($role_count==0){
        $role = new ElggObject();
        $role->type='role';
        $role->name=$name;
        return $role->save();
        //@todo Add contexts config when it is provided
    }
    return false;
}


function role_add_role_context($role_id,$context){
    //@todo Implement this
}

function role_assign_role($user_id, $role_id){
    $user = get_entity($user_id);
    $role = get_entity($role_id);

    if($user instanceof ElggUser){
        $roles = $user->role;
        if(!is_array($roles)){
            $roles = array($roles);
        }
        if(!in_array($role->name,$roles)){
            create_metadata($user->guid,'role',$role->name,'text',$user->guid,ACCESS_PRIVATE,true);
            return true;
        }
    }
    return false;
}


function remove_role($user_id,$role_id){
    $user = get_entity($user_id);
    $role = get_entity($role_id);
    if($user instanceof ElggUser){
        $roles = $user->role;
        $roles = array_diff($roles,array($role->name));
        $user->role=$roles;
        return true;
    }
    return false;
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