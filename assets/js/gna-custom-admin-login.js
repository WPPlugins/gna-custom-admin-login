jQuery(document).ready(function($){
	updateCSS(false);
	
	$('.upload_logo_button').click(function() {
		tb_show('Select an image file for custom admin logo and then Click "Insert into Post" to select it.', 'media-upload.php?referer=gna-cal-settings-menu&type=image&TB_iframe=true&post_id=0', false);
		return false;
	});
	
	window.send_to_editor = function(html) {
		var imgUrl,
		srcCheck = $(html).attr('src');

		if (srcCheck && typeof srcCheck !== 'undefined') {
			imgUrl = srcCheck;
		} else {
			imgUrl = $('img', html).attr('src');
		}

		//console.log(imgUrl);
		$('#g_adminlogin_logo').val(imgUrl);
		tb_remove();
		
		$('#gna_logo_preview').attr('src', imgUrl);
		updateCSS(false);
	}
	
	$('#g_adminlogin_logo').on('change', function() {
		$('#gna_logo_preview').attr('src', $(this).val());
		updateCSS(false);
	});
	
	$('#g_adminlogin_custom_css').on('change', function() {
		updateCSS(false);
	});
	
	$('#load_example_css_button').click(function() {
		updateCSS();
	});
	
	function updateCSS(load) {
		if (load === undefined) {
			load = true;
		}

		exampleCSS = 'body.login {}' + '\n';
		exampleCSS += 'body.login div#login {}' + '\n';
		exampleCSS += 'body.login div#login h1 {}' + '\n';
		exampleCSS += 'body.login div#login h1 a {' + '\n';

			exampleCSS += 'padding: 0;' + '\n';
			exampleCSS += 'background-size: contain;' + '\n';
			exampleCSS += 'background-position: center center;' + '\n';
			exampleCSS += 'background-repeat: no-repeat;' + '\n';
			exampleCSS += 'background-color: transparent;' + '\n';
			exampleCSS += 'width: 100%;' + '\n';
			
		exampleCSS += '}' + '\n';
		exampleCSS += 'body.login div#login form#loginform {}' + '\n';
		exampleCSS += 'body.login div#login form#loginform p {}' + '\n';
		exampleCSS += 'body.login div#login form#loginform p label {}' + '\n';
		exampleCSS += 'body.login div#login form#loginform input {}' + '\n';
		exampleCSS += 'body.login div#login form#loginform input#user_login {}' + '\n';
		exampleCSS += 'body.login div#login form#loginform input#user_pass {}' + '\n';
		exampleCSS += 'body.login div#login form#loginform p.forgetmenot {}' + '\n';
		exampleCSS += 'body.login div#login form#loginform p.forgetmenot input#rememberme {}' + '\n';
		exampleCSS += 'body.login div#login form#loginform p.submit {}' + '\n';
		exampleCSS += 'body.login div#login form#loginform p.submit input#wp-submit {}' + '\n';
		exampleCSS += 'body.login div#login p#nav {}' + '\n';
		exampleCSS += 'body.login div#login p#nav a {}' + '\n';
		exampleCSS += 'body.login div#login p#backtoblog {}' + '\n';
		exampleCSS += 'body.login div#login p#backtoblog a {}' + '\n';

		if (load)
			$('#g_adminlogin_custom_css').val(exampleCSS);
		
		var backgroundImage = 'background-image:url(' + $('#g_adminlogin_logo').val() + ');';
		var cssPreview = backgroundImage + '\n' + $('#g_adminlogin_custom_css').val();
		$('#gna_preview_css_output').html( cssPreview );
	}
});