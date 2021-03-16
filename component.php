<?php

$arResult = [
	'COUNT' => count(\Odva\Module\Favorites::get()),
	'ELEMENTS' => \Odva\Module\Favorites::get(),
];

$this->IncludeComponentTemplate();
