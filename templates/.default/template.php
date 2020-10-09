<div class="container">
	<?php
		$APPLICATION->IncludeComponent(
			"odva:breadcrumbs",
			"",
			[
				'LINKS' => [
					[
						'text' => 'Главная',
						'url' =>'/'
					],
					[
						'text' => 'Корзина',
						'url' =>'/cart/'
					],
					[
						'text' => 'Оформления заказа',
						'url' =>'/cart/order/'
					],
				]
			]
		);
	?>
	<h2 class="title">Оформления заказа</h2>
	<div class="order-register__wr _map-content">
		<?php include 'products.php';?>
		<?php include 'prices.php';?>
		<?php include 'deliveres.php';?>
		<?php include 'form.php';?>
	</div>
</div>
<script>
BX.message({
	TEMPLATE_PATH: '<? echo $this->GetFolder(); ?>',
	PATH_MAKE_ORDER: '<?=$arResult["PATH_MAKE_ORDER"]?>',
	PATH_ACTIVATE_RPOMOCOD: '<?=$arResult["PATH_ACTIVATE_RPOMOCOD"]?>'
});
</script>