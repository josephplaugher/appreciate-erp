	<div id = "newrequest">
	<h3 class = "header"><span class = "emerg">IF THIS IS AN EMERGENCY, DO NOT SUBMIT A MAINTENANCE REQUEST - CALL 911 IMMEDIATELY</span> <br></h3>
	<form method="POST" action="<?php print htmlspecialchars($_SERVER['PHP_SELF'])?>">
		<p class = "header">Submit a maintenance request</p>			
		<label for id = "title" class = "header">Provide a descriptive title for your request</label><br><input type="text" class = "textinput" name="title" id = "title" size="30" length="30" maxlength = "50" value = "<?php print $title ?>" autofocus><br> 	
		<label for id = "description" class = "header">Describe specifically and concisely what you need</label><br>	
		<textarea name="description"  rows="8"><?php print $description?></textarea><BR>
		<p class = "header">Choose a category <BR>
	 	<select name = "category" class = "select">
			<option value= "appliance">Appliance</option>  
			<option value= "electrical">Electrical</option>  
			<option value= "flooring">Flooring</option>  
			<option value= "outdoors">Outdoors</option>  			
			<option value= "paint">Paint</option>  
			<option value= "plumbing">Plumbing</option>  
			<option value= "roof">Roof</option>  
		</select>
		<br>
		<input type="submit" class = "submit" name="submit" onclick="validation()" value="Submit Request"> <input type="reset" name="reset" class = "submit" value="Reset">
		</form>
		<?php include('../messagecenter.php'); ?>
	</div>