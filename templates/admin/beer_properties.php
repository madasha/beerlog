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
    </tbody>
</table>