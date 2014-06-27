<h1>Welcome to Our Site</h1>

<p>Get Your URL Shortend By One Click !!</p>

<?php  
	if($keyExist==false)
	 echo "<font color='red'>Sorry URL Dosen't Exist In Our DB .</font>"
?>
<table width="100%">
	<tr>
		<td style="text-align:center">
			<div class="row">
				<strong><?php echo CHtml::label('Put Your URL Here : ','',''); ?></strong>
				<?php echo CHtml::textField('url','',array('style'=>'width:400px;')); ?>
				<?php echo CHtml::ajaxSubmitButton(
											'Short URL',          // the link body (it will NOT be HTML-encoded.)
											array('site/GetShortedUrl'), // the URL for the AJAX request. If empty, it is assumed to be the current URL.
											array(
											'data' => array('url'=> 'js: $("#url").val()'),
											'beforeSend'=>'js:function() {
																$("#req_res").html("<img src=\'images/ajax-loader.gif\' alt=\'Loading..\' />");
																}',
											'update'=>'#req_res')
											);
				?>
			</div>
		</td>	
	</tr>
	
	<tr>
		<td style="text-align:center">
			<br/>
			<h4><div id="req_res"></div></h4>
		</td>	
	</tr>
</table>


