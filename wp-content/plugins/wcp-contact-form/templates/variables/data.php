<?php
    $post_id = $params;
    $data = SCFP()->getSettings()->getFieldsSettings();
?>
<table width="100%" style="border: 1px solid #dfdfdf; border-collapse: collapse;">
    <?php $i=0; foreach( $data as $datakey => $datavalue ): ?>
        <?php if ( !empty( $datavalue['visibility'] ) && $datavalue['field_type'] !== 'captcha' ) : ?>
            <tr<?php echo (($i % 2 == 0)) ? ' style="background: #f5f5f5;"' : ''; ?>>
                <td style="padding: 10px 5px; border: 1px solid #dfdfdf; width: 15%;">
                    <strong><?php echo strip_tags( html_entity_decode( $datavalue['name'] ) ); ?></strong>
                </td>
                <td style="padding: 10px 5px; border: 1px solid #dfdfdf; width: 85%;">
                    <?php
                        if ( in_array( $datavalue['field_type'], array( 'checkbox', 'radio', 'select' ) ) ) {     
                            $value = strip_tags( html_entity_decode( SCFP()->getChoicesView(get_post_meta( $post_id, 'scfp_'.$datakey, true ), $datakey) ) );
                        } else {
                            $value = get_post_meta( $post_id, 'scfp_'.$datakey, true );
                        }
                        echo !empty($value) ? $value : '&nbsp;';
                    ?>
                </td>
            </tr>
        <?php endif;?>
    <?php $i++; endforeach; ?>
</table>