<?php
/**
 * WordPress script for delete variations without parent product.
 *
 * @link       https://tabernawp.com/
 * @author     carlos@longarela.eu
 * @since      1.0.0
 * @package    delete_variations
 */

define( 'SHORTINIT', true ); // We declare shortinit for minimal wp funcionality.

$doc_root = $_SERVER['DOCUMENT_ROOT'];
require_once( $doc_root . '/wp-load.php' ); // Delete / if your webrserver root is definid with final slash.

$execute = true; // Here we define if script must be executed.

$table_prefix = $wpdb->prefix;

if ( empty( $_GET['delete'] ) ) {
	$sql = "SELECT o.ID, o.post_parent FROM " . $table_prefix . "posts o
		LEFT OUTER JOIN " . $table_prefix . "posts r
		ON o.post_parent = r.ID
		WHERE r.id IS null AND o.post_type = 'product_variation'";

	$products = $wpdb->get_results( $sql );

	$n_orphaned_products = count( $products );

	echo '<h2>' . $n_orphaned_products . ' orphaned products to delete.</h2>';

	if ( ! empty( $n_orphaned_products ) ) {
		echo '<div style="font-style:bold;text-align:center;margin:10px auto;"><a href="?delete=yes">Delete ' . $n_orphaned_products . ' orphaned products</a></div>';
	}

	foreach ( $products as $product ) {
		echo '<p>Product with id ' . $product->ID . ' says that parent product is ' . $product->post_parent . ' but that parent product do not exists.</a></p>';
	}
} elseif ( 'yes' === $_GET['delete'] ) {
	if ( $execute ) { // Delete orphaned variations.
		$sql = "DELETE o FROM " . $table_prefix . "posts o
			LEFT OUTER JOIN " . $table_prefix . "posts r
			ON o.post_parent = r.ID
			WHERE r.id IS null AND o.post_type = 'product_variation'";

		$res = $wpdb->query( $sql );
		echo '<h2>Query executed</h2>';
		//echo '<h3>' . $sql . '</h3>';
	} else {
		echo '<h2>Yo can not delete orphaned products, change <em>$execute = false;</em> to <em>$execute = true;</em> in this PHP script</h2>';
	}
}
