<?php
if(empty($arParams['loadMore']))
{
	$seasonOffer = false;
	foreach ($arResult['OFFERS'] as $offerKey => $offer)
	{
		if(!empty($offer['PRODUCT']['PROPERTIES']['SEAZON_OFFER']['VALUE']))
		{
			$seasonOffer = $offer;
			// unset($arResult['OFFERS'][$offerKey]);
			array_splice($arResult['OFFERS'], $offerKey,1);
			break;
		}
	}
	$arResult['SEASON_OFFER'] = $seasonOffer;
}
