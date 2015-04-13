<table width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    	<tr>
	    	<td>
			    <p>
			    	<label for="malts"><strong><?php _e('Malts: ', 'beerlog'); ?></strong></label><br />
			    	<input type="text" name="beerlog_meta[malts]" id="beerlog_meta_malts" style="width:100%;"
			    	value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_malts', true ); ?>" />
			    </p>
			    <p>
			    	<label for="hops"><strong><?php _e('Hops: ', 'beerlog'); ?></strong></label><br />
			    	<input type="text" name="beerlog_meta[hops]" id="beerlog_meta_hops" style="width:100%;"
			    	value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_hops', true ); ?>" />
			    </p>
			    <p>
			    	<label for="adds"><strong><?php _e('Additions/Spices: ', 'beerlog'); ?></strong></label><br />
			    	<input type="text" name="beerlog_meta[adds]" id="beerlog_meta_adds" style="width:100%;"
			    	value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_adds', true ); ?>" />
			    </p>
			    <p>
			    	<label for="yeast"><strong><?php _e('Yeast: ', 'beerlog'); ?></strong></label><br />
			    	<input type="text" name="beerlog_meta[yeast]" id="beerlog_meta_yeast" style="width:100%;"
			    	value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_yeast', true ); ?>" />
			    </p>
	    		<hr />
			    <p>
			    	<label for="abv"><strong><?php _e('ABV: ', 'beerlog'); ?></strong></label>
					<input type="number" name="beerlog_meta[abv]" id="beerlog_meta_abv"
					min="0.0" max="100.0" step="0.1" value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_abv', true ); ?>" /> %
				</p>
			    <p>
			    	<label for="ibu"><strong><?php _e('IBU: ', 'beerlog'); ?></strong></label>
					<input type="number" name="beerlog_meta[ibu]" id="beerlog_meta_style"
					min="0" max="100" step="1" value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_ibu', true ); ?>" />
				</p>
	    	</td>
    	</tr>
    	<tr>
    		<td>
    			<p>
    				<hr />
			    	<label for="_beerlog_meta_prop_chart_off"><strong><?php _e('Show properties graph: ', 'beerlog'); ?></strong></label>
					<input type="radio" name="beerlog_meta[prop_chart]" id="_beerlog_meta_prop_chart_off"
					value="0" <?php if ( !get_post_meta( $post->ID, '_beerlog_meta_prop_chart', true ) ) echo 'checked="checked"'; ?> />
					<?php _e('Off', 'beerlog'); ?> |
					<input type="radio" name="beerlog_meta[prop_chart]" id="_beerlog_meta_prop_chart_simple"
					value="simple" <?php if ( get_post_meta( $post->ID, '_beerlog_meta_prop_chart', true ) == 'simple' ) echo 'checked="checked"'; ?> />
					<?php _e('Simple', 'beerlog'); ?> |
					<input type="radio" name="beerlog_meta[prop_chart]" id="_beerlog_meta_prop_chart_pro"
					value="pro" <?php if ( get_post_meta( $post->ID, '_beerlog_meta_prop_chart', true ) == 'pro' ) echo 'checked="checked"'; ?> />
					<?php _e('Pro', 'beerlog'); ?>
				</p>
    		</td>
    	</tr>
    </tbody>
</table>