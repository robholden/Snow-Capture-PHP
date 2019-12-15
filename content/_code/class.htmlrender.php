<?php

/**
 * Object to render html
 *
 * @author Robert Holden
 */
class HTMLRender
{
  /**
   *
   * @var SnowCapture
   */
  private $sc;




  public function __construct()
  {
    global $sc;
    $this->sc = $sc;
  }




  /**
   * Returns image rating html
   *
   * @param Image $i
   */
  public function imageRating($image_id, $star_rating)
  {
    return "
      <div class='rating-container' data-rating='" . $star_rating . "' data-image='" . $image_id . "'>
      	<div class='rating one'>
      		<span class='fa fa-star-o empty'></span>
      		<span class='fa fa-star-half-o half'></span>
      		<span class='fa fa-star full'></span>
      	</div>

      	<div class='rating two'>
      		<span class='fa fa-star-o empty'></span>
      		<span class='fa fa-star-half-o half'></span>
      		<span class='fa fa-star full'></span>
      	</div>

      	<div class='rating three'>
      		<span class='fa fa-star-o empty'></span>
      		<span class='fa fa-star-half-o half'></span>
      		<span class='fa fa-star full'></span>
      	</div>

      	<div class='rating four'>
      		<span class='fa fa-star-o empty'></span>
      		<span class='fa fa-star-half-o half'></span>
      		<span class='fa fa-star full'></span>
      	</div>

      	<div class='rating five'>
      		<span class='fa fa-star-o empty'></span>
      		<span class='fa fa-star-half-o half'></span>
      		<span class='fa fa-star full'></span>
      	</div>
      </div>
    ";
  }




  /**
   * Returns the autoload button based on page number
   *
   * @param int $page
   */
  public function imgAutoloadButton($page)
  {
    $html = '';

    if ($page <= 1)
    {
      $html .= '
          <div id="more-images" class="more-images autoload clear">
            <a id="next-page" href="#" class="button next-page disabled">
              <i class="fa ion-load-c fa-spin"></i>
            </a>
          </div><!-- #more-images -->
        ';
    }

    else
    {
      $html .= '
          <div id="more-images" class="more-images clear">
            <a id="next-page" href="#" class="button next-page spinner-load">
              Load more
              <i class="fa ion-load-c hidden icon-right"></i>
            </a>
          </div>
        ';
    }

    return $html;
  }




  /**
   * Return default image for profile change
   *
   * @param boolean $choosing
   * @param string $cover
   */
  public function imgChoosing($cover)
  {
    $cover = $cover ? 'true' : 'false';
    $html = '
      <div class="image-wrapping col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="image animate choosing" data-image="0" data-cover="' . $cover . '">
          <a href="#" class="image-container lazy-container anim" data-image="0">
            <img class="lazy" src="/template/images/placeholder.jpg" data-src="/template/images/no-image.jpg" />
          </a><!-- .image-container -->
        </div><!-- .image -->
      </div><!-- .image -->
    ';

    return $html;
  }




  /**
   * Returns html to display image - eg.
   * on Explore page
   *
   * @param Image $image
   */
  public function imgGalleryImage($image)
  {
    $_pageType = $this->sc->vars->pageType;
    $_draft = $image->status == IMAGE_DRAFT;
    $_processing = $image->status == IMAGE_PROCESSING;
    $_private = $image->status == IMAGE_PRIVATE;
    $_choosing = (($_pageType == 'choosing' || $_pageType == 'choosing_cover') && $this->sc->session->exists());
    $_cover = ($_pageType == 'choosing_cover') ? 'true' : 'false';

    $_imageClass = ($_choosing ? ' choosing ' : '') . ((! $this->sc->common->isMobile || $_choosing) ? ' animate ' : '');
    $_iconClass = $_private ? 'lock' : ($_processing ? 'spin ion-load-c' : ($_draft ? 'edit' : ($_choosing ? 'plus' : 'camera')));

    $_link = '/capture/' . $image->displayID . ($_draft ? '/edit' : '');
    $_thumbnail = $image->thumbnails['custom'];
    $_title = $image->title;

    $_likes = (! $_draft && ! $_processing) ? '<span class="small-image-favs">' . $image->likes . '<i class="fa fa-heart icon-right"></i></span>' : '';
    $_rating = (! $_draft && ! $_processing) ? '<div class="small-image-rating">' . (new HTMLRender)->imageRating($image->displayID, $image->rating) . '</div>' : '';

    $_location = 'Unknown';
    if (! empty($this->sc->common->location()))
    {
      $_location = ! empty($image->resort) ? $image->resort : (! empty($image->country) ? $image->country : $location);
    }

    elseif (! empty($image->country))
    {
      $_location = $image->country;
    }

    $_img = ($this->sc->common->isMobile) ? '<img src="' . $_thumbnail . '" alt="' . $_title . '" />' : '<img class="lazy" src="/template/images/blank.jpg" data-src="' . $_thumbnail . '" alt="' . $_title . '" />';

    return '
      <div class="image-wrapping col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="image ' . $_imageClass .  '" data-image="' . $image->displayID . '" data-cover="' . $_cover . '">
          <a href="' . $_link . '" class="image-container lazy-container anim" data-image="' . $image->displayID . '">
            ' . $_img . '

            <span class="view-image">
              <i class="fa fa-' . $_iconClass . '"></i>
            </span>

            <span class="image-name">
              <span class="small-image-title">
                <i class="fa fa-map-marker icon-left"></i>
                ' . $_location . '
              </span>

              ' . $_likes . '
              ' . $_rating . '
            </span>
          </a><!-- .image-container -->
        </div><!-- .image -->
      </div><!-- .image-wrapping -->
    ';
  }


  /**
   * Returns html for an image only showing geo info
   * @param Image $image
   */
  public function imgGeo($image)
  {
    $_thumbnail = $image->thumbnails['custom'];
    $_title = $image->title;
    $_animate = ! $this->sc->common->isMobile ? 'animate' : '';
    $_img = ($this->sc->common->isMobile) ? '<img src="' . $_thumbnail . '" alt="' . $_title . '" />' : '<img class="lazy" src="/template/images/blank.jpg" data-src="' . $_thumbnail . '" alt="' . $_title . '" />';

    return '
      <div class="image-wrapping col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="image geo ' . $_animate . '">
          <a href="/capture/' . $image->displayID . '" class="image-container lazy-container">
            ' . $_img . '
            <span class="view-image">
               <i class="fa fa-camera"></i>
            </span>
            <span class="image-name">
              <span class="small-image-title">
                <i class="fa fa-map-marker icon-left"></i>
                ' . $image->location . '
              </span>

              <span class="small-image-favs">' . $image->likes . '<i class="fa fa-heart icon-right"></i></span>
              <div class="small-image-rating">' . (new HTMLRender)->imageRating($image->displayID, $image->rating) . '</div>
            </span>
          </a><!-- .image-container -->
        </div>
      </div>
    ';
  }


  public function imgNotFound()
  {
    $html = '';
    $logged_user = $this->sc->user;
    $this_user = $this->sc->common->user();
    $common = $this->sc->common;
    $pageType = $this->sc->vars->pageType;

    if ($common->filterSet())
    {
      $html .= "<div class='no-images text-center'><br /><br /><br />It seems we can't find what you're looking for :( <br /><br /><br /><br /></div>";
    }

    elseif ($common->keyword())
    {
      $html .= "<div class='no-images text-center'><br /><br /><br />It seems we can't find anything matching your criteria <br /><br /><br /><br /></div>";
    }

    else
    {
      if ($pageType == 'likes')
      {
        $_toadd = "<div class='no-images text-center'><br /><br /><br />This user hasn't liked anything yet.<br /><br /><br /><br /></div>";

        if ($logged_user)
        {
          if ($logged_user->id == $this_user->id)
          {
            $_toadd = "<div class='no-images text-center'><br /><br /><br />You haven't liked anything, browse images <a href='/'>here</a>.<br /><br /><br /><br /></div>";
          }
        }

        $html .= $_toadd;
      }

      elseif ($pageType == 'drafts')
      {
        $html .= "<div class='no-images text-center'><br /><br /><br /><a href='#' class='open-upload-link'>Upload Images <i class='fa fa-plus icon-right'></i></a><br /><br /><br /><br /></div>";
      }

      elseif ($pageType == 'processing')
      {
        $html .= "<div class='no-images text-center'><br /><br /><br /><a href='#' class='open-upload-link'>Upload Images <i class='fa fa-plus icon-right'></i></a><br /><br /><br /><br /></div>";
      }

      elseif ($pageType == 'privates')
      {
        $html .= "<div class='no-images text-center'><br /><br /><br />Cannot find any private images<br /><br /><br /><br /></div>";
      }

      elseif ($this_user->exists())
      {
        $self = $this_user->username == $logged_user->username ? true : false;

        if ($self)
        {
          $html .= "<div class='no-images text-center'><br /><br /><br /><a href='#' class='open-upload-link'>Upload Images <i class='fa fa-plus icon-right'></i></a><br /><br /><br /><br /></div>";
        }

        else
        {
          $html .= "<div class='no-images text-center'><br /><br /><br />This user has not uploaded any images :(<br /><br /><br /><br /></div>";
        }
      }

      else
      {
        $html .= "<div class='no-images text-center'><br /><br /><br />Cannot find any images<br /><br /><br /><br /></div>";
      }
    }

    return $html;
  }




  /**
   * Returns results found string based on result rows
   *
   * @param int $rows
   */
  public function imgResultsFound($rows)
  {
    $html = '';

    if ($rows > 0)
    {
      if ($rows == 1)
      {
        $html = 'We found <strong>1</strong> result';
      }

      else
      {
        $html = 'We found <strong>' . number_format($rows) . '</strong> results';
      }
    }

    return $html;
  }




  /**
   * Returns notification html for block & popup
   *
   * @param Notification $notif
   * @param boolean $block
   * @return string
   */
  public function notification($notif, $count = 0)
  {
    if (! $notif)
    {
      return '<li class="none first">You have no notifications</li>';
    }

    $_user = $notif->userFrom;
    $_image = $notif->image;

    switch ($notif->type)
    {
      case LOG_LIKED:
        $_text = $count > 1 ? $count . ' people liked your image' : '<strong>' . $_user->displayName . '</strong> liked your image ';
        return '
          <li>
            <a href="/capture/' . $_image->displayID . '" class="notification-link">
              <span class="is-new ' . (! $notif->hasRead() ? 'new' : '') . '"></span>
              <img src="' . $_image->thumbnails['custom'] . '" class="notif-image" />
              ' . $_text . '
            </a>
          </li>
        ';

        break;

      case LOG_RATED:
        return '
        <li>
          <a href="/capture/' . $_image->displayID . '" class="notification-link">
            <span class="is-new ' . (! $notif->hasRead() ? 'new' : '') . '"></span>
            <img src="' . $_image->thumbnails['custom'] . '" class="notif-image" />
            <strong>' . $_user->displayName . '</strong> rated your image
          </a>
        </li>
      ';

        break;

      case LOG_ACCEPTED:
        return '
          <li>
            <a href="/capture/' . $_image->displayID . '" class="notification-link">
              <span class="is-new ' . (! $notif->hasRead() ? 'new' : '') . '"></span>
              <i class="fa fa-check icon-left"></i>
              <strong>' . $_image->title . '</strong> has been accepted
            </a>
          </li>
        ';

        break;

      case LOG_REJECTED:
        return '
          <li>
            <a href="/capture/' . $_image->displayID . '" class="notification-link">
              <span class="is-new ' . (! $notif->hasRead() ? 'new' : '') . '"></span>
              <i class="fa fa-times icon-left"></i>
              <strong>' . $_image->title . '</strong> has been rejected
            </a>
          </li>
        ';
        break;
    }

    return false;
  }




  /**
   * Returns session timeout length in a string
   */
  public function timeoutString()
  {
    $is_minutes = (TIMEOUT_MINUTES / 60) < 1;
    $time = $is_minutes ? TIMEOUT_MINUTES : (TIMEOUT_MINUTES / 60);
    $timeout = round($time, 2);
    $timeout .= $is_minutes ? ' minute' : ' hour';
    $timeout .= ($time == 1) ? '' : 's';

    return $timeout;
  }
}