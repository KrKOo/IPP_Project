## Implementačná dokumentácia k 1. úlohe do IPP 2021/2022

**Meno a priezvisko:** Kristián Kováč

**Login:** xkovac61

## Popis implementácie

Script je implementovaný v troch hlavných moduloch (`Scanner.php`, `Parser.php` a `XMLGenerator.php`).

### Scanner.php

Tento modul je zodpovedný za načítanie zdrojového kódu zo zadaného súboru a následné rozdelenie riadkov do jednotlivých tokenov.
Verejná funkcia `getTokenStrings()` vráti pole tokenov z jedného načítaného riadku. Zároveň sa pri načítavaní riadkov ignorujú prázdne riadky a komentáre.

### Parser.php

Tento modul kontroluje lexikálnu a sytanktickú správnosť jednotlivých inštrukcií. Verejná funkcia `parse()` postupne získava inštrukcie vo forme tokenov, ktoré sa pomocou funkcie `parseTokenStrings()` prevádzajú na typ `Instruction`. Typ `Instruction` obsahuje opcode a argumenty (typ `Argument`) inštrukcie. Všetky inštrukcie a ich parametre sú definované v súbore `Opcodes.php`

### XMLGenerator.php

V tomto module verejná funkcia `generateInstruction()` postupne dostáva od `Parser.php` inštruckie, ktoré následne prevádza do XML formátu a ukladá do svojho vnútorného bufferu. Funckia `generate()` tento buffer vypíše na štandardný výstup.

## Dátové štruktúry(`Types`)

**`TokenType`** - Enumerácia všetkých typov tokenov.

**`Argument`** - Typ a hodnota argumentu.

**`InstructionParams`** - Pole typov parametrov inštrukcie.

**`Instruction`** - Opcode a argumenty inštrukcie.
