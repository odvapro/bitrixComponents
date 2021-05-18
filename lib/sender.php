<?php

/**
 * класс для подписки/отписки от рассылок
 * использует модуль E-mail маркетинга (sender)
 */

namespace Odva\Module;

use \Bitrix\Main\Error;
use \Bitrix\Sender\ContactTable;
use \Bitrix\Sender\Entity\Contact;
use \Bitrix\Sender\Subscription;

class Sender
{
	public static function subscribe(string $email, array $rubrics = [])
	{
		if(!check_email($email))
			return new Error('Некорректный email', 'email');

		$contactId = ContactTable::addIfNotExist(['EMAIL' => $email]);
		$contact   = new Contact($contactId);

		if(empty($rubrics))
		{
			$arRubrics = self::getRubrics(['IS_PUBLIC' => 'Y']);
			foreach ($arRubrics as $rubric)
				$rubrics[] = $rubric['ID'];
		}

		foreach ($rubrics as $rubric)
			$contact->subscribe($rubric);

		return true;
	}

	public static function getRubrics(array $filter = [])
	{
		return Subscription::getMailingList($filter);
	}
}
