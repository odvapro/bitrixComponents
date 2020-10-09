var ProfilePassword =
{
	chenge:function(e,data)
	{
		e.preventDefault();
		$.ajax({
			type:'POST',
			url: $(data).attr("action"), // адрес, на который будет отправлен запрос
         	dataType: "json",
         	data:$(data).serialize(),
			success: function(data)
			{
				if(data.success == 'OK')
				{
					alert("все успешно изменено");
				}
				else
				{
					alert(data.msg);
				}
			}
		});
	}
}