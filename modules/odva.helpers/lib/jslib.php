<?php

namespace Odva\Helpers;

class JsLib
{
	public static function registerExt($template, $relations = [])
	{
		$templatePath = $template->GetFolder();

		$jsModulePath = "{$templatePath}/script.js";

		$jsModuleName = "{$template->getComponent()->getName()}_{$template->getName()}";
		$jsModuleName = preg_replace("/[^a-z0-9_]/i", '_', $jsModuleName);

		if(
			!\CJSCore::IsExtRegistered($jsModuleName)
			&&
			file_exists($jsModulePath)
		)
		{
			$relations = array_merge($relations, ['odva']);

			\CJSCore::RegisterExt(
				$jsModuleName,
				[
					'js' => $jsModulePath,
					'rel' => $relations
				]
			);

			\CUtil::InitJSCore([$jsModuleName]);
		}
	}
}