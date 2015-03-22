<table width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    	<tr>
    	<td>
		    <p><label for="malts"><strong><?php _e('Malts: ', 'beerlog'); ?></strong></label><br />
		    	<input type="text" name="malts" id="malts" style="width:100%;" value="<?php echo $b_malts; ?>" /></p>
		    <p><label for="hops"><strong><?php _e('Hops: ', 'beerlog'); ?></strong></label><br />
		    	<input type="text" name="hops" id="hops" style="width:100%;" value="<?php echo $b_hops; ?>" /></p>
		    <p><label for="adds"><strong><?php _e('Additions/Spices: ', 'beerlog'); ?></strong></label><br />
		    	<input type="text" name="adds" id="adds" style="width:100%;" value="<?php echo $b_adds; ?>" /></p>
		    <p><label for="yeast"><strong><?php _e('Yeast: ', 'beerlog'); ?></strong></label><br />
		    	<input type="text" name="yeast" id="yeast" style="width:100%;" value="<?php echo $b_yeast; ?>" /></p>
    	<hr />
		    <p><label for="abv"><strong><?php _e('ABV: ', 'beerlog'); ?></strong></label>
		       <input type="number" name="abv" id="abv" min="0.0" max="100.0" step="0.1" value="<?php echo $b_abv; ?>" /> %</p>
		    <p><label for="ibu"><strong><?php _e('IBU: ', 'beerlog'); ?></strong></label>
		       <input type="number" name="ibu" id="style" min="0" max="100" step="1" value="<?php echo $b_ibu; ?>" /></p>
    	</td>
    	</tr>
    </tbody>
</table>