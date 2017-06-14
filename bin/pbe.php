<?php
declare(strict_types=1);

use Twig\Loader\FilesystemLoader;

chdir(__DIR__ . '/../');
require_once('vendor/autoload.php');

$settings = require('config/app.config.php');
$files = glob($settings['examples'] . '*.php');

$renderer = getRenderer();

$examples = [];
$toc = [];
foreach ($files as $file) {
    $example = require($file);
    $example['filename'] = $file;
    $examples[] = $example;
    $toc[$example['title']] = str_replace(
        '.php',
        '.html',
        str_replace($settings['examples'], '', $file)
    );

}

// Loop twice because YOLO
foreach ($examples as $example) {
    handleFile($example, $settings, $toc);
}

function handleFile(array $example, array $settings, array $toc) : void
{
    $example['code'] = file_get_contents(str_replace($settings['examples'], $settings['examples'] . 'code/',
        $example['filename']));
    $example['toc'] = $toc;
    $example['rawname'] = str_replace($settings['examples'], '', $example['filename']);
    $example['rawname'] = str_replace('.php', '.html', $example['rawname']);
    $outputFile = str_replace($settings['examples'], $settings['output'], $example['filename']);
    $outputFile = str_replace('.php', '.html', $outputFile);

    $html = renderTemplate($example);

    if (!file_exists($settings['output'])) {
        mkdir($settings['output']);
    }

    file_put_contents($outputFile, $html);
}

function renderTemplate(array $example) : string
{
    global $renderer;

    return $renderer->render('template.twig', $example);
}

function getRenderer()
{
    $loader = new FilesystemLoader(['template/']);
    $renderer = new Twig_Environment($loader, ['debug' => true]);
    $renderer->addExtension(new Twig_Extension_Debug());
    $renderer->addExtension(new Jralph\Twig\Markdown\Extension(
        new Jralph\Twig\Markdown\Parsedown\ParsedownExtraMarkdown()
    ));
    $filter = new Twig_Filter('strippara', function (string $string) {
        $string = ltrim($string, '<p>');
        $string = rtrim($string, '</p>');

        return $string;
    });
    $renderer->addFilter($filter);

    return $renderer;
}