<?php
/**
 * Template File: Basic Pricing Table.
 *
 * To see what methods are available on the product variable, @see inc/models/class-products.php.
 *
 * This template can also be overridden using template overrides.
 * See more here: https://help.wpultimo.com/article/335-template-overrides.
 *
 * @since 2.0.0
 * @param array $products List of product objects.
 * @param string $name ID of the field.
 * @param string $label The field label.
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (isset($should_display) && !$should_display) {

  ?>
    <div id="wu-site-template-container"></div>
  <?php

  return;

} // end if;

$sites = array_map('wu_get_site', isset($sites) ? $sites : array());

$categories = isset($categories) ? $categories : array();

$customer_sites_category = __('Your Sites', 'wp_ultimo');

$customer_sites = isset($customer_sites) ? array_map('intval', $customer_sites) : array();

?>

<div id="wu-site-template-container">

  <ul id="wu-site-template-filter" class="wu-bg-white wu-border-solid wu-border wu-border-gray-300 wu-shadow-sm wu-p-4 wu-flex wu-rounded wu-relative wu-m-0 wu-mb-4 wu-list-none">

    <li class="wu-site-template-filter-all wu-mx-2 wu-my-0">
      <a
        href="#"
        data-category=""
        :class="$parent.template_category === '' ? 'current wu-font-semibold' : ''"
        v-on:click.prevent="$parent.template_category = ''"
      >
        <?php _e('All', 'wp-ultimo'); ?>
      </a>
    </li>

    <?php if (!empty($customer_sites)) : ?>

      <li class="wu-site-template-filter-<?php echo esc_attr(sanitize_title($customer_sites_category)); ?> wu-mx-2 wu-my-0">
        <a
          href="#"
          data-category="<?php echo esc_attr($customer_sites_category); ?>"
          :class="$parent.template_category === '<?php echo esc_attr($customer_sites_category); ?>' ? 'current wu-font-semibold' : ''"
          v-on:click.prevent="$parent.template_category = '<?php echo esc_attr($customer_sites_category); ?>'"
        ><?php echo $customer_sites_category; ?></a>
      </li>

    <?php endif; ?>

    <?php if (isset($categories) && $categories) : ?>

      <?php foreach ($categories as $category) : ?>

        <li class="wu-site-template-filter-<?php echo esc_attr(sanitize_title($category)); ?> wu-mx-2 wu-my-0">
          <a
            href="#"
            data-category="<?php echo esc_attr($category); ?>"
            :class="$parent.template_category === '<?php echo esc_attr($category); ?>' ? 'current wu-font-semibold' : ''"
            v-on:click.prevent="$parent.template_category = '<?php echo esc_attr($category); ?>'"
          ><?php echo $category; ?></a>
        </li>

      <?php endforeach; ?>

    <?php endif; ?>

  </ul>

  <div id="wu-site-template-container-grid" class="wu-grid wu-grid-cols-1 sm:wu-grid-cols-2 md:wu-grid-cols-<?php echo isset($cols) ? $cols : '3'; ?> wu-gap-4">

    <?php foreach ($sites as $site_template) : ?>

      <?php if ($site_template->get_type() !== 'site_template' && !in_array($site_template->get_id(), $customer_sites, true)) { continue; } ?>

      <?php $is_template = $site_template->get_type() === 'site_template'; ?>

      <?php $categories = array_merge($site_template->get_categories(), !$is_template ? array($customer_sites_category) : array()); ?>

      <div
        id="wu-site-template-<?php echo esc_attr($site_template->get_id()); ?>"
        class="wu-bg-white wu-border-solid wu-border wu-border-gray-300 wu-shadow-sm wu-p-4 wu-rounded wu-relative"
        v-show="!$parent.template_category || <?php echo esc_attr(json_encode($categories)); ?>.join(',').indexOf($parent.template_category) > -1"
        v-cloak
      >

        <div class="wu-site-template-image-container wu-relative">

          <a
            title="<?php esc_attr_e('View Template Preview', 'wp-ultimo'); ?>"
            class="wu-site-template-selector wu-cursor-pointer wu-no-underline"
            <?php echo $is_template ? $site_template->get_preview_url_attrs() : sprintf('href="%s" target="_blank"', $site_template->get_active_site_url()); ?>
          >

            <img class="wu-site-template-image wu-w-full wu-border-solid wu-border wu-border-gray-300 wu-mb-4 wu-bg-white" src="<?php echo esc_attr($site_template->get_featured_image()); ?>" alt="<?php echo $site_template->get_title(); ?>">

          </a>

        </div>

        <h3 class="wu-site-template-title wu-text-lg wu-font-semibold">

          <?php echo $site_template->get_title(); ?>

        </h3>

        <p class="wu-site-template-description wu-text-sm">

          <?php echo $site_template->get_description(); ?>

        </p>

        <div class="wu-mt-4">

          <button v-on:click.prevent="$parent.template_id = <?php echo esc_attr($site_template->get_id()); ?>" type="button" class="wu-site-template-selector button btn button-primary btn-primary wu-w-full wu-text-center wu-cursor-pointer">

            <span v-if="$parent.template_id == <?php echo esc_attr($site_template->get_id()); ?>"><?php _e('Selected', 'wp-ultimo'); ?></span>

            <span v-else><?php _e('Select', 'wp-ultimo'); ?></span>

          </button>

        </div>

      </div>

    <?php endforeach; ?>

  </div>

</div>
