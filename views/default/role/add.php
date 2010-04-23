<?php

$body ="<p>".elgg_echo("roles:name");
$body.= elgg_view("input/text",array('internalname'=>'name','validate'=>'required'));
$body.= "</p>";

$plugins = role_plugins();


$body.="<p>".elgg_echo("roles:plugins")."<br>";
$body.= elgg_view("input/comboselect",array('internalname'=>'plugins',
                                            'options'=>$plugins,
											'validate'=>'required'));
$body.= "</p>";

$body.="<p>";
$body.= elgg_view("input/submit",array('value'=>elgg_echo('save')));
$body.= "</p>";

echo elgg_view("input/form",array('internalname'=>'roles_form',
								  'action'=>$vars['url']."action/role/add",
                                  'body'=>$body,
                                  'validate'=>true));
?>

