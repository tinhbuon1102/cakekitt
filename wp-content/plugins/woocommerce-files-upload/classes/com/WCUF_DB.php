<?php 
class WCUF_DB
{
	var $db_version = "1.0";
	var $db_session_table_name = 'wcuf_upload_sessions';
	var $db_option_name = 'wcuf_db_version';
	function __construct()
	{
		register_activation_hook( WCUF_PLUGIN_ABS_PATH, array(&$this,'install') );
		add_action( 'plugins_loaded', array(&$this,'check_db_version'));
	}
	function install($is_update = false) 
	{
		global $wpdb;

		$table_name = $wpdb->prefix . $this->db_session_table_name;
		
		$charset_collate = !$is_update ? $wpdb->get_charset_collate() : "";

		$sql = "CREATE TABLE $table_name (
			last_activity int NOT NULL,
			session_type varchar(255) NOT NULL,
			session_id varchar(125) NOT NULL,
			item text NOT NULL,
			PRIMARY KEY  (session_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		if(!$is_update)
			add_option( $this->db_option_name, $this->db_version );
	}
	function check_db_version()
	{
		if(get_site_option( $this->db_option_name ) == false)
			$this->install();
		else if ( get_site_option( $this->db_option_name ) != $this->db_version ) 
		{
			//wcuf_var_dump("db not present");
			$this->install(true);
			update_option( $this->db_option_name, $this->db_version);
		}
		/* else
			wcuf_var_dump( get_site_option( $this->db_option_name )); */
	}
	public function delete_expired_sessions($timeout_duration)
	{
		global $wpdb;
		$table = $wpdb->prefix.$this->db_session_table_name;
		$time = time();
		
		$query = "DELETE 
		          FROM {$table} 
				  WHERE {$time} - last_activity > {$timeout_duration} ";
		//wcuf_var_dump($query);		  
		return $wpdb->get_results( $query );
	}
	public function create_session_row($session_id)
	{
		global $wpdb;
		$table = $wpdb->prefix.$this->db_session_table_name;
		$last_activity = time();
		
		
		$already_exists = $this->read_session_row('session_id', $session_id);
		if(!isset($already_exists))
			$wpdb->insert($table, array('last_activity'=>$last_activity,'session_type'=>'','session_id'=>$session_id,'item'=>''));
	}
	public function write_session_row($key, $value, $session_id, $additional_params = null)
	{
		global $wpdb;
		$table = $wpdb->prefix.$this->db_session_table_name;
		
		/* $query = "UPDATE {$table} SET {$key} = '{$value}' 
				  WHERE session_id = '{$session_id}'";
		return $wpdb->query($query); */
		$this->create_session_row($session_id);
		
		if($key == 'item')
		{
			$result = $this->read_session_row($key, $session_id);
			$result = isset($result) ? unserialize($result) : array();
			//wcuf_var_dump($result);
			$result[$additional_params] = $value; //$additional_params = session_type
			$value = serialize($result);
			//wcuf_var_dump($value);
		}
		$wpdb->update( $table, array($key => $value ), array('session_id' => $session_id ) ); 
	}
	public function read_session_row($key, $session_id)
	{
		global $wpdb;
		$table = $wpdb->prefix.$this->db_session_table_name;
		
		$query = "SELECT {$key} 
				  FROM {$table}
				  WHERE session_id = '{$session_id}'";
		$result = $wpdb->get_col($query);
		$result = isset($result) && isset($result[0]) ? $result[0] : null;
		return $result;
	}
	public function delete_session_row($session_id, $item_session_type = null)
	{
		global $wpdb;
		$table = $wpdb->prefix.$this->db_session_table_name;
		if($item_session_type != null)
		{
			$this->write_session_row('item', null, $session_id, $item_session_type);
			return;
		}
		
		return $wpdb->delete( $table, array('session_id' => $session_id ) );
	}
}
?>