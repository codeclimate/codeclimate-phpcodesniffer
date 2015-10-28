<?php

namespace Sniffs;

class Sniffs
{
    const DEFAULT_POINTS = 70000;
    const INVALID_SNIFFS = array(
        "Internal.NoCodeFound",
        "Internal.Tokenizer.Exception"
    );

    public static $sniffs = array(
        "Generic.ControlStructures.InlineControlStructure.NotAllowed" => 60000,
        "Generic.Files.LineEndings.InvalidEOLChar" => 60000,
        "Generic.Files.LineLength.TooLong" => 60000,
        "Generic.Functions.FunctionCallArgumentSpacing.NoSpaceAfterComma" => 50000,
        "Generic.WhiteSpace.DisallowTabIndent.TabsUsed" => 60000,
        "Generic.WhiteSpace.ScopeIndent.Incorrect" => 50000,
        "Generic.WhiteSpace.ScopeIndent.IncorrectExact" => 50000,
        "PEAR.Functions.ValidDefaultValue.NotAtEnd" => 60000,
        "PSR1.Classes.ClassDeclaration.MissingNamespace" => 600000,
        "PSR1.Classes.ClassDeclaration.MultipleClasses" => 400000,
        "PSR1.Files.SideEffects.FoundWithSymbols" => 500000,
        "PSR2.ControlStructures.ControlStructureSpacing.SpacingAfterOpenBrace" => 50000,
        "PSR2.ControlStructures.ElseIfDeclaration.NotAllowed" => 50000,
        "PSR2.ControlStructures.SwitchDeclaration.TerminatingComment" => 50000,
        "PSR2.Files.ClosingTag.NotAllowed" => 50000,
        "PSR2.Files.EndFileNewline.NoneFound" => 50000,
        "PSR2.Methods.FunctionCallSignature.CloseBracketLine" => 50000,
        "PSR2.Methods.FunctionCallSignature.ContentAfterOpenBracket" => 50000,
        "PSR2.Methods.FunctionCallSignature.Indent" => 50000,
        "PSR2.Methods.FunctionCallSignature.MultipleArguments" => 70000,
        "PSR2.Methods.FunctionCallSignature.SpaceAfterOpenBracket" => 50000,
        "PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket" => 50000,
        "PSR2.Methods.FunctionCallSignature.SpaceBeforeOpenBracket" => 50000,
        "Squiz.Classes.ValidClassName.NotCamelCaps" => 50000,
        "Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace" => 50000,
        "Squiz.ControlStructures.ControlSignature.SpaceAfterCloseBrace" => 50000,
        "Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis" => 50000,
        "Squiz.ControlStructures.ControlSignature.SpaceAfterKeyword" => 50000,
        "Squiz.ControlStructures.ControlSignature.SpaceBeforeSemicolon" => 50000,
        "Squiz.ControlStructures.ForLoopDeclaration.NoSpaceAfterFirst" => 50000,
        "Squiz.ControlStructures.ForLoopDeclaration.NoSpaceAfterSecond" => 50000,
        "Squiz.Functions.FunctionDeclarationArgumentSpacing.NoSpaceBeforeArg" => 50000,
        "Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine" => 50000,
        "Squiz.Functions.MultiLineFunctionDeclaration.ContentAfterBrace" => 50000,
        "Squiz.WhiteSpace.ScopeClosingBrace.ContentBefore" => 50000,
        "Squiz.WhiteSpace.ScopeClosingBrace.Indent" => 50000,
        "Squiz.WhiteSpace.SuperfluousWhitespace.EndLine" => 50000,
    );

    public static function pointsFor($issue)
    {
        $sniffName = $issue["source"];
        $points = self::$sniffs[$sniffName];

        if ($points) {
            return $points;
        } else {
            return $issue["severity"] * self::DEFAULT_POINTS;
        }
    }

    public static function isValidIssue($issue) {
        $sniffName = $issue["source"];

        if (in_array($sniffName, self::INVALID_SNIFFS)) {
            return false;
        } else {
            return true;
        }
    }
}
