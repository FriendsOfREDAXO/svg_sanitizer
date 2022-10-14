<?php

use Rhukster\DomSanitizer\DOMSanitizer;

/**
 * EP before the image is uploaded
 */
rex_extension::register('MEDIA_ADD', static function (rex_extension_point $ep)
{
    $type = $ep->getParam('type');

    if ($type === 'image/svg+xml') {
        $file = $ep->getParam('file');
        $filePath = $file['path'];

        /**
         * sanitize svg
         */
        $sanitizer = new DOMSanitizer(DOMSanitizer::SVG);
        $output = $sanitizer->sanitize(rex_file::get($filePath), [
            'remove-namespaces' => false,
            'remove-php-tags' => true,
            'remove-html-tags' => true,
            'remove-xml-tags' => true,
            'compress-output' => true,
        ]);

        /**
         * write file contents
         */
        rex_file::put($filePath, $output);
    }
});
