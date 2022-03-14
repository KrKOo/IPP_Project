<?php
require_once "Error.php";
require_once "Scanner.php";
require_once "Parser.php";
require_once "XMLGenerator.php";


$stdin = fopen('php://stdin', 'r');
$stdout = fopen('php://stdout', 'w');
$stderr = fopen('php://stderr', 'w');

if (!$stdin) {
    _Error::exit(_Error::INPUT_FILE_OPEN_ERROR, "Could not open file STDIN.");
}
if (!$stdout || !$stderr) {
    _Error::exit(_Error::OUTPUT_FILE_OPEN_ERROR, "Could not open file STDOUT or STDERR.");
}

$scanner = new Scanner($stdin);

$generator = new XMLGenerator();
$parser = new Parser($scanner, $generator);


$parser->parse();
