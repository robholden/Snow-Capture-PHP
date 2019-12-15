<?php

// Get tag
$_tag = $api->checkGet('tag');

// If set search for resorts
$api->data = array('tags' => (new Tag)->getStartingWith((! $_tag) ? '' : $_tag));	
