<?php
require_once "Scanner.php";
require_once "Opcodes.php";

class Parser
{
    private Scanner $scanner;
    private XMLGenerator $generator;

    public function __construct(Scanner $scanner, XMLGenerator $generator)
    {
        $this->scanner = $scanner;
        $this->generator = $generator;
    }

    # Main parsing function
    public function parse()
    {
        $tokenStrings = $this->scanner->getTokenStrings();

        if ($tokenStrings == false || count($tokenStrings) != 1 || strtoupper($tokenStrings[0]) != ".IPPCODE22") {
            _Error::exit(_Error::INVALID_HEADER_ERROR, "Invalid header.");
        }

        $counter = 1;
        do {
            $tokenStrings = $this->scanner->getTokenStrings();
            if ($tokenStrings == false) {
                $this->generator->generate();
                return;
            }
            $instruction = $this->parseTokenStrings($tokenStrings);
            $this->generator->generateInstruction($instruction, $counter++);
        } while (true);
    }

    # Parse tokens of one instruction
    private function parseTokenStrings($tokenStrings)
    {
        global $instructionParams;
        $opcode = strtoupper($tokenStrings[0]);

        if (!array_key_exists($opcode, $instructionParams)) {
            _Error::exit(_Error::INVALID_OPCODE_ERROR, "Invalid opcode.");
        }

        $argumentTypes = $instructionParams[$opcode]->arguments;
        $instructionArgumentCount = count($argumentTypes);

        if (count($tokenStrings) != ($instructionArgumentCount + 1)) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid argument count.");
        }

        $instruction = new Instruction($opcode);

        foreach ($argumentTypes as $key => $argumentType) {

            switch ($argumentType) {
                case TokenType::Variable:
                    // [LF, TF, GF]@id
                    $argument = $this->parseVariableString($tokenStrings[$key + 1]);
                    break;
                case TokenType::Symbol:
                    // type@value
                    $argument = $this->parseSymbolString($tokenStrings[$key + 1]);
                    break;
                case TokenType::Label:
                    // id
                    $argument = $this->parseLabelString($tokenStrings[$key + 1]);
                    break;
                case TokenType::Type:
                    // [int, string, bool]
                    $argument = $this->parseTypeString($tokenStrings[$key + 1]);
                    break;
                default:
                    _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid token type.");
                    break;
            }

            $instruction->addArgument($argument);
        }

        return $instruction;
    }

    # Parse an argument representing a variable
    private function parseVariableString(string $variableString)
    {
        if (!str_contains($variableString, '@')) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Variable without '@'.");
        }

        list($frame, $identifier) = explode('@', $variableString, 2);

        if (!$this->isValidFrame($frame)) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid frame.");
        }

        if (!$this->isValidIdentifier($identifier)) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid identifier name.");
        }

        return new Argument("var", $variableString);
    }

    # Parse an argument representing a symbol (Varialble or Constant)
    private function parseSymbolString(string $symbolString)
    {
        if (!str_contains($symbolString, '@')) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Variable without '@'.");
        }

        list($type, $value) = explode('@', $symbolString, 2);

        if (!$this->isValidType($type, true)) {
            return $this->parseVariableString($symbolString);
        }

        if ($type == "nil" && $value != "nil") {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid nil constant.");
        }

        if ($type == "bool" && !in_array($value, array("true", "false"))) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid bool value.");
        }

        if ($type == "int" && !$this->isValidNumber($value)) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid int value.");
        }

        if ($type == "string" && substr_count($value, "\\") != preg_match_all("/\\\\\d{3}/", $value)) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid string value.");
        }

        return new Argument($type, $value);
    }

    # Parse an argument representing a Label
    private function parseLabelString(string $labelString)
    {
        if (!$this->isValidIdentifier($labelString)) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid identifier name.");
        }

        return new Argument("label", $labelString);
    }

    # Parse an argument representing a Type
    private function parseTypeString(string $typeString)
    {
        if (!$this->isValidType($typeString)) {
            _Error::exit(_Error::LEX_OR_SYNTAX_ERROR, "Invalid type.");
        }

        return new Argument("type", $typeString);
    }

    # Validate Type string
    private function isValidType(string $typeString, bool $includeNil = false)
    {
        $types = ["int", "string", "bool"];

        if ($includeNil) {
            array_push($types, "nil");
        }

        return in_array($typeString, $types);
    }

    # Validate Frame string
    private function isValidFrame(string $frameString)
    {
        return in_array($frameString, array("LF", "TF", "GF"));
    }

    # Validate an identifier
    private function isValidIdentifier(string $identifier)
    {
        return preg_match("/^[a-z_\-$&%*!?]+[0-9a-z_\-$&%*!?]*$/i", $identifier);
    }

    # Validate a numeric string
    private function isValidNumber(string $numberString)
    {
        return preg_match("/^[\-\+]?\d+(?:\.\d+)?(?:e[\-\+]?\d+(?:\.\d+)?)?$|^0x[0-9a-f]+$|^0[0-7]+$/i", $numberString, $matches);
    }
}
