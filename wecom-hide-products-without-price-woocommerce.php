<?php
/*
 * Disable add to cart for products with 0 price
 */
add_filter( 'woocommerce_is_purchasable', 'wecom_disable_cart_on_missing_price', 10, 2 );
if ( ! function_exists( 'wecom_disable_cart_on_missing_price' ) ) {
	function wecom_disable_cart_on_missing_price( $is_purchasable, $product ) {

		if ( 0 == $product->get_price() ) {
			return false;
		}

		return $is_purchasable;
	}
}

/*
 * Exclude products with 0 price from the shop loop
 */
add_action( 'woocommerce_product_query', 'wecom_product_query_remove_missing_price_products', 20 );
if ( ! function_exists( 'wecom_product_query_remove_missing_price_products' ) ) {
	function wecom_product_query_remove_missing_price_products( $q ) {

		$meta_query = $q->get( 'meta_query' );

		$meta_query[] = array(
			'relation' => 'AND',
			array(
				'key'     => '_price',
				'value'   => array( 0.01, PHP_INT_MAX ),
				'compare' => 'BETWEEN',
			),
		);

		$q->set( 'meta_query', $meta_query );
	}
}


/*
 * Exclude products with 0 price from woocommerce [products] shortcode
 */
add_filter( 'woocommerce_shortcode_products_query', 'wecom_products_shortcode_remove_missing_price_products', 10, 1 );
if ( ! function_exists( 'wecom_products_shortcode_remove_missing_price_products' ) ) {
	function wecom_products_shortcode_remove_missing_price_products( $query_args ) {
		$meta_query = $query_args['meta_query'];
		if ( ! empty( $meta_query ) ) {
			$query_args['meta_query'] = array(
				$meta_query,
				array(
					'key'     => '_price',
					'value'   => array( 0.01, PHP_INT_MAX ),
					'compare' => 'BETWEEN',
				),
			);
		}
		return $query_args;
	}
}
