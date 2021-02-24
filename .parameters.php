<?php


// если компонент подключается для вывода детальной страницы товара,
// показываем в параметрах параметр "показывать в характеристиках"
if(
	array_key_exists('COMPONENT_TYPE', $arCurrentValues)
	&&
	$arCurrentValues['COMPONENT_TYPE'] == 'PRODUCT'
	&&
	!empty($arCurrentValues['IBLOCK_ID'])
)
{
	\Bitrix\Main\Loader::includeModule('iblock');

	$arPropList = [];
	$rsProps = CIBlockProperty::GetList([], ['IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']]);

	while ($arProp = $rsProps->Fetch())
		$arPropList[$arProp['CODE']] = $arProp['NAME'];

	$arComponentParameters['PARAMETERS']['SHOW_PROPERTIES'] = [
		'NAME' => 'Показывать в характеристиках на детальной странице',
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'SIZE' => 10,
		'VALUES' => $arPropList,
	];
}