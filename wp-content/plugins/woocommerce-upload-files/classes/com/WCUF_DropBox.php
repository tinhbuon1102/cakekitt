<?php 
//https://github.com/kunalvarma05/dropbox-php-sdk

require WCUF_PLUGIN_ABS_PATH.'/classes/vendor/autoload.php';
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;

class WCUF_DropBox
{
	var $app;
	var $dropbox;
	var $file_info_data = array();
	static $dropbox_filepath_prefix = 'dropbox:'; 
	
	public function __construct()
	{
		global $wcuf_option_model;
		$cloud_settings = $wcuf_option_model->get_cloud_settings();
		$this->app = new DropboxApp("mor95d73uit1o5w", "9fscabceaue78b9", $cloud_settings['dropbox_auth_key']);
		$this->dropbox = new Dropbox($this->app);
	}
	//can trow error
	public function upload_file($file_path, $file_name)
	{
		global $wcuf_file_model;
		$this->file_info_data = array();
		$dropboxFile = new DropboxFile($file_path); ///var/zpanel/hostdata/[..]/wp-content/uploads/wcuf/13390/file.pdf
		$blog_name = get_bloginfo('name');
		$blog_name = $blog_name ? "/".$wcuf_file_model->normalizeStringForFolderName($blog_name) : "";
		$file = $this->dropbox->upload($dropboxFile, $blog_name.$file_name, ['autorename' => true]);  //file.pdf

		//$file is Models\FileMetadata.php type
		if(is_object($file))
		{
			$this->file_info_data['name'] = $file->getName();
			$this->file_info_data['id'] = $file->getId();
			$this->file_info_data['size'] = $file->getSize();
			$this->file_info_data['path_lower'] = $file->getPathLower();
			$this->file_info_data['path_display'] = $file->getPathDisplay();
			$this->file_info_data['media_info'] = $file->getMediaInfo();
		}
				
		return $this->file_info_data;
	}
	public function getTemporaryLink($file_path, $remove_prefix = false)
	{
		$file_path = $remove_prefix ? str_replace(WCUF_DropBox::$dropbox_filepath_prefix, "", $file_path) : $file_path;
		$temporaryLink = $this->dropbox->getTemporaryLink($file_path);
		return $temporaryLink->getLink();
	}
	public function delete_file($file_path, $remove_prefix = false)
	{
		$file_path = $remove_prefix ? str_replace(WCUF_DropBox::$dropbox_filepath_prefix, "", $file_path) : $file_path;
		$this->dropbox->delete($file_path);
	}
	public static function is_dropbox_file_path($file_path)
	{
		if(!is_string($file_path))
			return false;
		return strpos($file_path, WCUF_DropBox::$dropbox_filepath_prefix) !== false ? true : false;
	}
	public function render_thumb($image_path)
	{
		//Available sizes: 'thumb', 'small', 'medium', 'large', 'huge'
		$size = 'large'; //Default size

		//Available formats: 'jpeg', 'png'
		$format = $this->get_file_extension($image_path); //Default format
		if($format == false)
			return false;
		
		$image_path = str_replace( WCUF_DropBox::$dropbox_filepath_prefix,"", $image_path);
		$file = $this->dropbox->getThumbnail($image_path, $size, $format);

		//Get File Contents
		$contents = $file->getContents();
		
		//Save File Contents to Disk
		//file_put_contents(__DIR__ . "/my-logo.jpg", $contents);
		switch($format)
			{
					default: 
					case "jpeg":
						header('Content-Type: image/jpeg');
						/* $im = imagecreatefromjpeg($contents); //jpeg file
						imagejpeg($im, null,30); //was 50
						imagedestroy($im); */
						echo $contents ;
						break;
					case "png":
						header('Content-Type: image/png');
						/* $im = imagecreatefrompng($contents); //png file
						imagepng($im,null, 9);
						imagedestroy($im); */
						echo $contents ;
						break;
			} 
			
		//Get File Metadata
		//$file->getMetadata();
	}
	private function get_file_extension($file_name) 
	{
		$index = strrchr($file_name,'.');
		$ext = $index != false  ? substr($index,1) : false;
		$ext = $ext != false ? strtolower($ext) : $ext;
		
		if($ext == 'jpg' || $ext == 'jpeg')
			$ext = 'jpeg';
		$ext = $ext != 'jpeg' && $ext != 'png' ? false : $ext;
		
		return $ext;
	}
}
?>