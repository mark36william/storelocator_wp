<div class="pol-md-4">
  <div class="sl-card mb-4 box-shadow">
    <?php if($store_data->path): ?>
    <img  class="mh-50 img-thumbnail" src="<?php echo ASL_UPLOAD_URL ?>Logo/<?php echo esc_attr($store_data->path) ?>" alt="<?php echo esc_attr($store_data->title) ?>">
    <?php endif; ?>
    <div class="sl-card-body">
        <h3 class="sl-card-title"><?php echo esc_attr($store_data->title) ?></h3>
        <ul class="sl-address list-unstyled">
            <li class="sl-store-info">
                <?php 
                if(isset($store_data->street)){
                    ?>
                    <i class="icon-location-1 float-left mr-2"></i>
                    <p><?php echo esc_attr($store_data->street) ?> <br> <?php echo esc_attr($locality) ?></p>

                    <?php
                }
                ?>
            </li>
            <?php if(isset($store_data->phone)): ?>
            <li class="sl-store-info">
                <i class="icon-mobile-1 float-left mr-2"></i>
                <p><a href="tel:<?php echo esc_attr($store_data->phone) ?>"><?php echo esc_attr($store_data->phone) ?></a></p>
            </li>
            <?php endif; ?>
            <?php if(isset($store_data->email)): ?>
            <li class="sl-store-info">
                <i class="icon-mail float-left mr-2"></i>
                <p><a href="mailto:<?php echo esc_attr($store_data->email) ?>"><?php echo esc_attr($store_data->email) ?></a></p>
            </li>
            <?php endif; ?>
        </ul>
        <p class="mb-0">
            <?php 
            if(isset($store_data->url)){
                echo '<a class="btn btn-primary float-left" href="'. esc_attr($store_data->url) .'" role="button">'.asl_esc_lbl('website').' »</a>';
            }
            ?>
            
            <a class="btn btn-light float-right" target="_blank" href="<?php echo esc_attr($store_data->direction) ?>" role="button"><?php echo asl_esc_lbl('direction') ?> »</a>
        </p>
    </div>
  </div>
</div>