<?php
/**
 * Elgg members administration view
 *
 * @package ElggGroupExtended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2008
 * @link http://www.somosmas.org
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
require_once(dirname(__FILE__))."/config.php";
gatekeeper();
admin_gatekeeper();

set_context("admin");
$title = elgg_echo("role:admin");

$area2 = elgg_view_title($title);


error_log(print_r($role_ignores,true));
$area2 .= elgg_view("forms/role/roleadmin",array("role_ignores"=>$role_ignores));

$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);

page_draw($title, $body);
?>