<?php
//========================================================================
// Author:  Pascal KISSIAN
// Resume:  http://pascal.kissian.net
//
// Copyright (c) 2015-2020 Pascal KISSIAN
//
// Published under the MIT License
//          Consider it as a proof of concept!
//          No warranty of any kind.
//          Use and abuse at your own risks.
//========================================================================

$yakpro_po_base_directory = dirname(realpath($argv[0]));
$php_parser_git_commandline = 'git clone https://github.com/nikic/PHP-Parser.git';

$base_dir = dirname(__DIR__);
$php_parser_paths = [PHP_PARSER_DIRECTORY, $base_dir . '/' . PHP_PARSER_DIRECTORY, "$base_dir/../../nikic/php-parser"];
$php_parser_path = false;
foreach ($php_parser_paths as $path) {
  if (is_dir($path) && is_file("$path/lib/PhpParser/Builder.php")) {
    $php_parser_path = realpath($path);
    break;
  }
}
if (!$php_parser_path) {
  fprintf(STDERR, "Error:\tPHP-Parser is not correctly installed!%sYou can try to use the following command:%s\t# %s%s", PHP_EOL, PHP_EOL, $php_parser_git_commandline, PHP_EOL);
  exit(23);
}

$t_composer = json_decode(file_get_contents("$php_parser_path/composer.json"));   //print_r($t_composer);
$php_parser_branch = $t_composer->{'extra'}->{'branch-alias'}->{'dev-master'};
$required_php_version = $t_composer->{'require'}->{'php'};

$operator = '';
for ($i = 0; !ctype_digit($c = $required_php_version[$i]); ++$i) $operator .= $c;
$required_php_version = substr($required_php_version, $i);

if (substr($php_parser_branch, 0, 2) != '4.') {
  fprintf(STDERR, "Error:\tWrong version of PHP-Parser detected!%sCurrently, only 4.x branch of PHP-Parser is supported!%s\tYou can try to use the following command:%s\t# %s%s", PHP_EOL, PHP_EOL, PHP_EOL, $php_parser_git_commandline, PHP_EOL);
  exit(22);
}

if (!version_compare(PHP_VERSION, $required_php_version, $operator)) {
  fprintf(STDERR, "Error:\tPHP Version must be %s %s%s", $operator, $required_php_version, PHP_EOL);
  exit(23);
}
