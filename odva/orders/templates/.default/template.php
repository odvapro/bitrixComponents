<table>
	<?php
		foreach ($arResult['ORDERS'] as $order)
		{
			?>
			<tr>
				<th>Заказ №<?=$order['ID']?> </th>
				<th>от <?=$order['DATE_INSERT']?></th>
			</tr>
			<tr>
				<td>Сумма по чеку:</td>
				<td><?=$order['PRICE_FORMAT']?></td>
			</tr>
			<tr>
				<td>Статус:</td>
				<td><?=$order['STATUS_NAME']?></td>
			</tr>
			<tr>
				<td>Получено баллов:</td>
				<td><?=$order['PROPERTIES']['POINTS']['VALUE']?> баллов</td>
			</tr>
			<tr>
				<td>Время выполнения заказа:</td>
				<td>как можно быстрее, <?=$order['PROPERTIES']['DAY']['VALUE']?> до <?=$order['PROPERTIES']['TIME']['VALUE']?></td>
			</tr>
			<tr>
				<td>Адрес:</td>
				<td><?=$order['PROPERTIES']['ADDRESS']['VALUE']?></td>
			</tr>
			<tr>
				<td colspan="2">Покупатель</td>
			</tr>
			<tr>
				<td>Имя:</td>
				<td><?=$order['PROPERTIES']['FIO']['VALUE']?></td>
			</tr>
			<tr>
				<td>Телефон:</td>
				<td><?=$order['PROPERTIES']['PHONE']['VALUE']?></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><?=$order['PROPERTIES']['EMAIL']['VALUE']?></td>
			</tr>
			<tr>
				<td colspan="2">Состав заказа:</td>
			</tr>
			<?php
			foreach ($order['PRODUCTS'] as $product)
			{
				?>
				<tr>
					<td>- <?=$product['NAME']?></td>
					<td>Количество: <?=$product['QUANTITY']?></td>
				</tr>
				<?php
				if(!empty($product['SOSTAV']))
				{
					foreach ($product['SOSTAV'] as $sostavMeal)
					{
						?><tr>
							<td colspan="2"><?=$sostavMeal?></td>
						</tr><?php
					}
				}
			}
			?>
			<tr>
				<th colspan="2"></th>
			</tr>
			<?php
		}
	?>
</table>