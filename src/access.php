<?php
$page = basename($_SERVER['PHP_SELF']);

$accessdenied = '../accessdenied.php';

switch ($page) {
	//administration
	case "pmtsettings.php":
		if($_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;

	//statements
	case "incomestatement.php":
		if($_SESSION['fin_stmts'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
	case "balancesheet.php":
		if($_SESSION['fin_stmts'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
	case "cashflow.php":
		if($_SESSION['fin_stmts'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
	//accounting
	case "crinvoice.php":
		if($_SESSION['ar'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
			case "crcredit.php":
		if($_SESSION['ar'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
	case "viewinv.php":
		if($_SESSION['ar'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
	case "invdetails.php":
		if($_SESSION['ar'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
	//Banking
		case "bankrec.php":
		if(is_null($_SESSION['reconcile_bank']) && is_null($_SESSION['administrator'])) {
		header("location: $accessdenied");
		}
		break;
				case "bankrec.view.php":
		if(is_null($_SESSION['undo_bank_rec']) && is_null($_SESSION['administrator'])) {
		header("location: $accessdenied");
		}
		break;
	case "withdrawal.php":
		if(is_null($_SESSION['withdrawals']) && is_null($_SESSION['administrator'])) {
		header("location: $accessdenied");
		}
		break;
	case "enterdeposit.php":
		if(is_null($_SESSION['deposits']) && is_null($_SESSION['administrator'])) {
		header("location: $accessdenied");
		}
		break;
	//AR
	case "viewinv.php":
		if($_SESSION['ar'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
	//employees 
	case "addemp.php":
		if($_SESSION['administrator'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
	case "viewemp.php":
		if($_SESSION['administrator'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
	case "editemp.php":
		if($_SESSION['administrator'] !== '1' && $_SESSION['administrator'] !== '1') {
		header("location: $accessdenied");
		}
		break;
}
?>
