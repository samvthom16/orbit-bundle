<ul class="list-unstyled">
<?php foreach( $atts['items'] as $item ): if( $item ):?>
	<li><input type="checkbox" <?php if( in_array( $item, $atts['form_value']) ){_e("checked='checked'");}?> name="<?php _e( $atts['form_name'] );?>[]" value="<?php _e( $item );?>" />&nbsp;<?php _e( $item );?></li>
<?php endif; endforeach;?>
</ul>