<?php
require_once "Types.php";

$var_symb           =   new InstructionParams(TokenType::Variable, TokenType::Symbol);
$var                =   new InstructionParams(TokenType::Variable);
$label              =   new InstructionParams(TokenType::Label);
$symb               =   new InstructionParams(TokenType::Symbol);
$var_symb_symb      =   new InstructionParams(TokenType::Variable, TokenType::Symbol, TokenType::Symbol);
$var_symb           =   new InstructionParams(TokenType::Variable, TokenType::Symbol);
$var_type           =   new InstructionParams(TokenType::Variable, TokenType::Type);
$label_symb_symb    =   new InstructionParams(TokenType::Label, TokenType::Symbol, TokenType::Symbol);
$no_args            =   new InstructionParams();

$instructionParams = array(
    "MOVE" => $var_symb,
    "INT2CHAR" => $var_symb,
    "STRLEN" => $var_symb,
    "TYPE" => $var_symb,

    "DEFVAR" => $var,
    "POPS" => $var,

    "CALL" => $label,
    "LABEL" => $label,
    "JUMP" => $label,

    "PUSHS" => $symb,
    "WRITE" => $symb,
    "EXIT" => $symb,
    "DPRINT" => $symb,

    "ADD" => $var_symb_symb,
    "SUB" => $var_symb_symb,
    "MUL" => $var_symb_symb,
    "IDIV" => $var_symb_symb,
    "LT" => $var_symb_symb,
    "GT" => $var_symb_symb,
    "EQ" => $var_symb_symb,
    "AND" => $var_symb_symb,
    "OR" => $var_symb_symb,
    "STRI2INT" => $var_symb_symb,
    "CONCAT" => $var_symb_symb,
    "GETCHAR" => $var_symb_symb,
    "SETCHAR" => $var_symb_symb,
    "NOT" => $var_symb,

    "READ" => $var_type,

    "JUMPIFEQ" => $label_symb_symb,
    "JUMPIFNEQ" => $label_symb_symb,

    "CREATEFRAME" => $no_args,
    "PUSHFRAME" => $no_args,
    "POPFRAME" => $no_args,
    "RETURN" => $no_args,
    "BREAK" => $no_args,
);
