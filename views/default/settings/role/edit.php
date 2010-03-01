<?php
/**
*
* @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
* @copyright Corporación Somos más - 2008
*/


?>
<p>
	<?php echo elgg_echo('role:showextratools'); ?>

	<select name="params[showextratools]">
		<option value="yes" <?php if ($vars['entity']->showextratools == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
		<option value="no" <?php if ($vars['entity']->showextratools != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
	</select>

</p>