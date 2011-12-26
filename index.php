<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
ini_set('zlib.output_compression', 'On');//gzip

// Load Smarty
require_once __DIR__ . '/Smarty/Smarty.class.php';
$smarty = new Smarty();

// Configure
$smarty->setAutoloadFilters(array(
	Smarty::FILTER_OUTPUT => array('trimwhitespace')
));
$smarty->setCacheDir(__DIR__ . '/cache');
$smarty->setCompileDir(__DIR__ . '/cache/compiled');
$smarty->setTemplateDir(__DIR__ . '/templates');

$smarty->caching = Smarty::CACHING_LIFETIME_SAVED;

$smarty->compile_check    = false;
$smarty->error_reporting  = E_ALL | E_STRICT;
$smarty->error_unassigned = true;
//$smarty->force_compile    = true;
$smarty->merge_compiled_includes = true;

// Hooks
$smarty->registerPlugin(Smarty::PLUGIN_COMPILER, 'class', function($tag_arg, $smarty) {
	$blocks = $smarty->template->block_data;
	if(isset($blocks['menu'], $blocks['sidebar'])) {
		return 'body-full';
	}
	if(isset($blocks['menu'])) {
		return 'body-simple';
	}
	if(isset($blocks['sidebar'])) {
		return 'body-extended';
	}
	return 'body-wide';
});

// Render
$smarty->assign(array(
	'language' => 'nl',
	'title'    => 'Smarty'
));
if(isset($_GET['layout']) && in_array($_GET['layout'], array('extended', 'full', 'simple', 'wide'))) {
	$smarty->display($_GET['layout'] . '.html');
}
else {
	$smarty->display('simple.html');
}