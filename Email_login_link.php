<?php
/*
* The ACF Text Area field content:

* something1 name - [something1];

* <a href="[link]">Somwhere link</a> 
*/

/*
* Put where need to send Email
*/
$user_id = 
$entry_code = get_user_meta( $user_id, 'paslink1', true );
if ($entry_code == '') {
    $hash1 = bin2hex(random_bytes(16));
    update_user_meta( $user_id, 'paslink1', $hash1 );
    $entry_code = $hash1;
}
    $link_to_send = home_url() . '/email_redirect/?ppc=' . rand ( 100 , 999 ) . $user_id . '&ppn=' . $curr_compet_code . '&ppf=' . $entry_code;

$message_raw = get_field('<<<Name of  Text Area field>>>', 'option');
    $patterns = array();
    $patterns[0] = '/\[something1\]/';
    // $patterns[1] = '/\[something2\]/'; 
    $patterns[2] = '/\[link\]/';
    $replacements = array();
    $replacements[0] = $something1;
    // $replacements[1] = $something2;
    $replacements[2] = $link_to_send;
$message = preg_replace($patterns, $replacements, $message_raw);
$headers = array(
    'From: <<< TITLE FROM >>> <<< info@DOMAIN_NAME important!!! >>>',
    'content-type: text/html'
);
$sent_mail = wp_mail( $to, $subject, $message, $headers );
?>

<!-- 
    Create a page name it:   email_redirect 
-->
<?php 

$ppc = $_GET['ppc'];
$ppn = $_GET['ppn'];
$ppf = $_GET['ppf'];

if ( is_string($_GET['ppc']) 
    && is_string($_GET['ppn']) 
    ) {

    $user_id = substr($_GET['ppc'], 3);
    $user = get_user_by( 'id', $user_id ); 
    $user_cod1 = get_user_meta( $user_id )['paslink1'][0];
    $user_cod_old = get_user_meta( $user_id )['paslink_old'][0];
    $user_cod_old_time = get_user_meta( $user_id )['paslink_old_time'][0] ? get_user_meta( $user_id )['paslink_old_time'][0] : '' ;

        if ($user) {
            if( $user_cod1 == $ppf ) {
                    wp_set_current_user( $user_id, $user->user_login );
                    wp_set_auth_cookie( $user_id );
                    do_action( 'wp_login', $user->user_login );
                    $hash1 = bin2hex(random_bytes(16));
                    // var_dump($hash);
                    $hash_old = get_user_meta( $user_id, 'paslink1', true );
                    update_user_meta( $user_id, 'paslink1', $hash1 );
                    update_user_meta( $user_id, 'paslink_old', $hash_old );
                    update_user_meta( $user_id, 'paslink_old_time', date("j M Y h:i a") );

                    wp_safe_redirect( home_url() . '/<<< PAGE TO REDIRECT >>>/?<<< PARAMETER NAME >>>=' . $ppn );
                    exit;
            }elseif ( $user_cod_old == $ppf ) {
                get_header(); ?>
                <style>
                    .container .info_block {
                        padding: 150px 0;
                        text-align: center;
                    }
                    .container .info_block h3 {
                        text-align: center;
                        font-size: 36px;
                        margin-bottom: 30px;
                    }
                    .container .info_block span {
                        font-size: 20px;
                        font-weight: 500;
                    }
                    .container .info_block p {
                        margin-top: 30px;
                        font-size: 26px;
                    }
                    .container .info_block p a {
                        color: var(--third_color);
                        font-weight: 600;
                    }
                </style>
                    <div class="container">
                        <div class="info_block">
                            <h3>Your link has been already used</h3>
                            <span> at <?php echo $user_cod_old_time ?></span>
                            <?php global $user_ID; ?>
                            <?php if (!$user_ID ) : ?>
                                <p>Please <a href="<?php echo home_url() . '/<<< sign in page link >>>/' ?>">login</p>
                            <?php endif; ?>
                            
                        </div>                            
                    </div>
                    <?php get_footer();
            }else {
                get_header(); ?>
                 <style>
                    .container .info_block {
                        padding: 150px 0;
                        text-align: center;
                    }
                    .container .info_block h3 {
                        text-align: center;
                        font-size: 36px;
                        margin-bottom: 30px;
                    }
                    .container .info_block span {
                        font-size: 20px;
                        font-weight: 500;
                    }
                    .container .info_block p {
                        margin-top: 30px;
                        font-size: 26px;
                    }
                    .container .info_block p a {
                        color: var(--third_color);
                        font-weight: 600;
                    }
                </style>
                <div class="container">
                    <div class="info_block">
                        <h3>Your link is wrong</h3>
                        <?php global $user_ID; ?>
                        <?php if (!$user_ID ) : ?>
                            <p>Please <a href="<?php echo home_url() . '/sign-in/' ?>">login</p>
                        <?php endif; ?>
                    </div>                            
                </div>
                <?php get_footer();
            }
        }
}

    // To check codes 
    // REMOVE IN PORODUCTION  !!!!
    echo '<pre>' . print_r(get_user_meta( $user_id, 'paslink1', true ), true) . '</pre>';
    echo '<pre>' . print_r(get_user_meta( $user_id, 'paslink_old', true ), true) . '</pre>';
    echo '<pre>' . print_r(get_user_meta( $user_id, 'paslink_old_time', true ), true) . '</pre>';

