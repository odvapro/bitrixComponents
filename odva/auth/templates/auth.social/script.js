function auth_with_social(token)
{
	$.ajax({
		type:'POST',
		url: $("#uLogin").data("url-file"), // адрес, на который будет отправлен запрос
		dataType: "json",
		data:{"token":token},
		success: function(data)
		{
			if(data.success == true)
			{
				alert(data.msg);
				return false;
			}
			else
			{
				alert(data.msg);
				return false;
			}
		}
	});
}