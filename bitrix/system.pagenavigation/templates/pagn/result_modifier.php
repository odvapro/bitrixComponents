<?php
$tPage = $arResult['NavPageNomer'];
$cPage = $arResult['NavPageCount'];
$countItem = 5;
if($cPage<6)
{
	echo $tPage;
	for($item = 1; $item < $cPage+1;$item++)
	{
		$result[] =	[$item,($item == $tPage)?'Y':''];
	}
}
else
{
	$result[] =	[$tPage,'Y'];
	$count = $countItem - 1;
	$item = 1;
	while(true)
	{
		if(($tPage-$item)> 0)
		{
			array_unshift($result,[$tPage-$item,'']);
			$count--;
		}
		if(!$count)
			break;
		if(($tPage+$item) < $cPage+1)
		{
			$result[] =	[$tPage+$item,''];
			$count--;
		}
		if(!$count)
			break;
		$item++;
	};
}
$arResult = $result;
