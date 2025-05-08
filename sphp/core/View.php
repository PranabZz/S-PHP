<?php
namespace Sphp\Core;

class View
{
  public static function render($filename, $data = [])
  {
    extract($data);

    $viewPath = '../app/views/' . $filename;
    if (file_exists($viewPath)) {
      // Load file content
      $content = file_get_contents($viewPath);

      $content = preg_replace_callback("/@layout\('([^']+)'(?:\s*,\s*(\[.*?\]))?\)/", function ($matches) {
        $layoutPath = '../app/views/layout/' . $matches[1] . '.php';
        $variables = isset($matches[2]) ? eval ('return ' . $matches[2] . ';') : [];

        if (file_exists($layoutPath)) {
          ob_start();
          extract($variables);
          include $layoutPath;
          return ob_get_clean();
        }
        return "<!-- Layout '{$matches[1]}' not found -->";
      }, $content);

      $content = preg_replace_callback("/@component\('([^']+)'(?:\s*,\s*(\$[\w]+))?\)/", function ($matches) use ($data) {
        $componentPath = 'app/views/components/' . $matches[1] . '.php';
        $variables = [];

        if (isset($matches[2]) && isset($data[substr($matches[2], 1)])) {
          $variables = $data[substr($matches[2], 1)];
        }

        if (file_exists($componentPath)) {
          ob_start();
          extract($variables);
          include $componentPath;
          return ob_get_clean();
        }
        return "<!-- Component '{$matches[1]}' not found -->";
      }, $content);


      // Evaluate the resulting PHP content
      eval ('?>' . $content);
    } else {
      require('../app/views/404.html');
    }
  }
}
