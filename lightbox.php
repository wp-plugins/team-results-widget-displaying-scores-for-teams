
	<div id="filter"></div>
	<div id="box">
	<span id="boxtitle"></span>
	<a href="#"  onclick="closebox()" style='float:right;'><?php _e( 'Close (X)','TeamResults'); ?></a>
	<h2><?php _e( 'All the results','TeamResults'); ?></h2>

<?php



				global $wpdb;
				$table = $wpdb->prefix."tr_result";	
				$table1= $wpdb->prefix."tr_team";
				$req = "SELECT * from $table, $table1 where ".$table.".id_team = ".$table1.".id order by DATE DESC;";
				$res = mysql_query($req);
				$number = mysql_num_rows($res); 
				if($number==0)
				{
					_e('No results for the moment','TeamResults');
				}
				else
				{
				?>
			    <table class="lightbox" cellspacing="1" id="lightbox_tab">
				<thead>
					<tr><th><?php _e( 'Date','TeamResults'); ?></th><th><?php _e( 'Game Type','TeamResults'); ?></th><th><?php _e( 'Team','TeamResults'); ?></th><th><?php _e( 'Team score','TeamResults'); ?></th><th><?php _e( 'Opponent score','TeamResults'); ?></th><th><?php _e( 'Opponent','TeamResults'); ?></th><th><?php _e( 'Location','TeamResults'); ?></th></tr>
				</thead>
				<?php
				$tr_color = get_option('teamresults_color');	
					  while ($games = mysql_fetch_array($res) )
					{
						switch ($games['place']) {
							case 'O':
								$place=__('Outside','TeamResults');
								break;
							case 'H':
								$place=__('Home','TeamResults');
								break;
							case 'N':
								$place=__('Neutral','TeamResults');
								break;
							}
						if ($tr_color=='on')
						{
							//victory: green
							if($games['team_score'] > $games['opponent_score'] )
								echo '<tr style="background-color: #A9F5A9;">';
							//defeat: red
							if($games['team_score'] < $games['opponent_score'] )
								echo '<tr style="background-color: #F5A9A9;">';
							//egality: normal
							if($games['team_score'] == $games['opponent_score'] )
								echo '<tr>';
						}
						else
							echo '<tr>';
						echo '<td>';
						echo date("d/m/y", strtotime($games['date'])) .'</td><td>'. $games['game_type']. '</td><td>'. $games['name']. '</td><td style="text-align: center;">'. $games['team_score'].'</td><td style="text-align: center;">'. $games['opponent_score'].'</td><td>'. $games['opponent']. '</td><td>'. $place. '</td></tr>';	
					}
						?>
				

			    </table


	<?php
	

				}
	?>
</div>

		<script language="javascript" type="text/javascript">

	
		var table1Filters = {
			col_0: "none",
			col_1: "select",
			col_2: "select",
			col_3: "none",
			col_4: "none",
			col_5: "none",
			col_6: "none"
		}
		setFilterGrid("lightbox_tab",table1Filters);

	
	
	</script>
		
		