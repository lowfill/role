<?php
/**
*
* @author Diego Andrés Ramírez Aragón
* @copyright Corporación Somos más - 2008
*/

	$class = $vars['class'];
	if (!$class) $class = "input-pulldown";

	$size = (isset($vars["size"]))?$vars["size"]:6;
?>
<table boder="1">
<tr>
 <td width="43%">
<select name="<?php echo $vars['internalname']; ?>s" <?php echo $vars['js']; ?> <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>" size="<?php echo $size?>" multiple="multiple">
<?php
	if ($vars['options_values'])
	{
		foreach($vars['options_values'] as $value => $option) {
	        if ($value != $vars['value']) {
	            echo "<option value=\"$value\">{$option}</option>";
	        } else {
	            echo "<option value=\"$value\" selected=\"selected\">{$option}</option>";
	        }
	    }
	}
	else
	{
	    foreach($vars['options'] as $option) {
	        if ($option != $vars['value']) {
	            echo "<option>{$option}</option>";
	        } else {
	            echo "<option selected=\"selected\">{$option}</option>";
	        }
	    }
	}
?>
</select>
 </td>
 <td width="4%" align="center">
 	<input type="button" value="&gt;" onclick="adicionar('<?php echo $vars["internalname"];?>s','<?php echo $vars["internalname"];?>Select','<?php echo $vars["internalname"];?>')">
	<br>
	<input type="button" value="&lt;" onclick="eliminar('<?php echo $vars["internalname"];?>s','<?php echo $vars["internalname"];?>Select','<?php echo $vars["internalname"];?>')">
 </td>
 <td width="43%">
   <select name="<?php echo $vars["internalname"];?>Select" id="<?php echo $vars["internalname"];?>Select" class="role_field" size="6" multiple="multiple">
   </select>
   <input type="hidden" name="<?php echo $vars["internalname"];?>" id="<?php echo $vars["internalname"];?>">
  </td>
</tr>
</table>
