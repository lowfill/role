<?php

$role_guid = get_input('role_guid');
if(!empty($role_guid)){
    $role = get_entity($role_guid);
    if(!empty($role)){
        $value = array();
        if(empty($role->members)){
            $members = get_entities_from_metadata('role',$role->name,'user','',0,100);
            if(!empty($members)){
                foreach($members as $member){
                    $value[]=$member->guid."||".mb_convert_case($member->name,MB_CASE_TITLE,'UTF-8') ;
                }
            }
        }
        else{
            $members = explode(",",$role->members);
            if(!empty($members)){
                foreach($members as $member){
                    if(!empty($member)){
                        $user = get_entity($member);
                        if(!empty($user)){
                            $value[]=$user->guid."||".mb_convert_case($user->name,MB_CASE_TITLE,'UTF-8') ;
                        }
                    }
                }
            }
        }
        $value = implode(",",$value);
        $body.= elgg_view('input/hidden',array('internalname'=>'role_guid','value'=>$role_guid));
        $body.=$role->description;
        $body.="<p>".elgg_echo('role:members')."<br>";
        $body.= elgg_view('input/autosuggest',array('internalname'=>'members',
                                                    'value'=>$value,
                                                    'suggest'=>'users'));
        $body.="</p>";

        $body.="<p>";
        $body.= elgg_view("input/submit",array('value'=>elgg_echo('save')));
        $body.= "</p>";

        echo elgg_view('input/form',array('name'=>'role_assign',
                                          'action'=>$vars['url'].'action/role/assign',
                                          'body'=>$body));
    }
    else{
        echo elgg_echo('role:error:invalid_id');
    }
}
else{
    echo elgg_echo('role:error:not_id');
}
?>