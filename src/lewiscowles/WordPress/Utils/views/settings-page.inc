<?php
$page_title = ( isset($myOptions['page_title']) ? $myOptions['page_title'] : '' );
$group = ( isset($myOptions['group']) ? $myOptions['group'] : '' );
?>
<div class="wrap">
<h2><?php echo $page_title; ?></h2>
<p>&nbsp;</p>
<form method="post" action="options.php">
    <?php settings_fields( $group ); ?>
    <?php do_settings_sections( $group ); ?>
    <div class="row">
        <div class="col">
        <?php foreach($myOptions['options'] as $n => $option) : ?>
            <?php if( is_null( $option ) ): continue; endif; ?>
            <?php if( ( $n + 1 ) % 10 == 0 ) : ?></div><div class="col"><?php endif; ?>
            <?php $opVal = isset($option['default']) ? get_option($option['name'], $option['default']) : get_option($option['name']); ?>
            <div class="row">
                <label for="<?php echo $option['name']; ?>" ><?php echo $option['dname']; ?></label>
            <?php 
            switch($option['type']) {
                case 'textarea':
            ?>
                <textarea name="<?php echo $option['name']; ?>" id="<?php echo $option['name']; ?>" <?php if(isset($option['placeholder'])): ?>placeholder="<?php echo $option['placeholder']; ?>"<?php endif;?> ><?php echo $opVal; ?></textarea>
            <?php
                break;
                case 'text':
            ?>
                <input type="text" name="<?php echo $option['name']; ?>" id="<?php echo $option['name']; ?>" value="<?php echo $opVal; ?>" <?php if(isset($option['placeholder'])): ?>placeholder="<?php echo $option['placeholder']; ?>"<?php endif;?> />
            <?php
                break;
                default:
            ?>
                &nbsp;
            <?php } ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <?php submit_button(); ?>
    </div>
</form>
</div>
