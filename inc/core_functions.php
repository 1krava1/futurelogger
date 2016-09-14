<?php
	if( !defined('APP_VERSION') ) exit('You can\'t access this file');
	define( 'TEMPLATEPATH', dirname(__FILE__) . '/templates/' );
	define( 'SOURCEPATH', dirname(__FILE__) . '/../src/' );
	define( 'URL', "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . '?') . '/' );
	
	function template( $template_name = '', $action = 'return' ){
		$page_content = require_once( TEMPLATEPATH . $template_name . '_template.php' );
		if ( $action == 'return' ) {
			return $page_content;
		} elseif ( $action == 'echo' ) {
			echo $page_content;
		}
	}
  
  if ( !function_exists('get_header') ) {
    function get_header( $action ){
      $page_content = require_once( TEMPLATEPATH . 'header_template.php' );
  		if ( $action == 'return' ) {
  			return $page_content;
  		} elseif ( $action == 'echo' ) {
  			echo $page_content;
  		}
    }
  }
  
  if ( !function_exists('get_footer') ) {
    function get_footer( $action ){
      $page_content = require_once( TEMPLATEPATH . 'footer_template.php' );
  		if ( $action == 'return' ) {
  			return $page_content;
  		} elseif ( $action == 'echo' ) {
  			echo $page_content;
  		}
    }
  }
	
	function database_assign(){
		unset( $_POST['submit'] );
		extract( $_POST );
		$messages = array( 'success' => array() ,'errors' => array(), );
		if ( !isset( $db_host ) || empty( $db_host ) ) $messages['errors'][] = 'Database host field is empty.';
		if ( !isset( $db_name ) || empty( $db_name ) ) $messages['errors'][] = 'Database name field is empty.';
		if ( !isset( $db_user ) || empty( $db_user ) ) $messages['errors'][] = 'Database user field is empty.';
		if ( !empty( $messages['errors'] ) ) {
			foreach ($messages['errors'] as $errors => $error){
				echo "<pre>";
				print_r($error);
				echo "</pre>";
			}
		} else {
			$db_file = fopen( dirname(__FILE__) . '/db_config.php', 'w+');
			fwrite( $db_file, '<' . '?php' . PHP_EOL );
			fwrite( $db_file, 'if( !defined(\'APP_VERSION\') ) exit(\'Access denied.\');' . PHP_EOL );
			fwrite( $db_file, '	$db_config = array(' . PHP_EOL);
			fwrite( $db_file, '				' . '\'db_host\' => ' . '\'' . $db_host . '\',' . PHP_EOL );
			fwrite( $db_file, '				' . '\'db_name\' => ' . '\'' . $db_name . '\',' . PHP_EOL );
			fwrite( $db_file, '				' . '\'db_user\' => ' . '\'' . $db_user . '\',' . PHP_EOL );
			fwrite( $db_file, '				' . '\'db_pass\' => ' . '\'' . $db_pass . '\',' . PHP_EOL );
			fwrite( $db_file, '			);' . PHP_EOL );
			fwrite( $db_file, '?' . '>' );
			fclose( $db_file );
			header( 'Refresh: 0' );
			exit;
		}
	}
	
	function create_tables(){
		global $db, $db_name;

		$users_columns = "ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY, name VARCHAR( 50 ) NOT NULL, email VARCHAR( 50 ), pass VARCHAR( 50 )" ;
		$create_users_table = $db->exec("CREATE TABLE IF NOT EXISTS " . $db_name . ".users ($users_columns)");
		
		$user_info_columns = "ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY, first_name VARCHAR( 50 ) NOT NULL, last_name VARCHAR( 50 )" ;
		$create_user_info_table = $db->exec("CREATE TABLE IF NOT EXISTS " . $db_name . ".user_info ($user_info_columns)");
		
		$gains_columns = "ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY, user_id INT( 11 ), name VARCHAR( 50 ) NOT NULL, date DATETIME, value INT, note LONGTEXT";
		$create_gains_table = $db->exec("CREATE TABLE IF NOT EXISTS " . $db_name . ".gains ($gains_columns)");
		
		$costs_columns = "ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY, user_id INT( 11 ), name VARCHAR( 50 ) NOT NULL, value INT, quantity INT, date DATETIME, note LONGTEXT";
		$create_costs_table = $db->exec("CREATE TABLE IF NOT EXISTS " . $db_name . ".costs ($costs_columns)");
		
		$goals_columns = "ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY, user_id INT( 11 ), name VARCHAR( 50 ) NOT NULL, value INT, quantity INT, date DATETIME, note LONGTEXT";
		$create_goals_table = $db->exec("CREATE TABLE IF NOT EXISTS " . $db_name . ".goals ($goals_columns)");
	}
	
	function register_user( $user_data = array() ){
		global $db_config;

		extract($db_config);
		$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
		try {
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $db->prepare("INSERT INTO users (name, email, pass) VALUES (:name, :email, :pass)");
			$pass = md5($_POST['user_pass']);
			$stmt->bindParam(':name', $_POST['user_name']);
			$stmt->bindParam(':email', $_POST['user_email']);
			$stmt->bindParam(':pass', $pass);
			$stmt->execute();
		} catch (Exception $e) {
			die('Register user error, sorry.');
		}
		try {
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $db->prepare("INSERT INTO user_info (id, first_name, last_name) VALUES (:id, :first_name, :last_name)");
			$user_id = $db->lastInsertId();
			$stmt->bindParam(':id', $user_id);
			$stmt->bindParam(':first_name', $_POST['user_first_name']);
			$stmt->bindParam(':last_name', $_POST['user_last_name']);
			$stmt->execute();
		} catch (Exception $e) {
			die('Register user error, sorry.');
		}
	}
	
	function connect_style( $file_name ){
    if ( is_array($file_name) ) {
      foreach ($file_name as $file_names => $file_name) {
    		$file_link = SOURCEPATH . 'css/' . $file_name;
				$styles = file_get_contents($file_link);
				$styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
				$styles = str_replace(': ', ':', $styles);
				$styles = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $styles);
    		echo '<style media="screen" id="' . $file_name . '">' . $styles . '</style>';
      }
    } else {
      $file_link = SOURCEPATH . 'css/' . $file_name;
			$styles = file_get_contents($file_link);
			$styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
			$styles = str_replace(': ', ':', $styles);
			$styles = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $styles);
			echo '<style media="screen" id="' . $file_name . '">' . file_get_contents($styles) . '</style>';
    }
	}
	
	if ( !function_exists('get_user') ) {
		function get_user( $user_id = '' ){
			global $db_config;
	
			if ( !empty( $_COOKIE['user_id'] ) ) {
				extract($db_config);
				$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
				try {
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $db->prepare("SELECT * FROM users WHERE ID = :user_id LIMIT 1");
					$stmt->bindParam(':user_id', $_COOKIE['user_id']);
					$stmt->execute();
					$result_user = $stmt->fetchAll();
				} catch (Exception $e) {
					die($e);
				}
				try {
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $db->prepare("SELECT * FROM user_info WHERE ID = :user_id LIMIT 1");
					$stmt->bindParam(':user_id', $_COOKIE['user_id']);
					$stmt->execute();
					$result_user_info = $stmt->fetchAll();
				} catch (Exception $e) {
					die($e);
				}
				return $result_user_info[0] + $result_user[0];
			}
		}
	}
	
	if ( !function_exists('update_user') ) {
		function update_user( $user_id = '', $data = array() ){
			global $db_config;
			extract($db_config);
			if ( !empty( $user_id ) && ( !empty( $data ) && is_array( $data ) ) ) {
				$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
				unset($data['submit']);
				foreach ($data as $key => $value) {
					if ( empty($value) ) {
						unset($data[$key]);
					} else {
						$data[$key] = htmlspecialchars($data[$key]);
					}
				}
				//users table
				$users_fields = array();
				if ( !empty( $data['name'] ) ) {
					$users_fields['name'] = 'name = :name';
				}
				if ( !empty( $data['email'] ) ) {
					$users_fields['email'] = 'email = :email';
				}
				if ( !empty( $data['pass'] ) ) {
					$users_fields['pass'] = 'pass = :pass';
				}
				$users_fields_string = implode($users_fields, ', ');
				$query = "UPDATE users SET $users_fields_string WHERE ID = :user_id";
				$stmt = $db->prepare($query);
				foreach ($users_fields as $key => $value) {
					$bind = ':' . $key;
					$stmt->bindParam($bind, $data[$key], PDO::PARAM_STR);
				}
				$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
				$stmt->execute();
				//user_info table
				$user_info_fields = array();
				if ( !empty( $data['first_name'] ) ) {
					$user_info_fields['first_name'] = 'first_name = :first_name';
				}
				if ( !empty( $data['last_name'] ) ) {
					$user_info_fields['last_name'] = 'last_name = :last_name';
				}
				$user_info_fields_string = implode($user_info_fields, ', ');
				$query = "UPDATE user_info SET $user_info_fields_string WHERE ID = :user_id";
				$stmt = $db->prepare($query);
				foreach ($user_info_fields as $key => $value) {
					$bind = ':' . $key;
					$stmt->bindParam($bind, $data[$key], PDO::PARAM_STR);
				}
				$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
	}
	
	if ( !function_exists( 'add_gain' ) ) {
		function add_gain( $user_id = '', $data = array() ){
			global $db_config;
			extract($db_config);
			if ( !empty( $user_id ) && ( !empty( $data ) && is_array( $data ) ) ) {
				$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
				unset($data['submit']);
				try {
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $db->prepare("INSERT INTO gains (user_id, name, date, value, note) VALUES (:user_id, :name, :date, :value, :note)");
					$stmt->bindParam(':user_id', $user_id);
					$stmt->bindParam(':name', $data['name']);
					$stmt->bindParam(':date', $data['date']);
					$stmt->bindParam(':value', $data['value']);
					$stmt->bindParam(':note', $data['note']);
					$stmt->execute();
					header("Location: ?action=gains");
				} catch (Exception $e) {
					die('Add gain error, sorry.');
				}
			}
		}
	}
	
	if ( !function_exists( 'get_gains' ) ) {
		function get_gains( $user_id = '' ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			
			if ( empty( $user_id ) ) {
				$user_id = get_user();
				$user_id = $user_id['ID'];
			}
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("SELECT * FROM gains WHERE user_id = :user_id ORDER BY date DESC");
				$stmt->bindParam(':user_id', $_COOKIE['user_id']);
				$stmt->execute();
				$result_gains = $stmt->fetchAll();
			} catch (Exception $e) {
				die($e);
			}
			return $result_gains;
		}
	}
	
	if ( !function_exists( 'get_gain' ) ) {
		function get_gain( $gain_id = '' ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			
			if ( empty( $user_id ) ) {
				$user_id = get_user();
				$user_id = $user_id['ID'];
			}
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("SELECT * FROM gains WHERE ID = :gain_id LIMIT 1");
				$stmt->bindParam(':gain_id', $gain_id);
				$stmt->execute();
				$result_gains = $stmt->fetchAll();
			} catch (Exception $e) {
				die($e);
			}
			return $result_gains;
		}
	}
	
	if ( !function_exists( 'update_gain' ) ) {
		function update_gain( $data = array() ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			unset($data['submit']);
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("UPDATE gains SET name = :name, date = :date, value = :value, note = :note WHERE ID = :id");
				$stmt->bindParam(':name', $data['name']);
				$stmt->bindParam(':date', $data['date']);
				$stmt->bindParam(':value', $data['value']);
				$stmt->bindParam(':note', $data['note']);
				$stmt->bindParam(':id', $data['gain_id']);
				$stmt->execute();
				header("Location: ?action=gains");
			} catch (Exception $e) {
				die('Add cost error, sorry.');
			}
		}
	}
	
	if ( !function_exists( 'add_cost' ) ) {
		function add_cost( $user_id = '', $data = array() ){
			global $db_config;
			extract($db_config);
			if ( !empty( $user_id ) && ( !empty( $data ) && is_array( $data ) ) ) {
				$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
				unset($data['submit']);
				try {
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $db->prepare("INSERT INTO costs (user_id, name, quantity, date, value, note) VALUES (:user_id, :name, :quantity, :date, :value, :note)");
					$stmt->bindParam(':user_id', $user_id);
					$stmt->bindParam(':name', $data['name']);
					$stmt->bindParam(':quantity', $data['quantity']);
					$stmt->bindParam(':date', $data['date']);
					$stmt->bindParam(':value', $data['value']);
					$stmt->bindParam(':note', $data['note']);
					$stmt->execute();
					header("Location: ?action=costs");
				} catch (Exception $e) {
					die('Add cost error, sorry.');
				}
			}
		}
	}
	
	if ( !function_exists( 'get_costs' ) ) {
		function get_costs( $user_id = '' ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			
			if ( empty( $user_id ) ) {
				$user_id = get_user();
				$user_id = $user_id['ID'];
			}
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("SELECT * FROM costs WHERE user_id = :user_id ORDER BY date DESC");
				$stmt->bindParam(':user_id', $_COOKIE['user_id']);
				$stmt->execute();
				$result_costs = $stmt->fetchAll();
			} catch (Exception $e) {
				die($e);
			}
			return $result_costs;
		}
	}
	
	if ( !function_exists( 'get_cost' ) ) {
		function get_cost( $cost_id = '' ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			
			if ( empty( $user_id ) ) {
				$user_id = get_user();
				$user_id = $user_id['ID'];
			}
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("SELECT * FROM costs WHERE ID = :cost_id LIMIT 1");
				$stmt->bindParam(':cost_id', $cost_id);
				$stmt->execute();
				$result_gains = $stmt->fetchAll();
			} catch (Exception $e) {
				die($e);
			}
			return $result_gains;
		}
	}
	
	if ( !function_exists( 'update_cost' ) ) {
		function update_cost( $data = array() ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			unset($data['submit']);
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("UPDATE costs SET name = :name, quantity = :quantity, date = :date, value = :value, note = :note WHERE ID = :id");
				$stmt->bindParam(':name', $data['name']);
				$stmt->bindParam(':quantity', $data['quantity']);
				$stmt->bindParam(':date', $data['date']);
				$stmt->bindParam(':value', $data['value']);
				$stmt->bindParam(':note', $data['note']);
				$stmt->bindParam(':id', $data['cost_id']);
				$stmt->execute();
				header("Location: ?action=costs");
			} catch (Exception $e) {
				die('Add cost error, sorry.');
			}
		}
	}
	
	if ( !function_exists( 'add_goal' ) ) {
		function add_goal( $user_id = '', $data = array() ){
			global $db_config;
			extract($db_config);
			if ( !empty( $user_id ) && ( !empty( $data ) && is_array( $data ) ) ) {
				$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
				unset($data['submit']);
				try {
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $db->prepare("INSERT INTO goals (user_id, name, quantity, date, value, note) VALUES (:user_id, :name, :quantity, :date, :value, :note)");
					$stmt->bindParam(':user_id', $user_id);
					$stmt->bindParam(':name', $data['name']);
					$stmt->bindParam(':quantity', $data['quantity']);
					$stmt->bindParam(':date', $data['date']);
					$stmt->bindParam(':value', $data['value']);
					$stmt->bindParam(':note', $data['note']);
					$stmt->execute();
					header("Location: ?action=goals");
				} catch (Exception $e) {
					die('Add goal error, sorry.');
				}
			}
		}
	}
	
	if ( !function_exists( 'get_goals' ) ) {
		function get_goals( $user_id = '' ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			
			if ( empty( $user_id ) ) {
				$user_id = get_user();
				$user_id = $user_id['ID'];
			}
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("SELECT * FROM goals WHERE user_id = :user_id ORDER BY date DESC");
				$stmt->bindParam(':user_id', $_COOKIE['user_id']);
				$stmt->execute();
				$result_goals = $stmt->fetchAll();
			} catch (Exception $e) {
				die($e);
			}
			return $result_goals;
		}
	}
	
	if ( !function_exists( 'get_goal' ) ) {
		function get_goal( $goal_id = '' ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			
			if ( empty( $user_id ) ) {
				$user_id = get_user();
				$user_id = $user_id['ID'];
			}
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("SELECT * FROM goals WHERE ID = :goal_id LIMIT 1");
				$stmt->bindParam(':goal_id', $goal_id);
				$stmt->execute();
				$result_goals = $stmt->fetchAll();
			} catch (Exception $e) {
				die($e);
			}
			return $result_goals;
		}
	}
	
	if ( !function_exists( 'update_goal' ) ) {
		function update_goal( $data = array() ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			unset($data['submit']);
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("UPDATE goals SET name = :name, quantity = :quantity, date = :date, value = :value, note = :note WHERE ID = :id");
				$stmt->bindParam(':name', $data['name']);
				$stmt->bindParam(':quantity', $data['quantity']);
				$stmt->bindParam(':date', $data['date']);
				$stmt->bindParam(':value', $data['value']);
				$stmt->bindParam(':note', $data['note']);
				$stmt->bindParam(':id', $data['goal_id']);
				$stmt->execute();
				header("Location: ?action=goals");
			} catch (Exception $e) {
				die('Add cost error, sorry.');
			}
		}
	}
	
	if ( !function_exists( 'delete_cost' ) ) {
		function delete_cost( $data = '' ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("DELETE FROM costs WHERE ID = :id");
				$stmt->bindParam(':id', $data);
				$stmt->execute();
				header("Location: ?action=costs");
			} catch (Exception $e) {
				die('Add cost error, sorry.');
			}
		}
	}
	
	if ( !function_exists( 'delete_goal' ) ) {
		function delete_goal( $data = '' ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("DELETE FROM goals WHERE ID = :id");
				$stmt->bindParam(':id', $data);
				$stmt->execute();
				header("Location: ?action=goals");
			} catch (Exception $e) {
				die('Add cost error, sorry.');
			}
		}
	}
	
	if ( !function_exists( 'delete_gain' ) ) {
		function delete_gain( $data = '' ){
			global $db_config;
			extract($db_config);
			$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . '', $db_user, $db_pass);
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $db->prepare("DELETE FROM gains WHERE ID = :id");
				$stmt->bindParam(':id', $data);
				$stmt->execute();
				header("Location: ?action=gains");
			} catch (Exception $e) {
				die('Add cost error, sorry.');
			}
		}
	}
?>