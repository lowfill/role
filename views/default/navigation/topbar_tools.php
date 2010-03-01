<?php

/**
 * Elgg standard tools drop down
 * This will be populated depending on the plugins active - only plugin navigation will appear here
 *
 * @package Elgg
 * @subpackage Core
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008
 * @link http://elgg.org/
 *
 */

// Add to this file some extra functions that you wants to enable
$rules = array();
$rules[] = elgg_echo("friends");
$rules[] = elgg_echo("blogs");
$rules[] = elgg_echo("gpractices");
$rules[] = elgg_echo("projects");
$rules[] = elgg_echo("services");
$rules[] = elgg_echo("groups");
$rules[] = elgg_echo("files");
$rules[] = elgg_echo("pages");
$rules[] = elgg_echo("photos");
$rules[] = elgg_echo("statistics_etl");
$rules[] = elgg_echo("nowet:lists");
$rules[] = elgg_echo("nowet:stats");

$menu = get_register('menu');

//var_dump($menu);

if (is_array($menu) && sizeof($menu) > 0) {
  $alphamenu = array();
  $others = array();
  foreach($rules as $rule){
    foreach($menu as $item) {
      if($item->name == $rule){
        $alphamenu[$item->name] = $item;
        break;
      }
      else{
        $others[$item->name] = $item;
      }
    }
  }

  // Uncomment this for allow extras plugins
  if(get_plugin_setting('showextratools', 'role')=="yes"){
    $alphamenu = array_merge($alphamenu,$others);
  }
  ?>
<ul class="topbardropdownmenu">
    <li class="drop"><a href="#" class="menuitemtools"><?php echo(elgg_echo('tools')); ?></a>
	  <ul>
      <?php

			foreach($alphamenu as $item) {
    			echo "<li><a href=\"{$item->value}\">" . sprintf(elgg_echo("role:my"),$item->name) . "</a></li>";
			}
     ?>
     <li>
  		<a href="<?php echo $vars['url']; ?>pg/settings/" ><?php echo sprintf(elgg_echo("my-single"),elgg_echo('settings')); ?></a>
	 </li>

      </ul>
    </li>
</ul>

<script type="text/javascript">
  $(function() {
    $('ul.topbardropdownmenu').elgg_topbardropdownmenu();
  });
</script>

<?php
		}
?>