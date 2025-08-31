<?php
declare(strict_types=1);


function component($component_name, $data = [], $slot = null)
{
    extract($data);

    $slotContent = null;
    if ($slot instanceof Closure) {
        ob_start();
        $slot();
        $slotContent = ob_get_clean();
    }

    $slot = $slotContent;

    ob_start();
    include __DIR__ . "/../views/components/{$component_name}.php";
    $content = ob_get_clean();

    // * AUTO ECHO KAPAG NESTED CALL YAN SHA
    if (ob_get_level() > 1) {
        echo $content;
        return null;
    }

    return $content;
}