<?php
/**
 * @package		DigiCom
 * @author 		ThemeXpert http://www.themexpert.com
 * @copyright	Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;
$configs = $this->configs;
$images  = json_decode($this->item->images);
// Legacy code
// TODO : Remove after 1.1
if(!isset($images->image_full)){
	$images = new stdClass();
	$images->image_full = $this->item->images;
}elseif(empty($images->image_full)){
	$images->image_full = $images->image_intro;
}
if($this->item->price > 0){
	$price = DigiComSiteHelperPrice::format_price($this->item->price, $configs->get('currency','USD'), true, $configs).'</span>';
}else{
	$price = '<span>'.JText::_('COM_DIGICOM_PRODUCT_PRICE_FREE').'</span>';
}
$link = JRoute::_(DigiComSiteHelperRoute::getProductRoute($this->item->id, $this->item->catid, $this->item->language));
?>

<div id="digicom" class="dc dc-product" itemscope itemtype="http://schema.org/CreativeWork">

	<header class="dc-item-head">
		<h1 class="dc-product-title">
			<span itemprop="name">
				<?php echo $this->item->name; ?>
			</span>
		</h1>
	</header>

	<article>
		<div class="row">
			<div class="col-md-8">
				<?php if(!empty($images->image_full)): ?>
				<div class="dc-item-in">
						<figure>
							<img itemprop="image" src="<?php echo JURI::root().$images->image_full; ?>" alt="<?php echo $this->item->name; ?>" class="dc-product-image"/>
						</figure>
				</div>
				<?php endif; ?>
				<p class="dc-product-intro"><?php echo $this->item->introtext?></p>
				<div class="dc-product-details" itemprop="description">
					<?php echo $this->item->text; ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="dc-item-in">
					<div class="well clearfix" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
						<div class="row">
							<div class="col-md-12">
								<p class="dc-product-price text-center">
									<meta itemprop="priceCurrency" content="<?php echo $configs->get('currency','USD');?>" />
									<strong itemprop="price" content="<?php echo $this->item->price; ?>">
										<?php echo $price; ?>
									</strong>
								</p>

								<?php if($configs->get('enable_taxes','0') && $configs->get('display_tax_with_price','0')):?>
									<div class="dc-product-tax text-info text-center">
										<?php echo JLayoutHelper::render('tax.price', array('config' => $configs, 'item' => $this->item)); ?>
									</div>
								<?php endif; ?>

							</div>

							<?php if ($this->configs->get('show_validity',1) == 1) : ?>
								<div class="col-md-12">
									<div class="dc-product-validity text-muted text-center">
										<?php echo JText::_('COM_DIGICOM_PRODUCT_VALIDITY'); ?> : <?php echo DigiComSiteHelperPrice::getProductValidityPeriod($this->item); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="dc-product-info">
						<ul class="list-unstyled">
							<li>
								<strong><?php echo JText::_('COM_DIGICOM_PRODUCT_CREATE_DATE');?> :</strong> <?php echo JFactory::getDate($this->item->publish_up)->format('M d, Y')?>
							</li>
							<li>
								<strong><?php echo JText::_('COM_DIGICOM_PRODUCT_CATEGORY');?>: </strong>
								<a href="<?php echo DigiComSiteHelperRoute::getCategoryRoute($this->item->catid);?>">
									<?php echo $this->item->category_title;?></a>
							</li>
							<li>
								<strong><?php echo JText::_('COM_DIGICOM_TYPE');?> : </strong>
								<?php if(!empty($this->item->bundle_source)):?>
									<?php echo JText::_('COM_DIGICOM_PRODUCT_TYPE_BUNDLE');?>
								<?php else:?>
									<?php echo JText::_('COM_DIGICOM_PRODUCT_TYPE_SINGLE');?>
								<?php endif; ?>
							</li>
							<li>
								<strong><?php echo JText::_('COM_DIGICOM_TAGS');?> :</strong> <?php
									if(!empty($this->item->tags->itemTags)){
										$this->item->tagLayout = new JLayoutFile('joomla.content.tags');
										echo $this->item->tagLayout->render($this->item->tags->itemTags);
									}
								?>
							</li>
						</ul>
					</div>

					<?php if($this->item->featured):?>
						<!-- Featured label -->
						<span class="label label-info label--featured"><?php echo JText::_('JFEATURED');?></span>
					<?php endif; ?>
				</div>

				<?php if ($this->configs->get('catalogue',0) == '0' and !$this->item->hide_public) : ?>
					<div class="dc-addtocart-bar">
						<form name="prod" class="form-inline" id="product-form" action="<?php echo JRoute::_('index.php?option=com_digicom&view=cart');?>" method="post" style="width:100%;">
							<div class="<?php echo ($configs->get('show_quantity',0) == 1 ? "with-qnty" : ''); ?>">

								<?php if($configs->get('show_quantity',0) == "1") {	?>
									<input data-digicom-id="quantity_<?php echo $this->item->id; ?>" type="number" name="qty" min="1" class="dc-product-qnty" value="1" size="2" placeholder="<?php echo JText::_('COM_DIGICOM_QUANTITY'); ?>">
								<?php } ?>

								<?php if($configs->get('afteradditem',0) == "2") {	?>
									<div type="button" class="btn btn-success btn-large btn-block btn--cart" onclick="Digicom.addtoCart(<?php echo $this->item->id; ?>,'<?php echo JRoute::_("index.php?option=com_digicom&view=cart"); ?>');"><?php echo JText::_("COM_DIGICOM_ADD_TO_CART");?></div>
								<?php }else { ?>
									<button type="submit" class="btn btn-success btn-large btn-block"> <?php echo JText::_('COM_DIGICOM_ADD_TO_CART'); ?></button>
									<?php } ?>
							</div>

							<input type="hidden" name="option" value="com_digicom"/>
							<input type="hidden" name="view" value="cart"/>
							<input type="hidden" name="task" value="cart.add"/>
							<input type="hidden" name="pid" value="<?php echo $this->item->id; ?>"/>
						</form>
					</div>
				<?php endif; ?>

				<?php
				if(!empty($this->item->bundle_source)):
					echo $this->loadTemplate('bundle');
				endif;
				?>

			</div>
		</div>
	</artile>

	<?php
		if($configs->get('afteradditem',0) == "2"):
			$layoutData = array(
				'selector' => 'digicomCartPopup',
				'params'   => array(
												'title' 	=> JText::_('COM_DIGICOM_CART_ITEMS'),
												'height' 	=> '400',
												'width'	 	=> '1280',
												'footer'	=> '<button type="button" class="btn btn-default" data-dismiss="modal">'.JText::_('COM_DIGICOM_CONTINUE').'</button> <a href="'.JRoute::_("index.php?option=com_digicom&view=cart").'" class="btn btn-success"><i class="ico-ok-sign"></i> '.JText::_("COM_DIGICOM_CHECKOUT").'</a>'
											),
				'body'     => ''
			);
			echo JLayoutHelper::render('bt3.modal.main', $layoutData);
		endif;
	?>

	<?php echo DigiComSiteHelperDigicom::powered_by(); ?>

</div>
