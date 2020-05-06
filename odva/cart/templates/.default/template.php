<a href="/cart/" class="header__bottom-item">
	<div class="header__bubble _cart-products-count<?=(empty($arResult['COUNT']['ITEMS']) ? ' header__bubble_hide' : '')?>">
		<?=$arResult['COUNT']['ITEMS']?>
	</div>
	<svg role="img" class="ic-cart">
		<use xlink:href="#ic-cart"></use>
	</svg>
	Корзина
</a>