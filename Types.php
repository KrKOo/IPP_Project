<?php
enum TokenType
{
    case None;
    case Opcode;
    case Variable;
    case Symbol;
    case Label;
    case Type;
}
class Argument
{
    public string $type;
    public string $value;

    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}

class InstructionParams
{
    public $arguments = array();

    public function __construct(
        TokenType $arg1 = TokenType::None,
        TokenType $arg2 = TokenType::None,
        TokenType $arg3 = TokenType::None
    ) {
        if ($arg1 != TokenType::None) array_push($this->arguments, $arg1);
        if ($arg2 != TokenType::None) array_push($this->arguments, $arg2);
        if ($arg3 != TokenType::None) array_push($this->arguments, $arg3);
    }
}

class Instruction
{
    public $opcode;
    public $args = array();

    public function __construct(string $opcode)
    {
        $this->opcode = $opcode;
    }

    public function addArgument(Argument $arg)
    {
        array_push($this->args, $arg);
    }
}
