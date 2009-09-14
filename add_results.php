	
	<?php 
		// Processing for the option
		if($_POST['tr_hidden'] == 'Y') {
			//Form data sent
			$tr_number = $_POST['teamresults_number'];
			update_option('teamresults_number', $tr_number);
			$tr_color = $_POST['teamresults_color'];
			update_option('teamresults_color', $tr_color);
			?>
			<div class="updated"><p><strong><?php _e('Options saved.','TeamResults' ); ?></strong></p></div>
			<?php
		} else {
			//Normal page display
			$tr_number = get_option('teamresults_number');
			$tr_color = get_option('teamresults_color');
		}
		//Processing for the new result
		if($_POST['tr_hidden1'] == 'Y') {
			//Format the date
			$tmp = $_POST['result_date'];
			$date = substr($tmp, 6).substr($tmp, 2, 4).substr($tmp, 0, 2) ;
			//Form data sent
			global $wpdb;
			//TODO
			$table = $wpdb->prefix."tr_result";
			$req = "INSERT INTO $table (date, game_type, id_team, team_score, opponent, opponent_score, place) values ('".$date."', '".$_POST['game_type']."', ".$_POST['result_team1'].", 
			". $_POST['result_team1_score']." , '". $_POST['result_team2'] . "', ".$_POST['result_team2_score']. ", '".$_POST['result_location']."');";
			//TODO Test variable
			$wpdb->query($req);
			?>
			<div class="updated"><p><strong><?php _e('New result saved.' ,'TeamResults'); ?></strong></p></div>
			<?php
		}
		//Delete a result
		if($_POST['tr_hidden2'] == 'Y') {
		global $wpdb;
			$table = $wpdb->prefix."tr_result";
			$id = $_POST['tr_supp'];
			$req = "DELETE FROM $table where ".$table.".id = $id;";
			$wpdb->query($req);
			?>
			<div class="updated"><p><strong><?php _e('Result deleted.','TeamResults' ); ?></strong></p></div>
			<?php
		}
	?>



	<div class="wrap">
			<?php    echo "<h2>" . __( 'Team Results Display Options','TeamResults' ) . "</h2>"; ?>
			
			
			
			
			
			
			
			
			<form name="team_results_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="tr_hidden" value="Y">

			 <h4><?php _e('Team Results Settings','TeamResults');?></h4>
			
			
							<table class="form-table">
				<tbody>
				<tr valign="top">
				<th scope="row">
				<label for="result_date"><?php _e('Number of results displayed on the widget:','TeamResults');?> </label>
				</th>
				<td>
				<input id="teamresults_number" class="regular-text" type="text"  name="teamresults_number" value="<?php echo $tr_number; ?>"/><?php _e(" ex: 25/12/08",'TeamResults' ); ?>
				</td>
				</tr>
				
				
								<tr valign="top">
				<th scope="row">
				<label for="result_date"><?php _e('Show colors (green:victory, red:defeat)','TeamResults');?></label>
				</th>
				<td>
				<input id="teamresults_color"  type="checkbox"  name="teamresults_color" <?php if ($tr_color =="on") echo 'checked="checked"'; ?> />
				</td>
				</tr>
				
				</tbody>
				</table>
				
				<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options', 'TeamResults' ) ?>" />
				</p>
				</form>
			
			
			
			
			
			<form name="team_results_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="tr_hidden1" value="Y">
				
				<?php    echo "<h4>" . __( 'Add a new result', 'TeamResults' ) . "</h4>"; ?>
				
				

				
				<table class="form-table">
				<tbody>
				<tr valign="top">
				<th scope="row">
				<label for="result_date"><?php _e('Date (dd/mm/yy):', 'TeamResults' ) ?> </label>
				</th>
				<td>
				<input id="result_date" class="regular-text" type="text"  name="result_date"/><?php _e(" ex: 25/12/08", 'TeamResults' ); ?>
				</td>
				</tr>
				
				
				<tr valign="top">
				<th scope="row">
				<label for="result_game_type"><?php _e('Game type:', 'TeamResults' ) ?></label>
				</th>
				<td>
				<input id="game_type" class="regular-text" type="text"  name="game_type"/><?php _e(" ex: Championship, Tournament", 'TeamResults' ); ?>
				</td>
				</tr>
				
				
				<tr valign="top">
				<th scope="row">
				<label for="result_team"><?php _e('Team:', 'TeamResults' ) ?> </label>
				</th>
				<td>
				
				<?php
				
				global $wpdb;
			    $table = $wpdb->prefix."tr_team";
				$req= "SELECT * FROM $table order by name";
				$res = mysql_query($req);
				echo'<select name="result_team1" style="width:300px;">';
				while ($data = mysql_fetch_array($res) )
				{
				 ?>

				 <option value="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></option>

				 <?php
				 }

				echo'</select>'.__('If not there:', 'TeamResults').'<a href="admin.php?page=tr_team">'.__('Add a team', 'TeamResults').'</a>';
								
				
				?>
				
				
				
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row">
				<label for="result_team1_score"><?php _e('Team score:', 'TeamResults' ) ?></label>
				</th>
				<td>
				<input id="result_team1_score" class="regular-text" type="text"  name="result_team1_score"/>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row">
				<label for="result_team2"><?php _e('Opponent: ', 'TeamResults' ) ?></label>
				</th>
				<td>
				<input id="result_team2" class="regular-text" type="text"  name="result_team2"/>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row">
				<label for="result_team2_score"><?php _e('Opponent score: ', 'TeamResults' ) ?> </label>
				</th>
				<td>
				<input id="result_team2_score" class="regular-text" type="text"  name="result_team2_score"/>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row">
				<label for="result_team2_score"><?php _e('Place of the Match: ', 'TeamResults' ) ?> </label>
				</th>
				<td>		      
				   <input type="radio" name="result_location" value="H" id="H" /> <label for="H"><?php _e('Home (H) ', 'TeamResults' ) ?></label><br />
				   <input type="radio" name="result_location" value="O" id="O" /> <label for="O"><?php _e('Opponent place (O) ', 'TeamResults' ) ?></label><br />
				   <input type="radio" name="result_location" value="N" id="N" /> <label for="N"><?php _e('Neutral / Don\'t care (N)', 'TeamResults' ) ?></label><br />
				</td>
				</tr>
				
				
				
				
				



				
				
				
				
				
				</tbody>
				</table>
				
				

				<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Add Result', 'TeamResults' ) ?>" />
				</p>
			</form>
			
			
			
			

			 <h4><?php _e('Browsing the results', 'TeamResults' ) ?></h4>
			 

			 
			 

				<?php
				//Browse the results
				global $wpdb;
				$table = $wpdb->prefix."tr_result";	
				$table1= $wpdb->prefix."tr_team";
				$req = "SELECT *,".$table.".id as id_res  from $table, $table1 where ".$table.".id_team = ".$table1.".id order by DATE DESC;";
				//TODO Test variable
				$res = mysql_query($req);
				$number = mysql_num_rows($res); 
				if($number==0)
				{
					_e('No results for the moment','TeamResults' );
				}
				else
				{
				?>
			    <table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr><th></th><th><?php _e('Date', 'TeamResults' ) ?></th><th><?php _e('Game Type', 'TeamResults' ) ?></th><th><?php _e('Team', 'TeamResults' ) ?> </th><th><?php _e('Team score', 'TeamResults' ) ?></th><th><?php _e('Opponent', 'TeamResults' ) ?></th><th><?php _e('Opponent score', 'TeamResults' ) ?></th><th><?php _e('Location', 'TeamResults' ) ?></th></tr>
				</thead>
				<?php
					  while ($games = mysql_fetch_array($res) )
					{
						echo '<tr><td><form method="post" action='.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'><input type="hidden" name="tr_hidden2" value="Y">
						<input type="hidden" name="tr_supp" value="'.$games['id_res'].'">
						<input type="submit" name="Submit" value="'.__('Delete','TeamResults').'" />
						</form></td><td>';
						echo date("d/m/y", strtotime($games['date'])) .'</td><td>'. $games['game_type']. '</td><td>'. $games['name']. '</td><td>'. $games['team_score'].'</td><td>'. $games['opponent'].'</td><td>'. $games['opponent_score']. '</td><td>'. $games['place']. '</td></tr>';
					}
				?>
				
				<tfoot>
					<tr><th></th><th><?php _e('Date', 'TeamResults' ) ?></th><th><?php _e('Game Type', 'TeamResults' ) ?></th><th><?php _e('Team', 'TeamResults' ) ?> </th><th><?php _e('Team score', 'TeamResults' ) ?></th><th><?php _e('Opponent', 'TeamResults' ) ?></th><th><?php _e('Opponent score', 'TeamResults' ) ?></th><th><?php _e('Location', 'TeamResults' ) ?></th></tr>
				</tfoot>
			    </table>
				<?php
					
					
				}
				?>
				

			 
			 <br/>
			 <br/>
		</div>
		

