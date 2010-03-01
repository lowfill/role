<?php
/**
 *
 * @author Diego Andrés Ramírez Aragón
 * @copyright Corporación Somos más - 2008
 */

if(isset($vars["rolename"])) {
  $title = $vars["rolename"];
  $contexts = $vars["contexts"];
}

$role_label = elgg_echo("role:name");
$role_field = elgg_view("input/text", array("internalname" => "rolename","value" => $role));

$context_label = elgg_echo("role:contexts");
$plugin_list_ = get_installed_plugins();
if(is_plugin_enabled("custom_external_mod")){
  $custom_plugins = get_plugin_setting('custom_path', 'custom_external_mod');
  if(!empty($custom_plugins)){
    $custom_plugins = $CONFIG->path.$custom_plugins;
    custom_regenerate_plugin_list(null,$custom_plugins);
    $plugin_list_ = array_merge($plugin_list_,get_custom_installed_plugins($custom_plugins));
  }
}
$plugin_list = array();
foreach($plugin_list_ as $plugin=>$data){
  $plugin_list[] = $plugin;
}

  error_log(print_r($vars["role_ignores"],true));

if(is_array($plugin_list)){
  $plugin_list = array_diff($plugin_list,$vars["role_ignores"]);
}
$context_field = elgg_view("input/selector",array("internalname"=>"contexts","options_values"=>$plugin_list));

$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

$form_body = <<<EOT
<p><label>$role_label<br />
$role_field
</label>
</p>

<p><label>$context_label<br />
$context_field
</label>
</p>

<p>
$submit_input
</p>

EOT;
echo elgg_view('input/form', array('action' => "{$vars['url']}action/roleadd", 'body' => $form_body));
?>
