var cSection =
{
	filter:
	{
		init:function()
		{
			var path = window.location.pathname;
			var regex = /\/filter\/(.*)\/[^\/]*/g;
			var m;
			if((m = regex.exec(path)) !== null)
			{
				var splitUrl = m[1].split('/');
				var paramsLength = splitUrl.length;
				var current = [];
				for (var i = 0; i < paramsLength; i = i+2)
					current.push([splitUrl[i],splitUrl[i+1]]);
				this.current = current;
			}
			var filterIndex = path.indexOf('/filter/');
			if(filterIndex === -1)
				this.pageUrl = path;
			else
				this.pageUrl = path.substr(0,filterIndex+1);
		},
		pageUrl:'',
		current:[],
		setParam:function(paramName,paramValue)
		{
			var paramIndex = this.getParamIndex(paramName);
			if(paramIndex === false)
				return this.current.push([paramName,paramValue]);
			return this.current[paramIndex][1] = paramValue;
		},
		getParam:function(paramName)
		{
			var paramIndex = this.getParamIndex(paramName);
			if(paramIndex !== false)
				return this.current[paramIndex][1];
			return false;
		},
		getParamIndex:function(paramName)
		{
			var paramsLength = this.current.length;
			for (var i = 0; i < paramsLength; i++)
				if(this.current[i][0] == paramName)
					return i;
			return false;
		},
		getCurrentUrl:function()
		{
			var paramsLength = this.current.length;
			var retUrl = [];
			for (var i = 0; i < paramsLength; i++)
				retUrl.push(this.current[i].join('/'));
			var resUrl = retUrl.join('/');
			resUrl = (resUrl != '')?this.pageUrl+'filter/'+resUrl+'/':'';
			this.setWindowUrl(resUrl);
			return resUrl;
		},
		// generate new url by pathing throw dom
		makeUrl:function()
		{
			var filterArr = [];
			$('._catalogFilter').first().find('._filterField').each(function()
			{
				var fieldType = $(this).data('fieldtype');
				var fieldName = $(this).data('fieldname');
				var fieldVal = false;
				switch(fieldType)
				{
					case 'list':
						fieldVal = [];
						$(this).find('input:checked').each(function()
						{
							fieldVal.push($(this).val());
						});
						fieldVal = fieldVal.join('-');
						fieldVal = (fieldVal == '')?false:fieldVal;
					break;
					case 'range':
						fieldVal = [];
						var from     = $(this).find('._fromValue').data('filtervalue');
						var to       = $(this).find('._toValue').data('filtervalue');
						if(from != '') fieldVal.push(from);
						if(to != '') fieldVal.push(to);
						fieldVal = (fieldVal.length > 1)?fieldVal.join('_'):false;
					break;
					case 'radiolist':
						fieldVal = $(this).find('input:checked').val();
						fieldVal = (typeof fieldVal != 'undefined' && fieldVal != '')?fieldVal:false;
					break;
				}
				if(fieldVal !== false)
					filterArr.push([fieldName,fieldVal]);
			});
			return filterArr;
		},
		setCurrent:function(newCurrent)
		{
			this.current = newCurrent;
		},
		setWindowUrl:function(url)
		{
			window.history.pushState({}, '', url);
		},
		clear:function()
		{
			$('._filterField').each(function()
			{
				var fieldType = $(this).data('fieldtype');
				switch(fieldType)
				{
					case 'list':
						$(this).find('input:checked').each(function()
						{
							$(this).prop('checked',false)
						});
					break;
					case 'range':
						fieldVal = [];
						var from     = $(this).find('._fromValue').data('filtervalue');
						var to       = $(this).find('._toValue').data('filtervalue');
						if(from != '') fieldVal.push(from);
						if(to != '') fieldVal.push(to);
						fieldVal = (fieldVal.length > 1)?fieldVal.join('_'):false;
					break;
					case 'radiolist':
						$(this).find('input:checked').prop('checked',false);
					break;
				}
			});
		}
	},
	clearFilter:function(argument)
	{
		var self = this;
		this.filter.clear();
		var filterParams = this.filter.makeUrl();
		var currentSorting = this.filter.getParam('sort');
		this.filter.setCurrent(filterParams);

		this.filter.setParam('page',1);
		if(currentSorting !== false)
			this.filter.setParam('sort',currentSorting);
		var url = this.filter.getCurrentUrl();
		this.loader.show();
		$.ajax({
			url      : url,
			type     : 'POST',
			dataType : 'json',
			data     : {'ajax':true}
		}).done(function(e)
		{
			if(typeof e.success == 'undefined' || e.success != true)
				return false;
			$('._productsBlock').html(e.html);
		}).always(function(e)
		{
			self.loader.hide();
		});
	},
	filterCatalog:function()
	{
		var self = this;
		var filterParams = this.filter.makeUrl();
		var currentSorting = this.filter.getParam('sort');
		this.filter.setCurrent(filterParams);
		this.filter.setParam('page',1);
		if(currentSorting !== false)
			this.filter.setParam('sort',currentSorting);
		var url = this.filter.getCurrentUrl();
		this.loader.show();
		$.ajax({
			url      : url,
			type     : 'POST',
			dataType : 'json',
			data     : {'ajax':true}
		}).done(function(e)
		{
			if(typeof e.success == 'undefined' || e.success != true)
				return false;
			$('._productsBlock').html(e.html);
		}).always(function(e)
		{
			self.loader.hide();
		});
	},
	loader:
	{
		show:function()
		{
			$('._catalogPreloader').addClass('_show');
		},
		hide:function()
		{
			$('._catalogPreloader').removeClass('_show');
		}
	},
	setSort:function(sortName)
	{
		var self = this;
		this.filter.setParam('page',1);
		this.filter.setParam('sort',sortName);
		var url = this.filter.getCurrentUrl();
		this.loader.show();
		$.ajax({
			url      : url,
			type     : 'POST',
			dataType : 'json',
			data     : {'ajax':true}
		}).done(function(e)
		{
			if(typeof e.success == 'undefined' || e.success != true)
				return false;
			$('._productsBlock').html(e.html);
		}).always(function()
		{
			self.loader.hide();
		});
	},
	loadMore:function(instance)
	{
		var nextPage = $(instance).data('nexpage');
		this.filter.setParam('page',nextPage);
		var url = this.filter.getCurrentUrl();
		$(instance).addClass('loading');
		$.ajax({
			url      : url,
			type     : 'POST',
			dataType : 'json',
			data     : {'ajax':true,loadMore:true}
		}).done(function(e)
		{
			$(instance).removeClass('loading');
			if(typeof e.success == 'undefined' || e.success != true)
				return false;
			$('._moreProductsPlace').before(e.html);
			$(instance).data('nexpage',e.nextpage);
			if(e.nextpage == 0)
				$(instance).remove();
		});
	}
}
document.addEventListener("DOMContentLoaded", function(event)
{
	cSection.filter.init();
});