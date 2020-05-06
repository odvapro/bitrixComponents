var sendPasswordBitrix =
{
	send:function(e,init)
	{
		e.preventDefault();
		$.ajax({
			type:'POST',
			url: $(init).attr("action"), // адрес, на который будет отправлен запрос
			dataType: "json",
			data:$(init).serialize(),
			success: function(data)
			{
				if(data.success == true)
				{
					alert(data.msg);
				}
				else
				{
					alert(data.msg);
				}
			}
		});
	}
}