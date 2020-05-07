<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
require("phpQuery-onefile.php");
class instagrammComponent extends CBitrixComponent
{
	static function GetRegPage($login)
	{
		$sOut=self::GETURL("https://www.instagram.com/".$login."/","");
		return $sOut;
	}
	static function GETURL($url,$data)
	{
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HEADER, false);
		curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($c, CURLOPT_URL, $url);
		$contents = curl_exec($c);
		curl_close($c);
		return $contents;
	}
	public function executeComponent()
	{
		$sOut=self::GetRegPage($this->arParams['login']);
		$html = phpQuery::newDocument($sOut);
		$text=null;
		$script=trim(pq("script")->eq(4)->text(), "\x00..\x1F \t\n\r\0\x0B");
		//удоляем лишнее
		$text=str_replace('window._sharedData = ',"",$script);
		$text=str_replace("</script>","",$text);
		$text=substr($text,0,strlen($text)-1);
		//декодируем json
		$text=json_decode($text);
		//забираем фото из распакованого объекта
		$img=$text->entry_data->ProfilePage[0]->user->media->nodes[0]->thumbnail_src;
		$this->arResult['ITEMS'] = [];
		$tree = $text->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges;
		foreach ($tree as $objectNode)
		{
			$this->arResult['ITEMS'][] = $objectNode->node->thumbnail_src;
		}
		$this->includeComponentTemplate();
	}
}