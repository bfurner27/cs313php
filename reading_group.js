function showErrorLogin() {
	$("#userName").mouseover();
	$("#errorMessage").show();
	$("#userName").attr("placeholder", "Ex: sillyJoe80");
	$("#password").attr("placeholder", "Ex: **********");
}

function submitLogin() {
	var username = $("#userName").val();
	var password = $("#password").val();

	if (username === "" || password === "")
	{
		showErrorLogin();
		return false;
	}

	return true;
}


function verifyNewAccountInfo() {
	var isValid = true;
	if ($("#inputName").val() == "")
	{
		$("#inputName").attr('placeholder', "ex: Jared Green");
		isValid = false;
	}
	
	if ($("#inputUsername").val() == "") 
	{
		$("#inputUsername").attr('placeholder', "ex: goingGreen83");
		isValid = false;
	}
	
	if ($("#password").val() == "") 
	{
		$("#password").attr('placeholder', "ex: *************");
		isValid = false;
	} 
	
	if ($("#passwordCheck").val ()== "") 
	{
		$("#passwordCheck").attr('placeholder', "ex: *************");
		isValid = false;
	} 
	
	if (!newUserConfirmPassword())
	{
		isValid = false;
	}

	return isValid;
}

function newUserConfirmPassword() {
	if ($("#password").val() !== $("#passwordCheck").val() || $("#password") === "") {
		$("#passwordError").show();
		$('#password').attr('placeholder', 'ex: *************');
		$('#passwordCheck').attr('placeholder', 'ex: ************');
		return false;
	}
	return true;
}