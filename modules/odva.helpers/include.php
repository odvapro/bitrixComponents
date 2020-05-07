<?php

CJSCore::RegisterExt(
	'odva',
	[
		'js' => '/bitrix/js/odva.lib.js',
		'rel' => ['jquery']
	]
);

CUtil::InitJSCore(['odva']);