<div class="wrap">
<?php
		//Processing for the new result
		if($_POST['tr_hidden'] == 'Y') 
		{
			//Form data sent
			global $wpdb;
			//TODO
			$table = $wpdb->prefix."tr_team";
			$req = "INSERT INTO $table (name) values ('".$_POST['team_name']."');";
			//TODO Test variable
			$wpdb->query($req);
			?>
			<div class="updated"><p><strong><?php _e('New team created.','TeamResults' ); ?></strong></p></div>
			<?php
		}
		// Deleting a team
		if($_POST['tr_hidden1'] == 'Y') 
		{
			global $wpdb;
			$table = $wpdb->prefix."tr_team";
			$id = $_POST['tr_supp'];
			$req = "DELETE FROM $table where id = $id;";
			$wpdb->query($req);
			$table = $wpdb->prefix."tr_result";
			$req = "DELETE FROM $table where id_team = $id;";
			$wpdb->query($req);
			?>
			<div class="updated"><p><strong><?php _e('Team and results deleted.','TeamResults' ); ?></strong></p></div>
			<?php
		}



	echo "<h2>" .  __('Team Management','TeamResults') . "</h2>";
	echo "<h4>" . __('Add a Team','TeamResults') . "</h4>"; 
	?>
	
				<form name="team_results_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="tr_hidden" value="Y">
				<p><?php _e("Name of the team: ",'TeamResults' ); ?><input type="text" name="team_name" value="" size="30"></p>

			
				<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Create team','TeamResults'); ?>" />
				</p>
			</form>
	
<h4><?php _e('Browsing the teams','TeamResults'); ?></h4>
			 

			 
			 

				<?php
				//Browse the teams
				global $wpdb;
				$table = $wpdb->prefix."tr_team";	
				$req = "SELECT * from $table order by name;";
				//TODO Test variable
				$res = mysql_query($req);
				$number = mysql_num_rows($res); 
				if($number==0)
				{
					_e( 'No team for the moment','TeamResults');
				}
				else
				{
				?>
			    <table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr><th></th><th><?php _e( 'Team name','TeamResults'); ?></th></tr>
				</thead>
				<?php
					  while ($data = mysql_fetch_array($res) )
					{
						echo '<tr><td><form method="post" action='.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'><input type="hidden" name="tr_hidden1" value="Y">
						<input type="hidden" name="tr_supp" value="'.$data['id'].'">
						<input type="submit" name="Submit" value="'. __('Delete team and all the corresponding results','TeamResults').'" />
						</form></td><td>';
						echo  $data['name']. '</td>';
					}
				?>
				
				<tfoot>
					<tr><th></th><th><?php _e( 'Team name','TeamResults'); ?></th></tr>
				</tfoot>
			    </table>
				<?php
					
					
				}
				?>
	
</div>
			