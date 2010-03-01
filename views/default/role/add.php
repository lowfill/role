<?php
$index = $vars['url']."pg/roles/";
$add = $vars['url']."pg/roles/add";

?>

<div id="elgg_horizontal_tabbed_nav">
  <ul>
  <li><a href="<?php echo $index;?>"><?php echo elgg_echo("role:available");?></a></li>
  <li><a href="<?php echo $add;?>"  class="selected"><?php echo elgg_echo("role:add");?></a></li>
  </ul>
</div>
<?php
    //TODO Add role form
?>
