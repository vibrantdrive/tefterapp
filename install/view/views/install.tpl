{include file='head.tpl'}

<body id="step-1">
	
	<section id="content">
		<div class="group block">
			<h1>{$lang.title_step1}</h1>
			
	        {if isset($m)}
	            {$m->content}
	        {/if}

			<section id="primary">
				<form action="../install/step2.php" method="post">
					<fieldset>
						<div class="group">
						<label for="license_agreement" class="required">{$lang.label_license_agreement}</label>
						<textarea name="license_agreement" id="license_agreement" readonly="readonly" cols="30" rows="10">{$lang.text_license_agreement}</textarea>
						</div>
						
						<div class="group">
							<label class="inline"><input type="radio" name="license" id="accept_license" value="yes"> {$lang.text_i_agree}</label>
							
							<label class="inline"><input type="radio" name="license" id="decline_license" value="no"> {$lang.text_i_dont_agree}</label>
						</div>
					</fieldset>
					
					<button type="submit">{$lang.button_submit}</button>
				</form>
			</section>
			
		</div>
	</section>

</body>
</html>