<?php
$arResult['DATE_FORMAT'] = FormatDate("d F Y", MakeTimeStamp($arResult["DATE_CREATE"]));
$APPLICATION->SetTitle($arResult['NAME']);