<?php
require_once "Error.php";
require_once "Scanner.php";
require_once "Parser.php";
require_once "XMLGenerator.php";


$stdin = fopen('php://stdin', 'r');
$stdout = fopen('php://stdout', 'w');
$stderr = fopen('php://stderr', 'w');
ini_set('display_errors', 'stderr');

if (!$stdin) {
    _Error::exit(_Error::INPUT_FILE_OPEN_ERROR, "Could not open file STDIN.");
}
if (!$stdout || !$stderr) {
    _Error::exit(_Error::OUTPUT_FILE_OPEN_ERROR, "Could not open file STDOUT or STDERR.");
}

// Parse arguments

$options = getopt("", array("help"));

if (count($argv) > 1) {
    if (array_key_exists("help", $options)) {
        if (count($argv) > 2) {
            _Error::exit(_Error::INVALID_ARGUMENTS_ERROR, "Invalid argument combination.");
        }

        echo "Lexikalny a syntakticky analyzator jazyka IPPcode22.

Tento script skontroluje lexikalnu a syntakticku spravnost zdrojoveho
kodu jazyka IPPcode22 a nasledne ho prevedie do XML formatu, ktory
sa vypisuje na standardny vystup.

--help\tVypis tejto napovedy.\n";

        exit(0);
    } else {
        _Error::exit(_Error::INVALID_ARGUMENTS_ERROR, "Invalid argument.");
    }
}

// Analysis

$scanner = new Scanner($stdin);
$generator = new XMLGenerator();
$parser = new Parser($scanner, $generator);

$parser->parse();

fclose($stdin);
fclose($stdout);
fclose($stderr);

exit(0);
