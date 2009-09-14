	
	<?php 
		if($_POST['tr_hidden'] == 'Y') {
			//Form data sent
			$tr_number = $_POST['teamresults_number'];
			$tr_title = $_POST['teamresults_title'];
			update_option('teamresults_number', $tr_number);
			update_option('teamresults_title', $tr_title);
		} else {
			//Normal page display
			$tr_number = get_option('teamresults_number');
			$tr_title = get_option('teamresults_title');
		}
	?>
	
			<form name="team_results_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="tr_hidden" value="Y">
				<p><?php _e("Title: ",'TeamResults' ); ?><input type="text" name="teamresults_title" value="<?php echo $tr_title; ?>" size="20"></p>
				<p><?php _e("Number of results displayed: ",'TeamResults' ); ?><input type="text" name="teamresults_number" value="<?php echo $tr_number; ?>" size="20"><?php _e(" ex: 5" ,'TeamResults'); ?></p>

			</form>


	
	