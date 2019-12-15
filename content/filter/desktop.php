<?php // We have acces to the $_filter object from image-dsplay.php ?>

<?php 

$_showFilter = $sc->common->filterSet();

?>

<div id="filter-form" class="container padding-bottom-none margin-top" <?php echo $_filter->total == 0 && !$_showFilter ? 'style="display: none;"' : ''; ?>>
  <h3 class="pull-left margin-bottom"> <a href="#" class="filter-toggle <?php echo $_showFilter ? 'opened' : '' ?>" data-session="<?php echo $sc->common->filterSet() ? 'true' : ''; ?>">FILTER <i class="fa fa-angle-down"></i></a> </h3>
  <div class="pull-left margin-left">      
    <a href="#" class="clear-filters">
      <code>Reset Filter</code>
    </a>
  </div>      
  
  <div class="pull-right">
    <?php include(WEB_ROOT . 'content/filter/sortby.php'); ?>
  </div>
  
  <div id="filter-holder" <?php echo !$_showFilter ? 'style="display: none;"' : ''; ?>><?php include('content.php'); ?></div>
</div><!-- #filter-form -->