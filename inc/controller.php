<?php
	if( !defined('APP_VERSION') ) exit('You can\'t access this file');
	require_once('db_config.php');
	
	function verify_database_connection(){
		global $db, $db_config;
		$result = FALSE;
		try {
			if ( !empty($db_config) ) {
				extract($db_config);
				$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
				
				create_tables();
				$result = TRUE;
			}
		} catch (Exception $e) {
			$result = FALSE;
		}
		return $result;
	}
	
	function verify_database_binding( $query ){
		database_assign();
	}
	
	function verify_user_registration( $query ){
		unset($query['submit']);
		global $db;
		try {
			$stmt = $db->prepare("SELECT ID FROM users WHERE `name`= :name OR `email` = :email");
			$stmt->bindParam(':name', $query['user_name']);
			$stmt->bindParam(':email', $query['user_email']);
			$stmt->execute();
			$id = $stmt->fetchAll();
			$db = null;
		} catch (Exception $e) {
			echo getmessage();
		}
		if ( !empty($id) ) {
			echo "Name or email are already exist.";
		} else {
			register_user();
		}
	}
	
	function verify_user_login( $query ){
		unset($query['submit']);
		global $db;
		if ( !empty($query['user_name']) && !empty($query['user_pass']) ) {
			try {
				$stmt = $db->prepare("SELECT ID, pass FROM users WHERE `name`= :name");
				$stmt->bindParam(':name', $query['user_name']);
				$stmt->execute();
				$user = $stmt->fetchAll();
				$db = null;
			} catch (Exception $e) {
				echo getmessage();
			}
			if ( !empty($user) && (md5($query['user_pass']) == $user[0]['pass']) ) {
				unset($user[0]['pass']);
				setcookie("is_logged", true);
				setcookie("user_id", $user[0]['ID']);
				header( 'Refresh: 0' );
			}
		}
	}
	
	function logout(){
		setcookie("is_logged");
		setcookie("user_id");
		header( 'Location: ' . URL . '' );
	}
	
	function is_logged(){
		$result = FALSE;
		if ( !empty($_COOKIE['is_logged']) && $_COOKIE['is_logged'] == 1 ) {
			$result = TRUE;
		} else {
			$result = FALSE;
		}
		return $result;
	}
	
	if ( !function_exists( 'verify_update_user' ) ) {
		function verify_update_user(){
			if ( is_logged() ) {
				$user = get_user();
				update_user( $user['ID'], $_POST );
			}
		}
	}
	
	if ( !function_exists( 'verify_add_gain' ) ) {
		function verify_add_gain(){
			if ( is_logged() ) {
				$user = get_user();
				add_gain( $user['ID'], $_POST );
			}
		}
	}
	
	if ( !function_exists( 'verify_add_cost' ) ) {
		function verify_add_cost(){
			if ( is_logged() ) {
				$user = get_user();
				add_cost( $user['ID'], $_POST );
			}
		}
	}
	
	if ( !function_exists( 'verify_add_goal' ) ) {
		function verify_add_goal(){
			if ( is_logged() ) {
				$user = get_user();
				add_goal( $user['ID'], $_POST );
			}
		}
	}
	
	if ( !function_exists( 'verify_update_gain' ) ) {
		function verify_update_gain(){
			if ( is_logged() ) {
				update_gain( $_POST );
			}
		}
	}
	
	if ( !function_exists( 'verify_update_cost' ) ) {
		function verify_update_cost(){
			if ( is_logged() ) {
				update_cost( $_POST );
			}
		}
	}
	
	if ( !function_exists( 'verify_update_goal' ) ) {
		function verify_update_goal(){
			if ( is_logged() ) {
				update_goal( $_POST );
			}
		}
	}
	
	if ( !function_exists( 'verify_delete_cost' ) ) {
		function verify_delete_cost(){
			if ( is_logged() ) {
				delete_cost( $_GET['delete'] );
			}
		}
	}
	
	if ( !function_exists( 'verify_delete_goal' ) ) {
		function verify_delete_goal(){
			if ( is_logged() ) {
				delete_goal( $_GET['delete'] );
			}
		}
	}
	
	if ( !function_exists( 'verify_delete_gain' ) ) {
		function verify_delete_gain(){
			if ( is_logged() ) {
				delete_gain( $_GET['delete'] );
			}
		}
	}
?>
