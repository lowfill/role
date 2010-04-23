<?php

$guid = get_input('role_guid');

if(!empty($guid)){
    $role = get_entity($guid);

    $body.= elgg_view("input/hidden",array('internalname'=>'role_guid','value'=>$guid));
    $body.="<p>".elgg_echo("roles:name");
    $body.= elgg_view("input/text",array('internalname'=>'name',
                                         'value'=>$role->description,
    									 'validate'=>'required'));
    $body.= "</p>";

    $plugins = role_plugins();


    $body.="<p>".elgg_echo("roles:plugins")."<br>";
    $body.= elgg_view("input/comboselect",array('internalname'=>'plugins',
                                            'options'=>$plugins,
                                            'value'=>$role->contexts,
											'validate'=>'required'));
    $body.= "</p>";

    $body.="<p>";
    $body.= elgg_view("input/submit",array('value'=>elgg_echo('save')));

    $body.= "</p>";

    echo elgg_view("input/form",array('internalname'=>'roles_form',
								  'action'=>$vars['url']."action/role/edit",
                                  'body'=>$body,
                                  'validate'=>true));

}
else{
    echo elgg_echo("roles:error:not_id");
}

?>
