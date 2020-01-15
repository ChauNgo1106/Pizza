<!DOCTYPE html>
<html>
    <body>
        <main>
            <h1>Error</h1>
            <p><?php echo $error; ?></p>
            <p> <?php debug_print_backtrace(); ?></p>
            <?php if ($error instanceof Exception) {
                echo '<p> at line ' . $e->getLine() . ' in file ' . $e->getFile() . '</p>';
                echo '<p> full backtrace:<br>';
                echo str_replace("\n", '<br>', $e->getTraceAsString());
                echo '</p>';
            }
            ?>
        </main>
    </body>
</html>
