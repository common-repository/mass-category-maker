<?php
/* 
 * Plugin Name:   Mass Category Maker
 * Version:       0.0.1
 * Plugin URI:    http://www.hotyear.com
 * Description:   Mass Category Maker
 * Author:        Jesse
 * Author URI:    http://www.hotyear.com
 */

add_action('admin_menu', 'mcm_add_admin_pages');

function mcm_add_admin_pages(){
	global $allcats;
	
	   add_options_page('Mass Category Maker', 'Mass Category Maker', 10, __FILE__, 'mcm_main');
	  if(!empty($_POST['categories'])){
		check_admin_referer( 'mcm_update'); // nonce
		
		if(!empty($_POST['parent_category']))

			mcm_core($_POST['categories'],$_POST['parent_category']);
		else

			mcm_core($_POST['categories']);
	 }
	
	
	
}

function mcm_core($categories,$parent=null){
	
	if(!function_exists('wp_create_category')) 
		require_once(ABSPATH.'wp-admin/includes/taxonomy.php');
	
	if( !empty($parent) && ($parent_cat=get_cat_id($parent)) == 0 )
		$parent_cat=wp_create_category( $parent );
	
	$cats=preg_split('/(\r\n|\r|\n)/',$categories);
	global $wp_object_cache;
	
	foreach($cats as $cat){
		
	    	if($cat){	
			// 0, if failure and ID of category on success.
			if( 0 == get_cat_id($cat) ){
				$rtn_id=wp_create_category($cat,$parent_cat);
				
				
			}
	
		}
		
		
	}
	clean_term_cache('','category');
	
	
}

function mcm_main($allcats){   ?>

	<div class="wrap">
<?php
	if($_POST['action'] == 'mcm_success' && isset($_POST['mcm_create']) )
		echo	'<div id="message" class="updated fade">Categories are created!</div>';
		
?>
      <h2>Mass Category Maker</h2>
	<p>For people who use wordpress 3.0 or 3.0.1, please look at this ticket http://core.trac.wordpress.org/ticket/14485</p>
      <form method="post" action="">
      <?php wp_nonce_field('mcm_update'); ?>
      Parent Category:<p><input type="text" name="parent_category" value="" ></p>
      Categories:<p><textarea name="categories" id="categories" rows="10" cols="30" value=""></textarea></p>
      <p class="note">one category per line</p>
      <p><input type="hidden" name="action" value="mcm_success" /></p>
      <p><input type="submit" name="mcm_create" class="button" value="Create" /></p>
      </form>
   </div>
<?php }?>
