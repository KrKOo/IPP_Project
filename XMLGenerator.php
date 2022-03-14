<?php
require_once "Parser.php";
require_once "Types.php";

class XMLGenerator
{
    private $xml;
    private $program;

    public function __construct()
    {
        $this->xml = new DOMDocument("1.0", "UTF-8");
        $this->xml->formatOutput = true;

        $this->program = $this->generateRoot();
    }

    public function generate()
    {
        $this->xml->appendChild($this->program);
        echo $this->xml->saveXML();
    }

    private function generateRoot()
    {
        $root = $this->xml->createElement("program");
        $root->setAttribute("language", "IPPcode22");
        return $root;
    }

    public function generateInstruction(Instruction $instruction, int $order)
    {
        $instructionElm = $this->xml->createElement("instruction");
        $instructionElm->setAttribute("opcode", strtoupper($instruction->opcode));
        $instructionElm->setAttribute("order", $order);

        for ($i = 0; $i < count($instruction->args); $i++) {
            $argumentElm = $this->generateArgument($instruction->args[$i], $i + 1);
            $instructionElm->appendChild($argumentElm);
        }

        $this->program->appendChild($instructionElm);
    }

    private function generateArgument(Argument $argument, int $id)
    {
        $argumentElm = $this->xml->createElement("arg" .  $id, $argument->value);
        $argumentElm->setAttribute("type", $argument->type);

        return $argumentElm;
    }
}
