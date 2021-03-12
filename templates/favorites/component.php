<?php

$arResult = [
	'COUNT' => count(\Odva\Helpers\Favorites::get()),
	'ELEMENTS' => \Odva\Helpers\Favorites::get(),
	'URL'   => ($USER->IsAuthorized() ? '/page/favorites/' : '#login')
];

$this->IncludeComponentTemplate();

$templatePath = $this->getTemplate()->GetFolder();

$jsModulePath = "{$templatePath}/script.js";

$jsModuleName = "{$this->getName()}_{$this->getTemplate()->getName()}";
$jsModuleName = preg_replace("/[^a-z0-9_]/i", '_', $jsModuleName);

if(
	!CJSCore::IsExtRegistered($jsModuleName)
	&&
	file_exists($jsModulePath)
)
{
	CJSCore::RegisterExt(
		$jsModuleName,
		[
			'js' => $jsModulePath,
			'rel' => ['odva']
		]
	);

	CUtil::InitJSCore([$jsModuleName]);
}