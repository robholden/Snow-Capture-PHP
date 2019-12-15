

<header>
  <div class="container header-container">
    <div id="logo">
      <h1>
        <a href="/" title="Snow Capture" class="main-link" tabindex="1">
          Snow Capture               
        </a>
      </h1>
      
      <div class="loading">
        <div class="loader">
          <div class="loader-inner ball-scale-ripple">
            <div></div>
          </div>
        </div>
      </div>
    </div>

    <a href="#" id="toggle-nav" tabindex="3" class="<?php if($sc->session->exists()) { if ($sc->user->status < 0) { echo 'hidden'; } } ?>">
      <i class="fa fa-bars is-closed fa-2x"></i>
      <i class="fa fa-times is-opened fa-2x"></i>
    </a>

  <?php if($sc->session->exists()): ?>
    
    <div id="notification" class="margin-right-xl">
      <div class="sc-dropdown-container" data-dir-right>
        <a href="#" id="open-notifications" class="main-link" data-dropdown="#notification-dashboard" data-tooltip data-placement="bottom" data-delay="500" title="Notifications" tabindex="5">
           <span><i class="fa fa-bell-o fa-2x"></i><i class="fa fa-bell fa-2x"></i></span> <span id="notification-count">0</span>
        </a>
        <ul id="notification-dashboard" class="sc-dropdown notification-dashboard animate" data-width="250"></ul>
			</div>
    </div><!-- .btn-group --> 
    
  <?php endif; ?>

  </div><!-- .container -->
</header>




<nav id="mobile-nav">
  <div class="header-search">
    <form id="search-form" class="form" action="/search">
      <div id="keyword-holder">
        <input id="header-search-input" class="search-input"  value="<?php echo $sc->common->keyword() ? $sc->common->keyword() : ''; ?>" type="text" name="q" placeholder="Search something..." maxlength="50" tabindex="2" required />
      </div><!-- #keyword-holder -->
    </form>
  </div><!-- .header-search -->

  <ul class="mobile-links">
    <li>
      <a href="/" class='notification-link'>
        <span>Home</span>          
        <i class="fa fa-home icon-right"></i>
      </a>
    </li>
  	<li>
      <a href="/search" class='notification-link'>
        <span>Search</span>          
        <i class="fa fa-search icon-right"></i>
      </a>
    </li>
    <li>
      <a href="/map" class='notification-link'>
        <span>Map</span>          
        <i class="fa fa-map-marker icon-right"></i>
      </a>
    </li>
    
<?php if($sc->session->exists()): ?>
<?php if($sc->user->status > 0): ?>
  <?php include('user_links.php'); ?>    
<?php endif; ?>
<?php else: ?>
    <li>
      <a href="/sign-in?url=<?php echo "$_SERVER[REQUEST_URI]"; ?>" class="logout notification-link">
        <span>Sign In</span>
        <i class="fa fa-sign-in icon-right"></i>
      </a>
    </li> 
    
    <li>
      <a href="/sign-up" class="notification-link">
        <span>Sign Up</span>
        <i class="fa fa-edit icon-right"></i>
      </a>
    </li>
<?php endif; ?>
  </ul>
</nav>