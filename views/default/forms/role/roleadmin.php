<?php
/**
*
* @author Diego Andrés Ramírez Aragón
* @copyright Corporación Somos más - 2008
*/

?>
<h2><?php echo elgg_echo('role:admin'); ?></h2>
<div id="role-invite">
<ul>
	<li><a href="#role-definition"><?php echo elgg_echo('role:definition');?></a></li>
	<li><a href="#role-assign"><?php echo elgg_echo('role:assign');?></a></li>
</ul>
<div id="role-definition"><?php echo elgg_view("forms/role/definition",$vars);?>
</div>
<div id="role-assign"><?php echo elgg_view("forms/role/assign",$vars);?>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#role-invite > ul").tabs();
});

</script>
