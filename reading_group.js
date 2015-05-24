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
	if ($("#inputName").val() == "")
	{
		$("#inputName").attr('placeholder', "ex: Jared Green");
		return false;
	}
	
	if ($("#inputUsername").val == "") 
	{
		$("#inputUsername").attr('placeholder', "ex: goingGreen83");
		return false;
	}
	
	if ($("#password").val == "") 
	{
		$("#password").attr('placeholder', "ex: *************");
		return false;
	} 
	
	if ($("#passwordCheck").val == "") 
	{
		$("#passwordCheck").attr('placeholder', "ex: *************");
		return false;
	} 
	
	if (newUserConfirmPassword()) 
	{
		return false;
	}

	return true;
}

function newUserConfirmPassword() {
	if ($("#password").val() !== $("#passwordCheck").val()) {
		$("#passwordError").show();
		$('#password').attr('placeholder', 'ex: *************');
		$('#passwordCheck').attr('placeholder', 'ex: ************');
		return false;
	}
	return true;
}