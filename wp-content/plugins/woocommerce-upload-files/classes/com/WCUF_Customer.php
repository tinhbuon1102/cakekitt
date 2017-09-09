<?php 
class WCUF_Customer
{
 public function __construct(){} 	
 public function get_current_customer_last_order_id()
 {
	 $current_customer_id = get_current_user_id();
	 
	 if($current_customer_id == 0)
		 return 0;
	 
	$last_customer_order = get_posts( array(
        'numberposts' => 1,
        'meta_value'  => $current_customer_id,
		'meta_key'  => "_customer_user",
        'post_type'   => 'shop_order',
		'post_status' => array_keys( wc_get_order_statuses() ),
    ) ); 
	
	return isset($last_customer_order) && !empty($last_customer_order) ? $last_customer_order[0]->ID : 0;
 }
 
 public function get_user_roles()
 {
	 global $wp_roles;
	 return $wp_roles->roles;
 }
 public function belongs_to_allowed_roles($roles,$roles_policy)
 {
	 global $current_user;
	 //wcuf_var_dump($current_user);
	 //wcuf_var_dump(session_id());
	 
	/*if(!isset($current_user))
		 return false;*/
	$is_logged = is_user_logged_in();
	if($roles_policy == 'allow')
	{
		if(!$is_logged && array_key_exists('not_logged', $roles))
			return true;
		
		if($is_logged)
		{
			//wcuf_var_dump($is_logged);
			foreach($roles as $role => $value)
			{
				if(in_array($role, $current_user->roles))
						return true;
			}
		}
	}
	else if($roles_policy == 'deny')
	 {
		 if(!$is_logged && !array_key_exists('not_logged', $roles))
			return true;
		
		 if($is_logged)
		 {
			 $belongs_at_least_to_one_not_allowe_rule = false;
			 foreach($roles as $role => $value)
			 {
				if(in_array($role, $current_user->roles))
					$belongs_at_least_to_one_not_allowe_rule = true;
			 }return !$belongs_at_least_to_one_not_allowe_rule;
		 }
	 }
			
	return false;
 }
}
?>