<?php

$option = get_input('option','list');
$selected="class=\"selected\"";
if(!empty($option)){
    $var="{$option}_selected";
    $$var=$selected;
}

?>

<div id="elgg_horizontal_tabbed_nav">
  <ul>
  <li><a href="<?php echo $vars['url']."pg/roles/?option=list"?>" <?php echo $list_selected?>><?php echo elgg_echo("role:list");?></a></li>
  <li><a href="<?php echo $vars['url']."pg/roles/?option=add"?>" <?php echo $add_selected?>><?php echo elgg_echo("role:add");?></a></li>
  </ul>
</div>
<?php
    echo elgg_view("role/{$option}",$vars);
?>
