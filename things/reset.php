<?php

if (! isset($_GET['pass']))
{
  exit();
}

if ($_GET['pass'] != 'image_cleanup')
{
  exit();
}

require_once('../api/site.init.php');

$db->query('delete from sc_image_likes');
$db->query('delete from sc_image_ratings');
$db->query('delete from sc_image_rejections');
$db->query('delete from sc_image_spotlight');
$db->query('delete from sc_image_tags');
$db->query('delete from sc_images');
$db->query('delete from sc_notifications where image_id > 0');