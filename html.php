<?php
function asanaconnect_html() {
?>
   <form action='options.php' method='post' style="background-color: #fff; padding: 1em 2em; margin: 20px 20px 20px 0; box-shadow: 0 0 1px #000;">
        <h1>Asana</h1>
        <?php
            settings_fields( 'asanaconnect' );
            do_settings_sections( 'asanaconnect' );
            submit_button();
        ?>
    </form>
<?php
}

