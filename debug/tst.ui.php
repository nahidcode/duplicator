
<div class="section-tests">
	<div class="section-hdr">UI CTRLS</div>

	<!-- METHOD TEST -->
	<script>
		//value param must be dynamic with each submit
		function JS_DUP_CTRL_UI_SaveViewState(form) 
		{
			jQuery(form).find('input[name="value"]').val(new Date(jQuery.now()));
			return false;
		}
	</script>
	<form onsubmit="return JS_DUP_CTRL_UI_SaveViewState(this);">
		<?php 
			$CTRL['Title']  = 'DUP_CTRL_UI_SaveViewState';
			$CTRL['Action'] = 'DUP_CTRL_UI_SaveViewState'; 
			$CTRL['Test']	= true;
			DUP_DEBUG_TestSetup($CTRL); 
		?>
		
		<div class="params">
			<label>Key:</label>
			<input type="text" name="key" value="dup_ctrl_test" /><br/>
			<label>Value:</label> 
			<input type="text" name="value"  /> <br/>
		</div>
	</form>
	

</div>
