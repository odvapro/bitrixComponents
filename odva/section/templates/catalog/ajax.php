<?php
$filter = $APPLICATION->IncludeComponent('odva:filter', 'catalog', {
	'productsFilter' : {
		'IBLOCK_ID': $arResult['IBLOCK_ID'],
		'SECTION_ID': $arResult['ID'],
	},
	'offersFilter':{
		'IBLOCK_ID': 5,
	},
	'url':_REQUEST['filter'],
	'ajax':'Y',
	'fields':{
		'sphere':{
			'type':'sectionsList',
		},
		'type':{
			'type'      :'list',
			'propName'  :'TYPE',
			'iBclockId' :1,
			'heading'   :'Тип продукции'
		},
		'box':{
			'type'      :'list',
			'propName'  :'BOX_TYPE',
			'iBclockId' :1,
			'heading'   :'Вид упаковки'
		},
		'price':{
			'type'     :'range',
			'propName' :'CATALOG_PRICE_1',
			'offers'   : true
		},
		'volume':{
			'type'      :'offersList',
			'propName'  :'VALUME',
			'iBclockId' :5,
			'heading'   :'Объем продукции',
			'offers'    : true
		},
	}
});
$products = $APPLICATION->IncludeComponent('odva:offers', 'catalog', {
	'productsFilter' : $filter['productsFilter'],
	'offersFilter' : $filter['offersFilter'],
	'offerPropertiesSettings': {},
	'productPropertiesSettings': {
		'PREVIEW_PICTURE' : {
			'type' : 'image',
			'sizes' : {
				'mini' : {'height' : 364, 'width':364 },
				'medium' : {'height' : 616, 'width':626 }
			}
		}
	},
	'count' :13,
	'page'  :$filter['page'],
	'sort'  :$filter['sort'],
	'ajax'  :'Y',
	'loadMore':$arParams['loadMore']
});