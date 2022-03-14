<?php
abstract class _Error
{
    const OK = 0;
    const INVALID_ARGUMENTS_ERROR = 10;
    const INPUT_FILE_OPEN_ERROR = 11;
    const OUTPUT_FILE_OPEN_ERROR = 12;

    const INVALID_HEADER_ERROR = 21;
    const INVALID_OPCODE_ERROR = 22;
    const LEX_OR_SYNTAX_ERROR = 23;

    const INTERNAL_ERROR = 99;

    public static function exit($code, $message = null)
    {
        fwrite(STDERR, "$message\n");
        exit($code);
    }
}
