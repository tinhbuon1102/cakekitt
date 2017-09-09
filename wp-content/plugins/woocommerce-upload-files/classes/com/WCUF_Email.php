<?php
class WCUF_Email  
{
	public function __construct() 
	{
	}
	public function trigger( $links_to_notify_via_mail, $order , $attachment = array()) 
	{
		global $wcuf_order_model;
		
		$order_id = $wcuf_order_model->get_order_id($order) ;	
		$billing_email = $wcuf_order_model->get_billing_email($order) ;	
		//$recipient = get_option( 'admin_email' );
		foreach($links_to_notify_via_mail as $recipients => $links)
		{
			$recipient = $recipients;
			$subject = __('User submitted new upload for order number: ', 'woocommerce-files-upload').$order_id;
			$content = __('User submitted new upload for order number: ', 'woocommerce-files-upload').'<a href="'.admin_url('post.php?post='.$order_id.'&action=edit').'">'.$order_id.'</a>';
			$content .= "<br /> <br />";
			$content .= "<strong>".__('Customer personal data', 'woocommerce-files-upload')."</strong><br/>".$order->get_formatted_billing_full_name()." (". $billing_email.") <br/>".$order->get_formatted_billing_address();
			$content .= "<br /> <br />";
			$content .= "<strong>".__('Uploaded file(s)', 'woocommerce-files-upload')."</strong> <br />";
			$content .= __('You can directly download by clicking on following link(s): ', 'woocommerce-files-upload');
			$content .= "<br /> ";
			$content .= '<table>';
			$counter = 0;
			foreach($links as $download)
			{
				
				foreach($download['url'] as $file_url)
				{
					//if($download['source'][$counter] == 'local')
						if(isset($download['title']))
							$content .='<tr><a href="'. $file_url.'">'.$download['title'].": ".$download['file_name'][$counter].'</a> '.__('(Quantity: ', 'woocommerce-files-upload').$download['quantity'][$counter].')</tr>';
						else
							$content .='<tr><a href="'. $file_url.'">'.$download['title'].'</a> '.__('(Quantity: ', 'woocommerce-files-upload').$download['quantity'][$counter].')</tr>';
					$counter++;
				}
				if(isset($download['feedback']) && $download['feedback'] != '')
				{
					$content .= '<tr><strong>'.__('User feedback: ', 'woocommerce-files-upload').'</strong>';
					$content .= "<br /> ";
					$content .= $download['feedback'];
					$content .= "</tr>";
					$content .= "<tr><br/></tr>";
				}
			}
			$content .= '</table>';
			
			$attachments = isset($attachment[$recipients]) ? $attachment[$recipients] : array();
			$attachments_local = array();
			foreach($attachments as $attachment)
			{
				$counter = 0;
				foreach($attachment['paths'] as $file_to_attach)
					if($attachment['sources'][$counter++] == 'local')
						$attachments_local[] = $file_to_attach;
			}
			
			
			$mail = WC()->mailer();
			$email_heading = get_bloginfo('name');
			
			ob_start();
			$mail->email_header($email_heading );
			echo $content;
			$mail->email_footer();
			$message =  ob_get_contents();
			ob_end_clean(); 
			
			
			$mail->send( $recipient, $subject, $message, "Content-Type: text/html\r\n", $attachments_local);
		}
	}
	function send_error_email_to_admin($text)
	{
		$mail = WC()->mailer();
		$email_heading = get_bloginfo('name');
		$subject = __('Something needs your attention...', 'woocommerce-files-upload');
		
		ob_start();
		$mail->email_header($email_heading );
		_e('<h2>The following error has been generated by your site:</h2>', 'woocommerce-files-upload');
		echo "<p>".$text."</p>";
		_e('<p>Be sure that the <strong>max_execution_time</strong> PHP setting is properly setted. For large files execution time may excede the configured time. So in case you are handling big file, try increase that setting.</p>', 'woocommerce-files-upload');
		$mail->email_footer();
		$message =  ob_get_contents();
		ob_end_clean(); 
		
		//$attachments = isset($attachment[$recipients]) ? $attachment[$recipients] : array();
		
		$mail->send( get_bloginfo('admin_email'), $subject, $message, "Content-Type: text/html\r\n");
	}
} 