<?php

if(isset($_GET['ajax']))
{
  require_once('../../api/site.init.php');
}

$_spotlight = empty($_GET['spotlight']) ? 'featured' : strtolower($_GET['spotlight']);
$_data = array();

switch ($_spotlight)
{
  case 'tag':
    $_data = (new Tag)->getTop(6);
    break;

  case 'location':
    $_data = (new Resort)->getTop(6);
    break;

  default:
    $_data = (new Image)->getTop(6);
}

if (!$_data || sizeof ($_data) == 0):

?>

<div class="no-images text-center"><br><br><br>Hmmm, we can't find anything :( <br><br><br><br></div>

<?php

else:

  foreach ($_data as $_value):
    $_image = false;

    switch ($_spotlight)
    {
      case 'tag':
        $_image = (new Image)->getByTag($_value);
        break;

      case 'location':
        $_image = (new Image)->getByLocation($_value);
        break;

      default:
        $_image = $_value;
    }

    $text = ($_spotlight == 'featured') ? 'View' : $_value;
    $link = ($_spotlight == 'featured') ? '/capture/' . $_image->displayID : '/search?' . $_spotlight . '=' . $_value;
    $path = $_image->thumbnails['custom'];

?>

<a href="<?php echo $link; ?>" class="featured-image" style="background-image: url('<?php echo $path; ?>')">
	<span class="inner-featured"></span>
	<span class="inner-name"><span class="button button-white see-through"><?php echo $text; ?></span></span>
</a><!-- .featured-image -->

<?php

  endforeach;

?>

<div class="clear"></div>

<?php

endif;