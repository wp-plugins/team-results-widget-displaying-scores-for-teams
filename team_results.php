<?php
/*
Plugin Name: Team results
Plugin URI: http://didoune.fr/blog/team-results/
Description: Display results of teams playing sport or e-sport
Author: Thomas Lamey
Version: 1.2
Author URI: http://didoune.fr
*/


/*  Copyright 2011  Thomas Lamey  (email : lamey.thomas@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/





load_plugin_textdomain('TeamResults','wp-content/plugins/teamresults/lang');



function displayScores()
{
//Displaying only the last results
  global $wpdb;
  
  $table = $wpdb->prefix."tr_result";	
  $table1= $wpdb->prefix."tr_team";
  $req = "SELECT * from $table, $table1 where ".$table.".id_team = ".$table1.".id order by DATE DESC LIMIT ". get_option('teamresults_number');
  $res = mysql_query($req);
  $number = mysql_num_rows($res);    
  echo '<div id="team_results" style="display:block;">';
  $date=0;
  if($number == 0)
  {
	_e('No results for the moment','TeamResults');
  }
  else
  {
		echo '<ul>';
	   while ($games = mysql_fetch_array($res) )
		{
			
			$date = listScore($games, $date);
		}
	  echo '</ul>';  
	  $table = $wpdb->prefix."tr_result";	
	  $table1= $wpdb->prefix."tr_team";
      $req = "SELECT * from $table, $table1 where ".$table.".id_team = ".$table1.".id order by DATE DESC LIMIT ". get_option('teamresults_number').",6;";
	  $res = mysql_query($req);
	  $number = mysql_num_rows($res); 
	  if($number > 0)
		echo "<a href='javascript:visibility(\"Y\");' id='link_more' \>".__('More Results', 'TeamResults')."</a>";
	  echo "<div id='DivRes' style='display:none;'>";
	  echo '<ul>';
	  while ($games = mysql_fetch_array($res) )
	  {
			$date = listScore($games, $date);
	  }
	  echo "</ul>";
	  echo"<a href='javascript:openbox(1);'  id='link_pop' \>".__('View all results', 'TeamResults')."</a>";
	  echo "</div>";


	lightboxDiv();

  }
  
  echo '</div>';

}


//List a result with or without the date, the goal is not to repeat the date twice
function listScore($games, $date)
{
	if($date == date("d/m/y", strtotime($games['date'])))
	{
		//Display in first the name of the team where the game was played, if it's neutral: first our team
		if($games['place']=='O') //opponent place
			echo "<li><a href='javascript:openbox(1);'>". $games['opponent']."  <span class='score'>".  $games['opponent_score']."-". $games['team_score']. "</span> ". $games['name']."<br/>(".$games['game_type'].")</a></li>";		
		else
			echo "<li><a href='javascript:openbox(1);'>". $games['name']."  <span class='score'>".  $games['team_score']."-". $games['opponent_score']. "</span> ". $games['opponent']."<br/>(".$games['game_type'].")</a></li>";
	}
	else
	{
		$date= date("d/m/y", strtotime($games['date']));
		if($games['place']=='O') //opponent place
			echo "<br/><h4>". $date ."</h4><br/><li><a href='javascript:openbox(1);'>". $games['opponent']." <span class='score'> ".  $games['opponent_score']."-". $games['team_score']. "</span> ". $games['name']."<br/>(".$games['game_type'].")</a></li>";
		else
			echo "<br/><h4>". $date ."</h4><br/><li><a href='javascript:openbox(1);'> ". $games['name']." <span class='score'>".  $games['team_score']."-". $games['opponent_score']. "</span> ". $games['opponent']."<br/>(".$games['game_type'].")</a></li>";
	}
	return $date;
}



// Content of the lightbox
function lightboxDiv()
{
	include "lightbox.php";
}
// Content for the filter
function filterDiv()
{
	global $wpdb;
	$resFunc="";
	$table = $wpdb->prefix."tr_result";	
	$table1= $wpdb->prefix."tr_team";
	$req = "SELECT * from $table, $table1 where ".$table.".id_team = ".$table1.".id order by DATE DESC;";
	$res = mysql_query($req);
	$number = mysql_num_rows($res); 
	if($number==0)
	{
		$resFunc.=__('No results for the moment','TeamResults');
	}
	else
	{ 
		$resFunc.=' <table style="font-size:7.5pt;">
		<tr><td>'.__( 'Date','TeamResults').'</td><td>'.__( 'Game Type','TeamResults').'</td><td>'.__( 'Team','TeamResults').'</td><td>'
		.__( 'Team score','TeamResults').'</td><td>'. __( 'Opponent score','TeamResults').'</td><td>'.__( 'Opponent','TeamResults').'</td><td>'.__( 'Location','TeamResults').'</td></tr>';

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
				//victory: 
				if($games['team_score'] > $games['opponent_score'] )
					$resFunc.= '<tr style="background-color: #'.get_option('teamresults_color_victory').';">';
				//defeat: 
				if($games['team_score'] < $games['opponent_score'] )
					$resFunc.= '<tr style="background-color: #'.get_option('teamresults_color_defeat').';">';
				//egality: white
				if($games['team_score'] == $games['opponent_score'] )
					$resFunc.= '<tr>';
			}
			else
				$resFunc.= '<tr>';
			$resFunc.= '<td>';
			$resFunc.= date("d/m/y", strtotime($games['date'])) .'</td><td>'. $games['game_type']. '</td><td>'. $games['name']. '</td><td style="text-align: center;">'. $games['team_score'].'</td><td style="text-align: center;">'. $games['opponent_score'].'</td><td>'. $games['opponent']. '</td><td>'. $place. '</td></tr>';	
		}
		$resFunc.='</table>';
	}
	return $resFunc;
}


//Content of the widget
function widget_teamresults($args) {
  extract($args);
  echo $before_widget;
  echo $before_title;
  include "misc.php";
  echo get_option('teamresults_title');
  echo $after_title;
  displayScores();
  echo $after_widget;
}

//Widget options
function widget_teamresults_control() {
	//Removed: widget options are now coming from the admin page
	//include('team_results_admin.php');  
}






//Initialisation function
function teamresults_init()
{
  register_sidebar_widget(__('Team Results', 'TeamResults'), 'widget_teamresults');    
  register_widget_control(__('Team Results', 'TeamResults'), 'widget_teamresults_control', $width = '', $height = '');
}

//Activation function
function teamresults_activation()
{
  //Creation of the tables
    global $wpdb;
    $table = $wpdb->prefix."tr_result";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        id_team int(9) NOT NULL,
		team_score INT(9) DEFAULT 0,
        opponent VARCHAR(80) NOT NULL,
		opponent_score INT(9) DEFAULT 0,
		game_type VARCHAR(80) DEFAULT 0,
		place VARCHAR(2) DEFAULT 0,
		date DATETIME,       
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
	$table = $wpdb->prefix."tr_team";
	$structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        name VARCHAR(80) NOT NULL,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
}

/************************************
*	Administration page
*************************************/

//Administration menu: Create a New menu
function teamresults_admin_actions() {  


    add_menu_page('Team Result', 'Team Result', 1, __FILE__, 'tr_general');

    // Add a submenu to the custom top-level menu:
    add_submenu_page(__FILE__, 'Team Management', 'Team Management', 1, 'tr_team', 'tr_team');


} 


function tr_general() {
	include('add_results.php');  
}
function tr_team() {
 	include('add_team.php'); 
}

//Filter function to replace specific tag [team_results] with the widget content on a post or page
function filter_teamresult($content) {
	
	if(stripos($content, '[team_results]')>0)
	{
		$tab=filterDiv();
		$content=str_replace('[team_results]',$tab,$content);
	}
    return $content;
}

 
 /******************************
 * Hooks
 ********************************/
 
 //for the admin menu
 add_action('admin_menu', 'teamresults_admin_actions');  

//initialisation of the plugin (done each time)
add_action("plugins_loaded", "teamresults_init");

//activation of the plugin
register_activation_hook(__FILE__, 'teamresults_activation');

//For the filter
add_filter ( 'the_content', 'filter_teamresult' );

?>