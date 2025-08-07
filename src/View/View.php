<?php

namespace ModPath\View;

class View
{
    protected static array $data = [];

    protected static string $baseViewPath;

    public static function setBasePath(string $path)
    {
        self::$baseViewPath = $path;
    }

    public static function render(string $templatePath, array $data = []): void
    {
        self::$data = $data;

        $filePath = self::$baseViewPath . str_replace('.', '/', $templatePath) . '.php';

        if (!file_exists($filePath)) {
            throw new \RuntimeException("Template not found: {$filePath}");
        }

        $template = file_get_contents($filePath);
        $compiled = self::compile($template);

        ob_start();
        extract(self::$data, EXTR_SKIP);
        eval('?>' . $compiled);
        echo ob_get_clean();
    }

    protected static function compile(string $template): string
    {
        // Interpolation: {{ $var }}
        $template = preg_replace_callback('/\{\{\s*(.+?)\s*\}\}/', function ($matches) {
            return '<?= htmlspecialchars(' . $matches[1] . ') ?>';
        }, $template);

        // If / Elseif / Else / Endif
        $template = preg_replace('/<if\((.+?)\)>/', '<?php if ($1): ?>', $template);
        $template = preg_replace('/<elseif\((.+?)\)>/', '<?php elseif ($1): ?>', $template);
        $template = str_replace('<else/>', '<?php else: ?>', $template);
        $template = str_replace('</if>', '<?php endif; ?>', $template);

        // Loop / Endloop
        $template = preg_replace('/<loop\((.+?)\)>/', '<?php foreach ($1): ?>', $template);
        $template = str_replace('</loop>', '<?php endforeach; ?>', $template);

        // For / Endfor
        $template = preg_replace('/<for\((.+?)\)>/', '<?php for ($1): ?>', $template);
        $template = str_replace('</for>', '<?php endfor; ?>', $template);

        // Include other templates <include('partials/header')/>
        $template = preg_replace_callback('/<include\(\'(.+?)\'\)\/>/', function ($matches) {
            $includePath = self::$baseViewPath . str_replace('.', '/', $matches[1]) . '.php';

            if (!file_exists($includePath)) {
                throw new \RuntimeException("Included template not found: {$includePath}");
            }

            // Recursively compiles the contents of the include
            $includedContent = file_get_contents($includePath);
            return self::compile($includedContent);
        }, $template);

        return $template;
    }
}
