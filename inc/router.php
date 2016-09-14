<?php
	if( !defined( 'APP_VERSION') ) exit('You can\'t access this file');

	require_once('core_functions.php');
	require_once('controller.php');
	
	function app(){
		if ( verify_database_connection() ) {
			$action = 'default';
			if ( !empty($_POST) ) {
				$action = $_POST['submit'];
				switch ($action) {
					case 'Register':
					verify_user_registration( $_POST );
					break;
					
					case 'Login':
					verify_user_login( $_POST );
					break;
					
					case 'Update Profile':
					verify_update_user();
					break;
					
					case 'Add Gain':
					verify_add_gain();
					break;
					
					case 'Add Cost':
					verify_add_cost();
					break;
					
					case 'Add Goal':
					verify_add_goal();
					break;
					
					case 'Update Gain':
					verify_update_gain();
					break;
					
					case 'Update Cost':
					verify_update_cost();
					break;
					
					case 'Update Goal':
					verify_update_goal();
					break;
					
					default:
					break;
				}
			}
			if ( !is_logged() ) {
				get_header( 'echo' );
				echo "<main id='main' class='text-center container-fluid dark-bg'>";
					echo "<div class='container'>";
						echo "<div class='row'>";
							template( 'login_form', 'echo' );
							template( 'register_form', 'echo' );
						echo "</div>";
					echo "</div>";
				echo "</main>";
				get_footer( 'echo' );
			} elseif ( !empty( $_GET['action'] ) ) {
				switch ( $_GET['action'] ) {
					case 'my_profile':
						template( 'my_profile', 'echo' );
						break;
					case 'add_gain':
						template( 'add_gain_form', 'echo' );
						break;
					case 'gains':
						template( 'gains_table', 'echo' );
						break;
					case 'add_cost':
						template( 'add_cost_form', 'echo' );
						break;
					case 'costs':
						template( 'costs_table', 'echo' );
						break;
					case 'add_goal':
						template( 'add_goal_form', 'echo' );
						break;
					case 'goals':
						template( 'goals_table', 'echo' );
						break;
					
					default:
					template( 'my_profile', 'echo' );
						break;
				}
			} else {
				template( 'homepage', 'echo' );
			}
		} else {
			if ( !empty($_POST) && $_POST['submit'] == 'Assign Database' ) {
				verify_database_binding( $_POST );
			} else {
				template( 'database_connection_form', 'echo' );
			}
		}
	}
