<?php
foreach($arResult as $reviewKey => $review)
{
	?><div class="detail__product-reviews clearfix <?php if($reviewKey > 0) {?>hidden _hiddenReview<?php }?>">
		<div class="detail__product-reviews__top clearfix">
			<div class="detail__product-review-name"><?=$review['NAME']?></div>
			<div class="detail__product-review-date"><?=$review['FORMAT_DATE']?></div>
		</div>
		<div class="detail__product-review-text"><?=$review['PREVIEW_TEXT']?></div>
		<div class="detail__product-testimonials-stars detail__product-testimonials-stars__in-feedback">
			<?php
			for($i = 0; $i < 5; $i++)
			{
				if ($i <= $review['PROPERTIES']['RAIT']['VALUE'])
				{
					?><div class="svg-star svg-star-dims testimonials-star"></div><?php
				}
				else
				{
					?><div class="svg-star-empty svg-star-empty-dims testimonials-star"></div><?php
				}
			}
		?></div>
	</div><?php
}
?><div class="detail__product-reviews-buttons clearfix">
	<button onclick="o2.popups.showPopup('._feedbackPopup');" class="t-button-text t-button-bluegradient product-review-leaverev">Оставить свой отзыв</button><?php
	if (count($arResult) > 1)
	{
		?><button onclick="detailReviews.showMore(this)" class="t-button-text t-button-blue-border product-review-readrev">Читать все отзывы <span>(<?=count($arResult)?>)</span></button><?php
	}
?></div>
