<?php

action_gatekeeper();

$role_guid = get_input('role_guid');
$role_name = get_input('name');
$plugins = get_input('plugins');
if(!empty($role_guid) && !empty($role_name)){
    $role = get_entity($role_guid);
    if(!empty($role)){
        $role->name = strtolower($role_name);
        $role->description = $role_name;
        if(!empty($plugins)){
            $role->contexts = $plugins;
        }
        if($role->save()){
            system_message(elgg_echo('role:success:role_edited'));
        }
        else{
            register_error(elgg_echo('role:error:not_edited'));
        }
    }
    else{
        register_error(elgg_echo('role:error:invalid_id'));
    }
}
else{
    register_error(elgg_echo('role:error:invalid_parameters'));
}
forward('pg/roles/?option=list');
?>