<div class="order-register__item">
	<h3 class="order-register__subtitle">Состав заказа</h3>
	<?php
		foreach ($arResult['ITEMS'] as $product)
		{
			$img = CFile::ResizeImageGet($product['PICTURE'], ['width' => 240, 'height' => 240])['src'];
			?>
			<div class="order-register__item-content">
				<div class="order-register__item-img"><img src="<?=$img?>" alt=""></div>
				<div>
					<div class="order-register__item-name"><?=$product['NAME']?></div>
					<div class="order-register__item-quantity"><?=$product['QUANTITY']?> шт.</div>
				</div>
			</div>
			<?php
		}
	?>
</div>