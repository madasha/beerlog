<?php
// Initialize simple beer chart props
$propsSimple = array(
  	'sourness'    => 1,
	'bitterness'  => 1,
    'sweetness'   => 1,
    'saltiness'   => 1,
    'yeast'       => 1,
    'hop'         => 1,
    'malt'        => 1,
);

// Fetch values from post meta in case they are set
foreach ( $propsSimple as $propName => $propValue )
{
	if ( $metaValue = get_post_meta( $post->ID, '_beerlog_meta_props_' . $propName, true ) )
		$propsSimple[ $propName ] = $metaValue;
}

// Initialize pro beer chart props
$propsPro = array(
  	'fruty'       => 1,
    'alcoholic'   => 1,
    'citrus'      => 1,
    'hoppy'       => 1,
    'floral'      => 1,
    'spicy'       => 1,
    'malty'       => 1,
    'toffee'      => 1,
    'burnt'       => 1,
    'sulphury'    => 1,
    'sweet'       => 1,
    'sour'        => 1,
    'bitter'      => 1,
    'dry'         => 1,
    'body'        => 1,
    'linger'      => 1,
);

// Fetch values from post meta in case they are set
foreach ( $propsPro as $propName => $propValue )
{
	if ( $metaValue = get_post_meta( $post->ID, '_beerlog_meta_props_' . $propName, true ) )
		$propsPro[ $propName ] = $metaValue;
}
?>

<table width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    	<tr>
	    	<td>
			    <p>
			    	<label for="beerlog_meta_malts"><strong><?php _e('Malts: ', 'beerlog'); ?></strong></label><br />
			    	<input type="text" name="beerlog_meta[malts]" id="beerlog_meta_malts" style="width:100%;"
			    	value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_malts', true ); ?>" />
			    </p>
			    <p>
			    	<label for="beerlog_meta_hops"><strong><?php _e('Hops: ', 'beerlog'); ?></strong></label><br />
			    	<input type="text" name="beerlog_meta[hops]" id="beerlog_meta_hops" style="width:100%;"
			    	value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_hops', true ); ?>" />
			    </p>
			    <p>
			    	<label for="beerlog_meta_adds"><strong><?php _e('Additions/Spices: ', 'beerlog'); ?></strong></label><br />
			    	<input type="text" name="beerlog_meta[adds]" id="beerlog_meta_adds" style="width:100%;"
			    	value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_adds', true ); ?>" />
			    </p>
			    <p>
			    	<label for="beerlog_meta_yeast"><strong><?php _e('Yeast: ', 'beerlog'); ?></strong></label><br />
			    	<input type="text" name="beerlog_meta[yeast]" id="beerlog_meta_yeast" style="width:100%;"
			    	value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_yeast', true ); ?>" />
			    </p>
	    		<hr />
			    <p>
			    	<label for="beerlog_meta_abv"><strong><?php _e('ABV: ', 'beerlog'); ?></strong></label>
					<input type="number" name="beerlog_meta[abv]" id="beerlog_meta_abv"
					min="0.0" max="100.0" step="0.1" value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_abv', true ); ?>" /> %
				</p>
			    <p>
			    	<label for="beerlog_meta_style"><strong><?php _e('IBU: ', 'beerlog'); ?></strong></label>
					<input type="number" name="beerlog_meta[ibu]" id="beerlog_meta_style"
					min="0" max="100" step="1" value="<?php echo get_post_meta( $post->ID, '_beerlog_meta_ibu', true ); ?>" />
				</p>
	    	</td>
    	</tr>
    	<tr>
    		<td>
    			<p>
    				<?php
    					$propChart = get_post_meta( $post->ID, '_beerlog_meta_prop_chart', true );
    				?>
    				<hr />
			    	<label for="beerlog_meta_prop_chart_off"><strong><?php _e('Show properties graph: ', 'beerlog'); ?></strong></label>
					<input type="radio" name="beerlog_meta[prop_chart]" id="beerlog_meta_prop_chart_off"
					value="0" <?php if ( !$propChart ) echo 'checked="checked"'; ?> />
					<?php _e('Off', 'beerlog'); ?> |
					<input type="radio" name="beerlog_meta[prop_chart]" id="beerlog_meta_prop_chart_simple"
					value="simple" <?php if ( 'simple' == $propChart ) echo 'checked="checked"'; ?> />
					<?php _e('Simple', 'beerlog'); ?> |
					<input type="radio" name="beerlog_meta[prop_chart]" id="beerlog_meta_prop_chart_pro"
					value="pro" <?php if ( 'pro' == $propChart ) echo 'checked="checked"'; ?> />
					<?php _e('Pro', 'beerlog'); ?>

					<div id="beerlog_props_simple" <?php if ( false && 'simple' != $propChart ) echo 'style="display: none"'?>>
						<table width="50%" cellpadding="0" cellspacing="0">
						<?php foreach ( $propsSimple as $propName => $propValue ): ?>
						<tr>
							<td width="20%">
								<label for="beerlog_meta_props_<?php echo $propName?>" style="vertical-align: text-top;"><strong><?php _e( ucfirst( $propName ) . ': ', 'beerlog'); ?></strong></label>
							</td>
							<td>
								<input type="range" name="beerlog_meta_props[ <?php echo $propName?> ]" id="beerlog_meta_props_<?php echo $propName?>"
									min="1" max="5" step="1" value="<?php echo $propValue; ?>" defaultValue="<?php echo $propValue; ?>"
									style="vertical-align: text-top" oninput="outputUpdate('<?php echo $propName?>', value)" />
								<input id="beerlog_output_<?php echo $propName?>" type="text" style="width: 30px" value="<?php echo $propValue; ?>" />
							</td>
						</tr>
						<?php endforeach; ?>
					</div>

					<div id="beerlog_props_pro" <?php if ( 'pro' != $propChart ) echo 'style="display: none"'?>>
					</div>
				</p>
    		</td>
    	</tr>
    </tbody>
</table>

<script type="text/javascript">
function outputUpdate( propName, value )
{
	// console.log( propName, value );
	document.getElementById('beerlog_output_' + propName).value = value;
}
</script>