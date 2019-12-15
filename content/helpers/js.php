<script src="/js/min/jquery-1.12.0.min.js"></script>
<script>(function(h){h.className = h.className.replace('no-js', 'js')})(document.documentElement)</script>
<script>
	var vars = {
		mobile: <?php echo $sc->common->isMobile ? 'true' : 'false'; ?>,
  	debug: <?php echo DEVELOPMENT < 2 ? 'true' : 'false'; ?>
	}    

// 	window.onerror = function (errorMsg, url, lineNumber) {
//     alert('Error: ' + errorMsg + ' Script: ' + url + ' Line: ' + lineNumber);
//   }  
</script>

<script src="/js/min/sc.fun.min.js"></script>
<script>
  $(document).ready(function() { snow.init() });
</script>
  