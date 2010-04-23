<?php

action_gatekeeper();

$role_guid = get_input('role_guid');

if(!empty($role_guid)){
    $role = get_entity($role_guid);
    if(!empty($role)){
        $members = get_input("members");
        $role_members = $role->members;
        if(empty($role_members)){
            $role->members = $members;
        }
        else{
            $role_members = explode(",",trim($role->members));
            $members = explode(",",trim($members));
            //Updating existing members
            foreach($role_members as $member){
                $user = get_entity($member);
                if(!empty($user)){
                    $member_roles = $user->get('role');
                    if(empty($member_roles)){
                        $member_roles = array();
                    }
                    if(!is_array($member_roles)){
                        $member_roles = array($member_roles);
                    }
                    if(!in_array($member,$members)){
                        // Drop from user
                        $member_roles = array_diff($member_roles,array($role->name));
                    }
                    else if(!in_array($role->name,$member_roles)){
                        $member_roles[]=$role->name;
                    }
                    $user->clearMetadata('role');
                    $user->role = $member_roles;
                    $user->save();
                }
            }
            //new Members
            $new_members = array_diff($members,$role_members);
            foreach($new_members as $member){
                $user = get_entity($member);
                if(!empty($user)){
                    $member_roles = $user->get('role');
                    if(empty($member_roles)){
                        $member_roles = array();
                    }
                    if(!is_array($member_roles)){
                        $member_roles = array($member_roles);
                    }
                    if(!in_array($role->name,$member_roles)){
                        $member_roles[]=$role->name;
                    }
                    $user->clearMetadata('role');
                    $user->role = $member_roles;
                    $user->save();
                }
            }
            $role->members = implode(",",$members);
        }
        system_message(elgg_echo('role:success:assign'));
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