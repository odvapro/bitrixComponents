<div class="cart__wr">
    <div class="container">
        <?php
            $APPLICATION->IncludeComponent("odva:breadcrumbs", "", [
                'LINKS' => [
                    ['text' => 'Главная', 'url'  => '/'],
                    ['text' => 'Корзина', 'url'  => '/cart/']
                ]
            ]);
        ?>
        <h2 class="title">Корзина</h2>
        <?php
            if(empty($arResult['PRODUCTS']))
            {
                ?>
                <div class="cart__empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="0 0 70 70">
                        <path fill="#CFD8DC" fill-rule="evenodd" d="M52.85 64.167c5.294 0 8.4-2.968 8.4-8.847V25.782c0-5.879-3.135-8.847-9.287-8.847H47.23c-.148-5.993-5.56-11.102-12.245-11.102-6.684 0-12.097 5.109-12.245 11.102h-4.703c-6.181 0-9.287 2.94-9.287 8.847V55.32c0 5.907 3.106 8.847 9.287 8.847H52.85zM42.468 16.935H27.502c.148-3.739 3.313-6.764 7.483-6.764s7.335 3.025 7.483 6.764zm10.293 42.637H18.096c-2.957 0-4.584-1.513-4.584-4.48V26.01c0-2.968 1.627-4.48 4.584-4.48h33.778c2.928 0 4.614 1.512 4.614 4.48v29.081c0 2.968-1.686 4.48-3.727 4.48z" />
                    </svg>
                    <div class="cart__empty-big-text">В корзине ничего нет</div>
                    <div class="cart__empty-small-text">
                        Вы можете начать свой выбор с главной страницы, посмотреть акции или воспользоваться поиском.
                    </div>
                    <a href="/" class="simple-button simple-button--empty">На главную</a>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="cart__detail">
                    <div class="cart__detail-left">
                        <h3 class="title">В корзине <span><?=$arResult['COUNT']['ITEMS']?></span> <?=$arResult['STR_COUNT']?></h3>
                        <?php
                            foreach ($arResult['PRODUCTS'] as $product)
                            {
                                ?>
                                <div class="cart__detail-item">
                                    <div class="cart__detail-close" data-item-id="<?=$product['ID']?>" onclick="odvaCart.deleteItem(event,this);">
                                        <svg role="img" class="close-balloon">
                                            <use xlink:href="#close-balloon"></use>
                                        </svg>
                                        <svg role="img" class="ic-close-green">
                                            <use xlink:href="#ic-close-green"></use>
                                        </svg>
                                    </div>
                                    <a class="cart__detail-img" href="<?=$product['DETAIL_PAGE_URL']?>">
                                        <img src="<?=$product['IMG']?>">
                                    </a>
                                    <div class="cart__detail-qn">
                                        <div>
                                            <a class="cart__detail-text" href="<?=$product['DETAIL_PAGE_URL']?>"><?=$product['NAME']?></a>
                                            <div
                                                class="cart__detail-count"
                                                data-product-id="<?=$product['PRODUCT_ID']?>"
                                                data-storage="<?=$product['PRODUCTS_IN_STORAGE']?>"
                                            >
                                                <div class="cart__detail-count-minus" onclick="odvaCart.cartCount(this)">
                                                    <span></span>
                                                </div>
                                                <div class="cart__detail-count-number">
                                                    <?=(int)$product['QUANTITY']?>
                                                </div>
                                                <div class="cart__detail-count-plus" onclick="odvaCart.cartCount(this)">
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="cart__detail-price"><span><?=$product['BASE_PRICE']*$product['QUANTITY']?></span> ₽</div>
                                            <div
                                                class="cart__detail-price-one"
                                            ><span><?=$product['BASE_PRICE']?></span> ₽ / шт.</div>
                                        </div>
                                    </div>
                                </div>
                                <?
                            }
                        ?>
                    </div>
                    <div class="cart__detail--right">
                        <div class="checkout-summary__wr">
                            <div class="checkout-summary">
                                <div class="checkout-summary__item">
                                    <div class="checkout-summary__line checkout-summary__line--title">
                                        <span>Вашь заказ</span>
                                        <a>Изменить</a>
                                    </div>
                                    <form onsubmit="odvaCart.setPromocod(event,this);">
                                        <div class="checkout-summary__line checkout-summary__line--promo">
                                            <div>
                                                <div class="l-input__label">Промокод на скидку</div>
                                                <input type="text" class="g-input" name="PROMOCOD" placeholder="Промокод">
                                            </div>
                                            <button class="simple-button">Применить</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="checkout-summary__item">
                                    <div class="checkout-summary__line">
                                        <span class="checkout-summary__line--count"><?=mb_ucfirst($arResult['STR_COUNT'])?> (<?=$arResult['COUNT']['ITEMS']?>)</span>
                                        <span class="checkout-summary__line--price"><?=$arResult['PRICE']['VALUE']+$arResult['PRICE']['DISCOUNT']?>₽</span>
                                    </div>
                                    <div class="checkout-summary__line checkout-summary__line--delivery">
                                        <span>Доставка</span>
                                        <span>Бесплатно</span>
                                    </div>
                                    <div class="checkout-summary__line <?=($arResult['PRICE']['DISCOUNT'] == 0)?'__hide-elememt':''?>">
                                        <span>Скидка по промокоду</span>
                                        <span class="checkout-summary__line--discount">-<?=$arResult['PRICE']['DISCOUNT']?>₽</span>
                                    </div>
                                </div>
                                <div class="checkout-summary__item checkout-summary__item--total">
                                    <div class="checkout-summary__line checkout-summary__line--total">
                                        <span>Итого</span>
                                        <div><span class="checkout-summary__line-sum"><?=$arResult['PRICE']['VALUE']?></span>₽</div>
                                    </div>
                                </div>
                                <button class="button button--checkout-summary" onclick="location.href='/cart/order/'">Перейти к оформлению</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        ?>
        <?php
            if(empty($arResult['PRODUCTS']))
            {
                $APPLICATION->IncludeComponent(
                    'odva:products',
                    'saleleader',
                    [
                        'filter' => [
                            'IBLOCK_ID'  => 2,
                            'ACTIVE'     => 'Y',
                            'PROPERTY_SALELEADER_VALUE' => 'Y',
                        ],
                        'count' => 10,
                        'heading' => 'Популярные товары'
                    ]
                );
            }
            else
            {
                $APPLICATION->IncludeComponent(
                    'odva:products',
                    'saleleader',
                    [
                        'filter' => [
                            'IBLOCK_ID' => 2,
                            'ACTIVE'    => 'Y',
                            'ID' => $arResult['RECOMMEND'],
                        ],
                        'count' => count($arResult['RECOMMEND']),
                        'heading' => 'C этими товарами покупают'
                    ]
                );
            }
        ?>
    </div>
</div>
<script>
BX.message({
    PATH_ACTIVATE_RPOMOCOD:'<?=$arResult['PATH_ACTIVATE_RPOMOCOD']?>',
    PATH_GET_ACTUAL_PRICES:'<?=$arResult['PATH_GET_ACTUAL_PRICES']?>'
});
</script>