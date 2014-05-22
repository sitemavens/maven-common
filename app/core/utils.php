<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Utils {

	public static function isEmpty( $value ) {

		//We need to add more validations, like array, strings, ints, etc.
		return empty( $value );
	}

	/**
	 * Convert a CamelCase string into a word separated string, using the splitter
	 * @param string $camel
	 * @param string $splitter
	 * @return string 
	 */
	public static function unCamelize( $camel, $splitter = "-" ) {

		return preg_replace(
			'/(^|[a-z])([A-Z])/e', 'strtolower(strlen("\\1") ? "\\1' . $splitter . '\\2" : "\\2")', $camel
		);
	}

//
//		$camel = preg_replace( '/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace( '/(?!^)[[:upper:]]+/', $splitter . '$0', $camel ) );
//		return strtolower( $camel );
//	}

	/*	 * Convert a underscore separated string into a CamelCase strings
	 * 
	 * @param string $str
	 * @param bool $capitaliseFirstChar
	 * @return string
	 */

	public function toCamelCase( $str, $capitaliseFirstChar = false ) {
		if ( $capitaliseFirstChar ) {
			$str[ 0 ] = strtoupper( $str[ 0 ] );
		}
		$func = create_function( '$c', 'return strtoupper($c[1]);' );
		return preg_replace_callback( '/_([a-z])/', $func, $str );
	}

//	/**
//	 * Return a permalink
//	 *
//	 * @param string $text
//	 * @return string
//	 *
//	 */
//	public static function to_permalink( $text ) {
//		$text = Utils::swap_accents( $text );
//
//		$text = preg_replace( '/[^a-z0-9_]/i', '_', $text );
//		$text = trim( preg_replace( '/_+/', '_', $text ), '_' );
//
//		return $text;
//	}
//
//
//	/**
//	 * Replace accents with letters.
//	 *
//	 * @param string $string
//	 * @return string
//	 *
//	 */
//	private static function swap_accents( $string ) {
//		$from = explode( ' ', 'à â ç é è ê ë ï î ô ù û ü ÿ' );
//		$to = explode( ' ', 'a a c e e e e i i o u u u y' );
//
//		$string = mb_strtolower( $string, 'UTF-8' );
//		$string = str_replace( $from, $to, $string );
//
//		return $string;
//	}
// swap_accents()

	 static function crypto_rand_secure( $min, $max ) {
		$range = $max - $min;
		if ( $range < 0 )
			return $min; // not so random...
		$log = log( $range, 2 );
		$bytes = ( int ) ($log / 8) + 1; // length in bytes
		$bits = ( int ) $log + 1; // length in bits
		$filter = ( int ) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec( bin2hex( openssl_random_pseudo_bytes( $bytes ) ) );
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ( $rnd >= $range );
		return $min + $rnd;
	}

	/**
	 * Return a random string of lenght $lenght.
	 * 
	 * @param type $length
	 * @return string
	 */
	public static function getToken( $length ) {
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		for ( $i = 0; $i < $length; $i ++  ) {
			$token .= $codeAlphabet[ Utils::crypto_rand_secure( 0, strlen( $codeAlphabet ) ) ];
		}
		return $token;
	}

	public static function updatePostParent( $postId, $parentId ) {

		$post = get_post( $postId );
		$parentPost = get_post( $parentId );
		if ( isset( $post ) && $post != null && isset( $parentPost ) && $parentPost != null ) {
			$post->post_parent = $parentPost->ID;

			wp_update_post( $post );
		} else {
			throw new \Maven\Exceptions\NotFoundException( 'The post doesn\'t exist: ' . $new_post_id );
		}
	}

	/*
	 * Function creates post duplicate as a draft and redirects then to the edit post screen
	 */

	public static function duplicatePost( $postId ) {
		global $wpdb;
		/*
		 * and all the original post data then
		 */
		$post = get_post( $postId );

		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		 * if post data exists, create the post duplicate
		 */
		if ( isset( $post ) && $post != null ) {

			/*
			 * new post data array
			 */
			$args = array(
			    'comment_status' => $post->comment_status,
			    'ping_status' => $post->ping_status,
			    'post_author' => $new_post_author,
			    'post_content' => $post->post_content,
			    'post_excerpt' => $post->post_excerpt,
			    'post_name' => $post->post_name,
			    'post_parent' => $post->post_parent,
			    'post_password' => $post->post_password,
			    'post_status' => 'draft',
			    'post_title' => $post->post_title,
			    'post_type' => $post->post_type,
			    'to_ping' => $post->to_ping,
			    'menu_order' => $post->menu_order
			);

			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $postId, $taxonomy );
				for ( $i = 0; $i < count( $post_terms ); $i ++  ) {
					wp_set_object_terms( $new_post_id, $post_terms[ $i ]->slug, $taxonomy, true );
				}
			}

			/*
			 * duplicate all post meta
			 */
			$post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$postId" );
			if ( count( $post_meta_infos ) != 0 ) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ( $post_meta_infos as $meta_info ) {
					$meta_key = $meta_info->meta_key;
					$meta_value = addslashes( $meta_info->meta_value );
					$sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
				$sql_query.= implode( " UNION ALL ", $sql_query_sel );
				$wpdb->query( $sql_query );
			}


			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			return $new_post_id;
		} else {
			throw new \Maven\Exceptions\NotFoundException( 'The post doesn\'t exist: ' . $new_post_id );
		}
	}

}
