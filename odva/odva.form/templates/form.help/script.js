var formHelp =
{
	textareaEmpty:function(field)
	{
		let hasError = false;
		hasError = $(field).find("textarea").val() == "";
		if(hasError)
		{
			this.setMessage(field,'Заполните сообщение');
			return false;
		}
		return true;
	},
	send:function(e,instanse)
	{
		e.preventDefault();
		let validator = new O2Validator(instanse);
		validator.callbacks.textareaEmpty = this.textareaEmpty;
		if(!validator.validate())
			return false;
		let formData = $(instanse).serializeArray();
		formData.push({name:"IBLOCK_ID",value:BX.message('IBLOCK_ID')})
		formData.push({name:"NAME_FORM_RESULT",value:BX.message('NAME_FORM_RESULT')})
		formData.push({name:"MAIL_EVENT_CODE",value:BX.message('MAIL_EVENT_CODE')})
		$.ajax({
			type     :'POST',
			url      : BX.message('PATH_AJAX'),
			dataType : "json",
			data     :formData,
			success: (data)=>
			{
				console.log(data);
				if(data.success)
				{
					alert('Вопрос успешно отправлен.');
					$(instanse)[0].reset();
				}
				else
				{
					alert('Ошибка отправки, попробуйте позже.');
				}
			}
		});
	}
}