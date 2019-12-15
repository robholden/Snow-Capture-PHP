<div id="filter-container" class="posh none">
  <div class="container">      
    <a href="#" id="toggle-filter" class="toggle-filter pull-left" data-session="<?php echo $sc->common->filterSet() ? 'true' : ''; ?>">
      <i class="fa fa-filter fa-2x"></i>
    </a>

    <div class="pull-right">
      <?php include(WEB_ROOT . 'content/filter/sortby.php'); ?>
    </div>    

    <br><!--<span id="number-results"></span> -->
  </div><!-- .container -->
</div><!-- #filter-container -->