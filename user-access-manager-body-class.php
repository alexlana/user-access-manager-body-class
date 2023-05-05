<?php
/*
Plugin Name: User Access Manager Body Class
Plugin URI: -
Description: Acrescenta classe "permission-denied" na tag <body> quando não há permissão de acesso.
Author: -
Author URI: -
Text Domain: uam-bc
Version: 1.0
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function uam_bc ( $classes ) {
	global $wpdb, $post;

	$autorizado = false;

	$grupos = $wpdb->get_results( 'select * from ' . $wpdb->prefix . 'uam_accessgroup_to_object where object_id = ' . (int)$post->ID );

	if ( count($grupos) > 0 ) {

		foreach ( $grupos as $grupo ) {
			$permissoes = $wpdb->get_results( 'select * from ' . $wpdb->prefix . 'uam_accessgroup_to_object where group_id = ' . $grupo->group_id );
			foreach ( $permissoes as $permissao ) {
				if ( current_user_can( $permissao->object_id ) ) {
					$autorizado = true;
				}
			}
		}

		if ( $autorizado )
			$classes[] = 'permission-granted';
		else
			$classes[] = 'permission-denied';

	}

	return $classes;
}
add_action( 'body_class', 'uam_bc' );








