//These functions control the sub links that appear after clicking the main links
function report(method) {
window.open("../display/ui.php?class=reportlist&method=" + method, "toolbar=no, scrollbars=yes, resizable=yes, width=1200, height=1000");
}

function ui(method) {
window.open("../display/ui.php?class=formview&method=" + method, "toolbar=no, scrollbars=yes, resizable=yes,width=700");
}

function banking(formname) {//opens the same file as the previous function, except full screen
window.open("../display/ui.php?class=bankview&method="+ formname, "toolbar=no, scrollbars=yes, resizable=yes, width=1000, height=1000");
}

function stmts(stmt) {
window.open("../display/ui.php?class=formview&method=statement&id=" + stmt, "toolbar=no, scrollbars=yes, resizable=yes");
}