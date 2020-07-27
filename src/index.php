<?php
?>
<!DOCTYPE html PUBLIC "//EN" www.appreciateco.com>
<head profile = "display/logos/AppreciateLogo_2_H.png">
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title>Appreciate</title>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="MSSmartTagsPreventParsing" content="true" />
<meta name="description" content="accounting services information" />
<meta name="keywords" content="accounting, bookkeeping, books, quickbooks, quick books, quickbooks pro, quick books pro, ledger, general ledger, debits, credits, small business, entrepreneur, entrepreneurship, funding, bank" />
<link rel="stylesheet" href="display/css/home.css" />
<link rel="icon" 
      type="image/png" 
      href="display/logos/AppreciateLogo_4_H.png">
<!-- This includes the functions that controll all our links -->
<script src="/display/javascript/homelinks.js">
</script>	
</head>

<body link="white">

<div id="page-container">
	<div id= "appreciate">
	<p><img src="/display/logos/AppreciateLogo_4_H.png" alt="Appreciate Corporation" onclick="home()" style="cursor:pointer"> </P>
	<div id="accounts">
		<p2><text0 onclick="signin()" style="cursor:pointer"> Sign In </text0></p2> 
<script>
function signin() {
	window.location = "display/ui.php?class=userview&method=login";
}
</script>
	</div>
	</div>	
	<div id="top-nav">
<p>Coming Soon...</p>
	<div id = "cntr">
	
	<div id="acct"> <p> <text1 onclick="scale()" style="cursor:pointer"><span class = "big">Scale</span> <br>
	Sophisticated accounting<br>for growing businesses</text1></p>  
	</div> 

	<div id="resoftware"> 
		<p> <text1 onclick="resoftware()" style="cursor:pointer"><span class = "big">Appreciate ERP</span><BR/>
		Comprehensive business management<br> for real estate companies</text1></p> 
	</div> 
	
	<div id="simpleacct"> <p> <text2 onclick="simple()" style="cursor:pointer"><span class = "big">Simple</span> <br>
	Basic accounting<br>for small businesses</text2></p>  
	</div> 
	</div> 
	
	</div>
	<div id = "content-bg">
	<div id="content">
	<?php include ('landingpage/start.php');
	?>
	</div>
	</div>
	<div id="form">	
		<p1>Sign up to receive updates as we roll out the software. We promise not to sell or distribute your information. Period.</p1>
		<form  method = "POST" action = "<?php print htmlspecialchars($_SERVER['PHP_SELF']);?>" >
	 	<p>First Name <br>
		<input type = "text" name = "fname" size = "20"><br>
	 	Email Address <br>
		<input type = "text" name = "email" size = "20"> <br>
		I am interested in<BR>being a beta tester<br> 
		<select name = "beta">
		<option value = "y">Yes, please sign me up!</option>
		<option value = "n">No, not right now</option>	
		</select><br><br>					
		<input type="submit" name="submit" value="Keep Me Updated"><br></p>
		</form>
		</div>
	</div>
	
	<div id="services">
	<div class="padding">	
		<h3>Appreciate Accounting Services</h3>
		<br />
		<p>Streamline cash flow management<br>Identify break-even point<br>Cost analysis<br>General bookkeeping<br>Payroll	
	</div>
	</div>
	
	<div id="footer">
		<p onclick="signin()"><img src = "ssl/comodo_secure_seal_100x85_transp.png"><br>
		Copyright 2016 Appreciate Corporation</p>
	</div>
</div>
</body>
</html>

