<?php
/**
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
?>



<!--<div id="post-<?php //the_ID(); ?>" <?php //post_class(); ?>>

	<div class="entry-content">
	
		<div class="gallery_contents">-->
<div class="cbp-item web-design graphic">
<?php if(has_post_thumbnail()): ?>
<?php // アイキャッチ画像のIDを取得
$thumbnail_id = get_post_thumbnail_id(); 
 //サムネイルサイズの画像を$img_thimbnailに代入
$img_thumbnail = wp_get_attachment_thumb_url( $thumbnail_id , 'thumbnail' );
// mediumサイズの画像内容を取得（引数にmediumをセット）
$img_large = wp_get_attachment_thumb_url( $thumbnail_id , 'full' );
?>

<a href="<?php echo $img_large; ?>" class="cbp-caption cbp-lightbox" data-title="Bolt UI<br>by Tiberiu Neamu">
	<div class="cbp-caption-defaultWrap"><?php the_post_thumbnail('medium'); ?></div>
	<div class="cbp-caption-activeWrap">
                    <div class="cbp-l-caption-alignCenter">
                        <div class="cbp-l-caption-body">
                            <div class="cbp-l-caption-title">Bolt UI</div>
                        </div>
                    </div>
                </div>
	</a>
	
<!--<div class="esg-entry-cover">
	<div class="esg-cc eec">
		<?php

/*
*  get all custom fields, loop through them and load the field object to create a label => value markup
*/

/*$fields = get_fields();

if( $fields )
{
	foreach( $fields as $field_name => $value )
	{
		// get_field_object( $field_name, $post_id, $options )
		// - $value has already been loaded for us, no point to load it again in the get_field_object function
		$field = get_field_object($field_name, false, array('load_value' => false));

		echo '<div>';
			echo '<div class="field-label">' . $field['label'] . '</div>';
			echo '<div class="field-value">' . $value . '</div>';
		echo '</div>';
	}
}*/

?>
	</div>
	</div>-->


<?php endif; ?>
</div>
<!--</div><!-- end of .gallery_contents -->

	
	<!--</div><!-- .entry-content -->
<!--</div><!-- #post-## -->
