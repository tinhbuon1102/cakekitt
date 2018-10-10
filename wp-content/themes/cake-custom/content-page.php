<?php
/**
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
?>

<?php if( is_front_page() ){ ?>
<section class="cakeorder cards_box section_front">
	<div class="layout_style01">
		<div class="card_block">
			<div class="card_block_images">
				<div class="card_item_column">
				<figure class="card_item">
				<figcaption class="pinkcap">
					<span class="subt jp">フルカスタムオーダー</span>
					<p>Full custom order</p>
					<span class="price_label">¥8,000~</span>
					<span class="del jp"><span class="label_inline label_pink">配送可能エリア：東京23区内</span><br/><small><!--(送料¥1,500~)--></small></span>
					<span class="desc jp">あなたのお好きなデザインのケーキをご注文いただけます。</span>
					
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>order-made-form/" class="round_link">注文する</a>
				</figcaption>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>order-made-form/" class="card_link"></a>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/order_cake01_new.jpg" />
				</figure>
				</div><!--/card_item_column-->
				<div class="card_item_column">
				<figure class="card_item">
				<figcaption class="bluecap">
					<span class="subt jp">メニューオーダー</span>
					<p>Menu order</p>
					<span class="price_label">¥4,500~</span>
					<span class="del jp"><span class="label_inline label_blue">配送可能エリア：全国</span><br/><small>(宅配便配送可能地域のみ)</small></span>
					<span class="desc jp">Kittオリジナルのケーキメニューをご注文いただけます。</span>
					
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>menu/" class="round_link">注文する</a>
				</figcaption>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>menu/" class="card_link"></a>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/order_cake02_new.jpg" />
			</figure>
			</div><!--/card_item_column-->
			</div>
			<div class="card_block_text">
				<div class="card_block_text_inner mid_content">
					<span class="subt jp">ケーキ注文</span>
					<h2>Order <span class="cppink">Cakes</span></h2>
					<p>あなたの特別な日に<br class="xs-hide">とっておきのケーキを提供させていただきます。<br class="xs-hide">ケーキメニューからのご注文、またはフルカスタムオーダーのオーダーメイドケーキご注文からお選びできます。</p>
					<div class="link_block">
						<a href="#howOrder" class="minimal_link">ご注文方法ついて</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php } ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cake'),
				'after'  => '</div>',
			));
		?>
	</div><!-- .entry-content -->
</div><!-- #post-## -->
