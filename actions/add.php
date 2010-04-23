<?php

action_gatekeeper();

$role_name = get_input('name');
$plugins = get_input('plugins');
if(!empty($role_name)){
    $role = new ElggObject();
    $role->subtype = 'role';
    $role->name = strtolower($role_name);
    $role->description = $role_name;
    if(!empty($plugins)){
        $role->contexts = $plugins;
    }
    if($role->save()){
        system_message(elgg_echo('role:success:role_added'));
        forward('pg/roles/?option=list');
    }
    else{
        register_error(elgg_echo('role:error:not_added'));
    }
}
else{
    register_error(elgg_echo('role:error:invalid_parameters'));
}
forward('pg/roles/?option=add');
?>