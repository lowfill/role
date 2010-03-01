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

function add_role($name,$contexts = array()){
  $roles = get_plugin_setting("roles","roles");
  if(empty($roles)){
    $roles = array();
  }
  else{
    $roles = unserialize($roles);
  }
  if(!in_array($name,$roles)){
    $roles[$name] = $contexts;
    set_plugin_setting("roles","roles",serialize($roles));
    return true;
  }
  return false;
}


function add_role_context($role_name,$context){
  $roles = get_plugin_setting("roles","roles");
  if(empty($roles)){
    $roles = array();
  }
  else{
    $roles = unserialize($roles);
  }
  if(!in_array($role_name,$roles)){
    $roles[$role_name] = array();
  }
  if(!in_array($context,$roles[$role_name])){
    $roles[$role_name][] = $context;
    set_plugin_setting("roles","roles",serialize($roles));
    return true;
  }
  return false;
}

function assign_role($user, $role){
  if(is_numeric($user)){
    $user = get_entity($user);
  }

  if($user instanceof ElggUser){
    return assign_role_to_user($user,$role);
  }
  else if($user instanceof ElggGroup){
    $success = array();
    $members = get_entities_from_relationship('member', $user->getGUID(), true, '', '', 0, "", 100);
    if(!empty($members)){
      foreach($members as $member){
        if(assign_role_to_user($member,$role)){
          $success ++;
        }
      }
    }
    return (count($members,$success));
  }
  return false;
}

function assign_role_to_user($user,$role){
  if(is_numeric($user)){
    $user = get_entity($user);
  }

  if($user instanceof ElggUser){
    $user_roles = $user->get("roles");
    if(!is_array($user_roles)){
      $user_roles = array();
    }
    else{
      $user_roles = unserialize($user_roles);
    }
    if(!in_array($role,$user_roles)){
      $user_roles[] = $role;
      $user->set("roles",serialize($user_roles));
      $user->save();
      return true;
    }
  }
  return false;
}

function remove_role($user,$role){
  if(is_numeric($user)){
    $user = get_entity($user);
  }
  if($user instanceof ElggUser){
    return remove_role_from_user($user,$role);
  }
  else if($user instanceof ElggGroup){
    $success = array();
    $members = get_entities_from_relationship('member', $user->getGUID(), true, '', '', 0, "", 100);
    if(!empty($members)){
      foreach($members as $member){
        if(remove_role_from_user($member,$role)){
          $success ++;
        }
      }
    }
    return (count($members,$success));
  }
  return false;
}
function remove_role_from_user($user,$role){
  if(is_numeric($user)){
    $user = get_entity($user);
  }
  if($user instanceof ElggUser){
    $user_roles = $user->get("roles");
    if(!empty($user_roles)){
      $user_roles = unserialize($user_roles);
      if(is_array($user_roles) && in_array($role,$user_roles)){
        $user_roles = array_diff($user_roles, array($role));
        $user->set("roles",serialize($user_roles));
        $user->save();
        return true;
      }
    }
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