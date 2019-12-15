<?php

$_images = (new Image)->getTop(15, false);

if (sizeof($_images) > 0)
{
  foreach ($_images as $_image)
  {
    echo '<a href="/capture/' . $_image->displayID . '"><img src="' . $_image->thumbnails['custom'] . '" /></a>';
  }
  
  echo '<div class="clear"></div>';
}