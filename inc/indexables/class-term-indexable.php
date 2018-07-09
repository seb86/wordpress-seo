<?php

/**
 * WPSEO plugin file.
 *
 * @package WPSEO\Internals
 */

class WPSEO_Term_Indexable extends WPSEO_Indexable {

	/**
	 * Creates a new Indexable from a passed object.
	 *
	 * @param $object_id
	 *
	 * @return WPSEO_Indexable
	 */
	public static function from_object( $object_id ) {
		$term = get_term( $object_id );

		if ( $term === null ) {
			throw new \InvalidArgumentException( 'Invalid object id passed' );
		}

		$link_count = new WPSEO_Link_Column_Count();
		$link_count->set( array( $object_id ) );

		return new self(
			array(
				'object_id'                   => (int) $object_id,
				'object_type'                 => 'term',
				'object_subtype'              => $term->taxonomy,
				'permalink'                   => get_term_link( $term ),
				'canonical'                   => self::get_meta_value( 'canonical', $term ),
				'title'                       => self::get_meta_value( 'title', $term ),
				'description'                 => self::get_meta_value( 'desc', $term ),
				'breadcrumb_title'            => self::get_meta_value( 'bctitle', $term ),
				'og_title'                    => self::get_meta_value( 'opengraph-title', $term ),
				'og_description'              => self::get_meta_value( 'opengraph-description', $term ),
				'og_image'                    => self::get_meta_value( 'opengraph-image', $term ),
				'twitter_title'               => self::get_meta_value( 'twitter-title', $term ),
				'twitter_description'         => self::get_meta_value( 'twitter-description', $term ),
				'twitter_image'               => self::get_meta_value( 'twitter-image', $term ),
				'is_robots_noindex'           => self::get_robots_noindex_value( self::get_meta_value( 'noindex', $term ) ),
				'is_robots_nofollow'          => null,
				'is_robots_noarchive'         => null,
				'is_robots_noimageindex'      => null,
				'is_robots_nosnippet'         => null,
				'primary_focus_keyword'       => self::get_meta_value( 'focuskw', $term ),
				'primary_focus_keyword_score' => (int) self::get_meta_value( 'linkdex', $term ),
				'readability_score'           => (int) self::get_meta_value( 'content_score', $term ),
				'is_cornerstone'              => false,
				'link_count'                  => null,
				'incoming_link_count'         => null,
				'created_at'                  => null,
				'updated_at'                  => null,
			)
		);
	}

	/**
	 * Updates the data and returns a new instance.
	 *
	 * @param array $data The data to update into a new instance.
	 *
	 * @return WPSEO_Indexable A new instance with the updated data.
	 */
	public function update( $data ) {
		$data = array_merge( $this->data, $data );

		return new self( $data );
	}

	/**
	 * Returns the needed term meta field.
	 *
	 * @param string $field The requested field.
	 * @param mixed  $term  The term object.
	 *
	 * @return bool|mixed The value of the requested field.
	 */
	protected static function get_meta_value( $field, $term ) {
		return WPSEO_Taxonomy_Meta::get_term_meta( $term, $term->taxonomy, $field );
	}
}