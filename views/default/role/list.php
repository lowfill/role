<?php

set_context('search');
echo list_entities('object','role',0,10,false,false,true);
set_context('role');
?>