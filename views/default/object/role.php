<?php

if ($vars['full']) {
    echo elgg_view('export/entity', $vars);
} else {


    $title = $vars['entity']->title;
    if (!$title) $title = $vars['entity']->description;
    if (!$title) $title = get_class($vars['entity']);

    $controls = "";
    if ($vars['entity']->canEdit())
    {
        $controls .= " (<a href=\"{$vars['url']}pg/roles/edit/{$vars['entity']->guid}\">" . elgg_echo('edit') . "</a>";
        $controls .= "&nbsp;|&nbsp;";
        $link = $vars['url']."action/role/delete?role_guid=".$vars['entity']->guid;
        $controls .= elgg_view("output/confirmlink",array('text'=>elgg_echo('delete'),'href'=>$link,'is_action'=>true,'confirm'=>elgg_echo('question:areyousure'))). "</a>";
        $controls .= "&nbsp;|&nbsp;";
        $controls .= " <a href=\"{$vars['url']}pg/roles/assign/{$vars['entity']->guid}\">" . elgg_echo('roles:assign') . "</a>)";
    }

    $info = "<div><p><b><a href=\"" . $vars['entity']->getUrl() . "\">" . $title . "</a></b> $controls </p></div>";

    if (get_input('search_viewtype') == "gallery") {

        $icon = "";

    }

    $owner = $vars['entity']->getOwnerEntity();
    $ownertxt = elgg_echo('unknown');
    if ($owner)
    $ownertxt = "<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>";
    $contexts = $vars['entity']->contexts;
    if(!is_array($contexts)){
        $contexts = array($contexts);
    }
    $contexts = implode(",",$contexts);
    $info .= "<div><b>".elgg_echo("roles:plugins")."</b>: {$contexts}</div>";
    $info .= "<div>".sprintf(elgg_echo("entity:default:strapline"),
                             friendly_time($vars['entity']->time_created),
                             $ownertxt);
    $info .= "</div>";

    echo elgg_view_listing($icon, $info);
}
?>