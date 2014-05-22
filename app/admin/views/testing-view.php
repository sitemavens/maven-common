<form name="frm_membership" method="post" action="<?php echo $form_action ?>" />

<?php echo $nonce_field; ?>
<?php if ($executed):?>
	<div class="box box-small">
		<div class="mvn-caption">
		<label ><?php _e("Mode:"); ?></label>
		</div>
		
		<div class="mvn-input">
			<?php echo $mode; ?>
		</div>
	</div>
	<div class="box box-small">
		<div class="mvn-caption">
		<label ><?php _e("Amount:"); ?></label>
		</div>
		<div class="mvn-input">
			<?php echo $amount; ?>
		</div>
	</div>
	<div class="box box-small">
		<div class="mvn-caption">
		<label ><?php _e("URL:");  ?></label>
		<div class="mvn-input">
			<textarea rows="5" cols="200" ><?php echo $url; ?></textarea>
		</div>
		
		</div>
	</div>
	<div class="box box-small">
		<div class="mvn-caption">
		<label ><?php _e("Tran Key:"); ?></label>
		</div>
		<div class="mvn-input">
			<?php echo $tran_key; ?>
		</div>
	</div>
	<div class="box box-small">
		<div class="mvn-caption">
		<label ><?php _e("Login:"); ?></label>
		</div>
		<div class="mvn-input">
			<?php echo $login; ?>
		</div>
	</div>
	<div class="box box-small">
		<div class="mvn-caption">
		<label ><?php _e("Response RAW:"); ?></label>
		</div>
		<div class="mvn-input">
			<?php echo $response_raw; ?>
		</div>
	</div>

	<?php if ($error_message): ?>
	<div class="box box-small">
		<div class="mvn-caption">
			<label ><?php _e("Error Message:"); ?></label>
		</div>
		<div class="mvn-input">
			<?php echo $error_message; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($success_message): ?>
	<div class="box box-small">
		<div class="mvn-caption">
			<label ><?php _e("Success Message:"); ?></label>
		</div>
		<div class="mvn-input">
			<?php echo $success_message; ?>
		</div>
	</div>
	<?php endif; ?>
	
	
<br/><br/><br/><br/>
<?php endif;?>
<table>
	<tr>
		<td>
			<?php _e("Mode"); ?>
		</td>
		<td>
			<select name='mode' id="mode">
				<option value="test_mode" label="Test mode"  />
				<option value="live_mode" label="Live mode"  />
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php _e("Amount"); ?>
		</td>
		<td>
			<input name="amount" id="amount" value="<?php echo $amount ?>" />
		</td>
	</tr>
	<tr>
		<td>
			Card Number
		</td>
		<td>
			<input  name="mvn_cc_number" value="4222222222222"  />
		</td>
	</tr>
	<tr>
		<td>
			Expiration Date
		</td>
		<td>
			<select name="mvn_cc_exp_month" id="mvn_cc_exp_month" >
				<option selected="selected" value=""></option>
				<option value="1">1 - January</option>
				<option value="2">2 - February</option>
				<option value="3">3 - March</option>
				<option value="4">4 - April</option>
				<option value="5">5 - May</option>
				<option value="6">6 - June</option>
				<option value="7">7 - July</option>
				<option value="8">8 - August</option>
				<option value="9">9 - September</option>
				<option value="10">10 - October</option>
				<option value="11">11 - November</option>
				<option value="12">12 - December</option>
			</select>
			<select name="mvn_cc_exp_year" id="mvn_cc_exp_year" >
				<option selected="selected" value=""></option>
				<option value="2012">2012</option>
				<option value="2013">2013</option>
				<option value="2014">2014</option>
				<option value="2015">2015</option>
				<option value="2016">2016</option>
				<option value="2017">2017</option>
				<option value="2018">2018</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>
			Security Code
		</td>
		<td>
			<input  name="mvn_cc_secutiry_code" value="123"  />
		</td>
	</tr>
</table>
<p>
	<input type="submit" name="mvn_gateway_pro_execute" value="<?php _e("Execute"); ?>"  />
</p>
</form>