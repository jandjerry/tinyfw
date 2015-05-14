<?php if( isset( $error ) ): ?>
    <?php if( $error == 'need_manual_delete' && isset( $url ) ):?>
        Current url: <?php echo $url; ?> <br/>
        Proxy for this url is already existed and in use. To re-test and re-create, 
        <a onClick="javascript:return confirm('Are you sure?');" href="/proxy/test/retest/<?php echo urlencode( urldecode( $url ) ); ?>">Click here </a>.
    <?php elseif( $error =='invalid_url' && isset( $url ) ):?>
        Invalid url: <?php echo $url; ?>
    <?php else: ?>
        Unknow error!
    <?php endif;?>
<?php elseif( isset( $msg )  && isset( $url ) ) : ?>
Redirecting...<a href="<?php echo $url; ?>">Click Here</a> if this is not redirect automatically.
<?php else: ?>
Unknow error!
<?php endif;?>