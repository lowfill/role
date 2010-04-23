<?php

action_gatekeeper();

$role_guid = get_input('role_guid');
if(!empty($role_guid)){
    $role = get_entity($role_guid);
    if(!empty($role)){
        $role->delete();
        //FIXME Quitar el rol de todos los usuarios que lo tengan
        system_message(elgg_echo('role:success:delete'));
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