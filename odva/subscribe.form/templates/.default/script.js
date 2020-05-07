var subscribeForm =
{
	subscribe:function(e,instance)
	{
		$.post(
			$(instance).attr("action"),
			$(instance).serialize(),
			(data)=>
			{
				alert(data);
			}
		)
	}
}