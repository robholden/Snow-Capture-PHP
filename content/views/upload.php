<div class="modal fade" id="ImageTermsModal" tabindex="-1" role="dialog" aria-labelledby="ImageTerms" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
  			<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal">
        		<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
        	</button>
        	<h3 class="pull-left">Start Uploading</h3>
        	<div class="clear"></div>
        </div>
        
  			<div class="modal-body normal-content">
  				<p>
  					Before you start uploading, please read our uploading guide. The more familiar you are with our process, the more
  					likely your pictures will be accepted!
  				</p>
					<p class="margin-top">
						By default we store any embedded geolocation information stored against your picture.
						You can turn this off in settings -> <a href="/<?php echo $sc->user->username; ?>/settings/preferences">preferences</a>.
					</p>
  			</div><!-- .modal-body -->
				
				<div class="modal-footer">
    			<a href='/how-it-works' class='button button-info pull-left'>Learn More</a>
    			<a href="#" id="image-terms-agree" data-dismiss="modal" class="button button-primary pull-right">
    				Start Uploading
    			</a>
					<div class="clear"></div>
  			</div><!-- .modal-footer -->
  	</div><!-- .modal-content -->
  </div><!-- .modal-dialog -->
</div><!-- #ImageTermsModal -->

<?php 

$fileTypes = array();
foreach (explode('/', ACCEPT_FILE_TYPES) as $key => $value) { array_push($fileTypes, 'image/' . $value); }

?>
<form id="form-upload" class="hidden">
  <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="image_uploading" />
  <input id="upload-image" accept="<?php echo implode(',', $fileTypes); ?>" type="file" name="images[]" data-limit="<?php echo $sc->user->limits->drafts; ?>" data-count="<?php echo ($sc->user->limits->drafts - $prevcount); ?>" <?php echo ALLOW_MULTIPLEUPLOAD ? 'multiple="multiple"' : ''; ?>/>
</form>