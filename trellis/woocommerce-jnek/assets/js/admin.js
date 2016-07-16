/* 
name: main admin jquery
version: 1.0.1
package: WooCommerce JNE Shipping
*/
jQuery(function($) {
	
	// agenwebsite_jne_admin_params is required to continue, ensure the object exists
	if ( typeof agenwebsite_jne_admin_params === 'undefined' ) {
		return false;
	}
	
	jQuery(document).ready(function($) {
		/*
		 * Add reset button
		 *
		 * @since 1.0.0
		 */
		$('#last_tab').before('<input name="reset_default" type="submit" value="Kembali ke Settingan Awal" class="button button_secondary" id="reset_default">');
        
        $("#reset_default").click(function(){
            var e = confirm( agenwebsite_jne_admin_params.i18n_reset_default );
            return e?void 0:!1
        });
		
        jQuery('.premium-version').click(function(){
            alert("Pengaturan ini tidak berfungsi untuk free version. Jika Anda membutuhkan fungsi ini, anda harus mengupgrade ke full version.");
            return false;
        });
	
	});

});