(function(){
	var odvaCartLine = {
		basketAddItemEvent: function(msg)
		{
			console.log(msg);
		}
	};

	BX.Odva.Basket.subscribe(odvaCartLine);
})();