<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<button data-post_id="<?php echo get_the_ID() ?>" class="<?php echo esc_attr($btn_class);?>">	
	<span class="btn-text"><?php echo esc_html($btn_label);?></span>
</button>