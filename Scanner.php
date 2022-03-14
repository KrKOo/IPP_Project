<?php

require_once "Types.php";
require_once "Error.php";

class Scanner
{
    private $stream;

    function __construct($stream)
    {
        $this->stream = $stream;
    }

    public function getTokenStrings()
    {
        $line = $this->getLine();
        if (!$line) return false;

        $tokenStrings = $this->parseLine($line);

        return $tokenStrings;
    }

    private function getLine()
    {
        $line = "";
        do {
            $line = trim(fgets($this->stream));

            if (str_contains($line, '#')) {
                $line = substr($line, 0, strpos($line, "#"));
            }
        } while (empty($line) && !feof($this->stream));

        return $line;
    }

    private function parseLine(string $line)
    {
        $tokenStrings = preg_split('/\s+/', $line, -1, PREG_SPLIT_NO_EMPTY);
        return $tokenStrings;
    }
}
