<?php
    $post = $params;
    $entry = SCFP()->getFormEntries();
    $data = SCFP()->getSettings()->getFieldsSettings();

?>
<div class="wrap">
    <h2>Entry #<?php echo $entry->getEntryId($post->ID); ?></h2>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content" style="position: relative;">
                <div id="wp-content-editor-container" class="wp-editor-container">
                    <table class="view-entry-table widefat scfp-view-table">
                        <?php foreach( $data as $datakey => $datavalue ): ?>
                            <?php if ( !empty( $datavalue['visibility'] ) && $datavalue['field_type'] !== 'captcha') : ?>
                                <tr class="view-entry-header alternate">
                                    <td><strong><?php echo strip_tags( html_entity_decode( $datavalue['name'] ) ); ?></strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php
                                            if ( in_array( $datavalue['field_type'], array( 'checkbox', 'radio', 'select' ) ) ) {        
                                                $value = strip_tags( html_entity_decode( SCFP()->getChoicesView(get_post_meta( $post->ID, 'scfp_'.$datakey, true ), $datakey) ) );
                                            } else {
                                                $value = get_post_meta($post->ID, 'scfp_'.$datakey, true );
                                            }           
                                            echo !empty($value) ? $value : '&nbsp;';
                                        ?>
                                    </td>
                                </tr>
                            <?php endif;?>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <div id="postbox-container-1" class="postbox-container">
                <div id="submitdiv" class="postbox ">
                    <h3 class="hndle">Entry</h3>
                    <div class="inside">
                        <div id="submitpost" class="submitbox">
                            <div id="minor-publishing">
                                <div id="misc-publishing-actions">
                                    <div class="misc-pub-section">Entry ID: <?php echo $entry->getEntryId($post->ID); ?></div>
                                    <div class="misc-pub-section">
                                        <?php
                                            $d = get_option( 'date_format' ); 
                                            $t = get_option( 'time_format' );
                                            $post_date = get_gmt_from_date($post->post_date, $d );
                                            $post_time = get_gmt_from_date($post->post_date, $t);
                                            echo "Submitted on: " . $post_date . " at " . $post_time;
                                        ?>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div id="major-publishing-actions">
                                <?php if ( current_user_can( 'delete_post', $post->ID ) ) :  ?>
                                <div id="delete-action">
                                    <a class="submitdelete deletion" href="<?php print get_delete_post_link($post->ID);?>">Move to Trash</a>
                                    <?php 
                                        if( !$post->ID ){
                                            redirect( admin_url( "edit.php?post_type=form-entries" ) );
                                        }
                                    ?>
                                </div>
                                <?php endif; ?>
                                <div id="publishing-action">
                                    <?php echo $entry->getReplyButton($post->ID, 'Quick Reply', array('class' => 'scfp-quick-reply-btn wcp-quick-reply-button button button-primary button-large')); ?>     
                                </div>    
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
