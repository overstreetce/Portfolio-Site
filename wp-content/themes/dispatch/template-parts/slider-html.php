<?php
global $hoot_theme, $hoot_style_builder;

if ( !isset( $hoot_theme->slider ) || empty( $hoot_theme->slider ) )
	return;

// Ok, so we have a slider to show. Now, lets display the slider.

/* Let developers alter slider via global $hoot_theme */
do_action( 'hoot_slider_start', 'html' );

/* Create Data attributes for javascript settings for this slider */
$atts = $class = '';
if ( isset( $hoot_theme->sliderSettings ) && is_array( $hoot_theme->sliderSettings ) ) {

	if ( !empty( $hoot_theme->sliderSettings['class'] ) )
		$class .= ' ' . sanitize_html_class( $hoot_theme->sliderSettings['class'] );

	if ( !empty( $hoot_theme->sliderSettings['id'] ) )
		$atts .= ' id="' . sanitize_html_class( $hoot_theme->sliderSettings['id'] ) . '"';
	foreach ( $hoot_theme->sliderSettings as $setting => $value )
		$atts .= ' data-' . $setting . '="' . esc_attr( $value ) . '"';

}

/* Start Slider Template */
$slide_count = 1; ?>
<ul class="lightSlider<?php echo $class; ?>"<?php echo $atts; ?>><?php
	foreach ( $hoot_theme->slider as $slide ) :
		if ( !empty( $slide['image'] ) || !empty( $slide['content'] ) || !empty( $slide['title'] ) ) :

			$slidestyle = '';
			$slidestylearray = $hoot_style_builder->css_rule_sanitized_array( 'background-color', $slide['background'] );
			if( is_array( $slidestylearray ) ) {
				foreach ( $slidestylearray as $property => $value ) {
					$slidestyle .= " $property: " . $value['value'] . ';';
				}
			}

			$slide['button'] = empty( $slide['button'] ) ? __('Know More', 'dispatch') : $slide['button'];

			// Start Slide
			?><li class="lightSlide hootslider-html-slide hootslider-html-slide-<?php echo $slide_count; $slide_count++; ?>" <?php if ( !empty( $slidestyle ) ) echo ' style="' . esc_attr( $slidestyle ) . '"'; ?>>

				<?php if ( !empty( $slide['image'] ) ) { ?>
					<div class="hootslider-html-slide-img">
						<img src="<?php echo esc_url( $slide['image'] ); ?>">
					</div>
				<?php } ?>

				<?php if ( !empty( $slide['content'] ) || !empty( $slide['title'] ) || !empty( $slide['url'] ) ) { ?>
					<div class="hootslider-html-slide-entry">
						<div class="grid">
							<div class="column-1-2">
								<?php if ( !empty( $slide['title'] ) ) { ?>
									<h3 class="hootslider-html-slide-title invert-typo">
										<?php if ( !empty( $slide['url'] ) ) { ?>
											<a href="<?php echo esc_url( $slide['url'] ); ?>">
										<?php } ?>
										<?php echo esc_html( $slide['title'] ) ; ?>
										<?php if ( !empty( $slide['url'] ) ) { ?>
											</a>
										<?php } ?>
									</h3>
								<?php } ?>
								<?php if ( !empty( $slide['content'] ) || !empty( $slide['url'] ) ) { ?>
									<div class="hootslider-html-slide-content linkstyle">
										<?php
										if ( !empty( $slide['content'] ) )
											echo wp_kses_post( wpautop( $slide['content'] ) );
										if ( !empty( $slide['url'] ) && !empty( $slide['button'] ) )
											echo '<a class="hootslider-html-slide-button more-link" href="' . esc_url( $slide['url'] ) . '">' . $slide['button'] . '</a>';
										 ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>

			</li><?php

		endif;
	endforeach; ?>
</ul>