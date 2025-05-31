<?php

namespace Mini;

class View
{
    protected static array $data = [];

    public static function render(string $templatePath, array $data = []): void
    {
        self::$data = $data;
        $templatePath = dirname(__DIR__) . "/src/views/{$templatePath}.php";
        $template = file_get_contents($templatePath);
        $compiled = self::compile($template);

        ob_start();
        extract(self::$data, EXTR_SKIP);
        eval('?>' . $compiled);
        echo ob_get_clean();
    }

    protected static function compile(string $template): string
    {
        // {{ $var }} => php htmlspecialchars($var)
        $template = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?= htmlspecialchars($1) ?>', $template);

        // <if(condition)> => php if(condition):
        $template = preg_replace('/<if\((.+?)\)>/', '<?php if ($1): ?>', $template);
        $template = preg_replace('/<elseif\((.+?)\)>/', '<?php elseif ($1): ?>', $template);
        $template = str_replace('<else>', '<?php else: ?>', $template);
        $template = str_replace('<endif>', '<?php endif; ?>', $template);

        // <loop($items as $item)> => php foreach($items as $item):
        $template = preg_replace('/<loop\((.+?)\)>/', '<?php foreach ($1): ?>', $template);
        $template = str_replace('<endloop>', '<?php endforeach; ?>', $template);

        return $template;
    }
}