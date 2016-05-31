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
        "Drupal.Array.Array" => 50000,
        "Drupal.CSS.ClassDefinitionNameSpacing" => 50000,
        "Drupal.CSS.ColourDefinition" => 50000,
        "Drupal.Classes.ClassCreateInstance" => 50000,
        "Drupal.Classes.ClassDeclaration" => 50000,
        "Drupal.Classes.FullyQualifiedNamespace" => 50000,
        "Drupal.Classes.InterfaceName" => 50000,
        "Drupal.Classes.UnusedUseStatement" => 50000,
        "Drupal.Commenting.ClassComment" => 50000,
        "Drupal.Commenting.DocComment" => 50000,
        "Drupal.Commenting.DocCommentStar" => 50000,
        "Drupal.Commenting.FileComment" => 50000,
        "Drupal.Commenting.FunctionComment" => 50000,
        "Drupal.Commenting.HookComment" => 50000,
        "Drupal.Commenting.InlineComment" => 50000,
        "Drupal.Commenting.PostStatementComment" => 50000,
        "Drupal.ControlStructures.ControlSignature" => 50000,
        "Drupal.ControlStructures.ElseIf" => 50000,
        "Drupal.ControlStructures.InlineControlStructure" => 50000,
        "Drupal.Files.EndFileNewline" => 50000,
        "Drupal.Files.FileEncoding" => 50000,
        "Drupal.Files.LineLength" => 50000,
        "Drupal.Files.TxtFileLineLength" => 50000,
        "Drupal.Formatting.MultiLineAssignment" => 50000,
        "Drupal.Formatting.SpaceInlineIf" => 50000,
        "Drupal.Formatting.SpaceUnaryOperator" => 50000,
        "Drupal.Functions.DiscouragedFunctions" => 50000,
        "Drupal.Functions.FunctionDeclaration" => 50000,
        "Drupal.InfoFiles.AutoAddedKeys" => 50000,
        "Drupal.InfoFiles.ClassFiles" => 50000,
        "Drupal.InfoFiles.DuplicateEntry" => 50000,
        "Drupal.InfoFiles.Required" => 50000,
        "Drupal.NamingConventions.ValidClassName" => 50000,
        "Drupal.NamingConventions.ValidFunctionName" => 50000,
        "Drupal.NamingConventions.ValidGlobal" => 50000,
        "Drupal.NamingConventions.ValidVariableName" => 50000,
        "Drupal.Semantics.ConstantName" => 50000,
        "Drupal.Semantics.EmptyInstall" => 50000,
        "Drupal.Semantics.FunctionAlias" => 50000,
        "Drupal.Semantics.FunctionT" => 50000,
        "Drupal.Semantics.FunctionWatchdog" => 50000,
        "Drupal.Semantics.InstallHooks" => 50000,
        "Drupal.Semantics.LStringTranslatable" => 50000,
        "Drupal.Semantics.PregSecurity" => 50000,
        "Drupal.Semantics.RemoteAddress" => 50000,
        "Drupal.Semantics.TInHookMenu" => 50000,
        "Drupal.Semantics.TInHookSchema" => 50000,
        "Drupal.Strings.UnnecessaryStringConcat" => 50000,
        "Drupal.WhiteSpace.CloseBracketSpacing" => 50000,
        "Drupal.WhiteSpace.Comma" => 50000,
        "Drupal.WhiteSpace.EmptyLines" => 50000,
        "Drupal.WhiteSpace.Namespace" => 50000,
        "Drupal.WhiteSpace.ObjectOperatorIndent" => 50000,
        "Drupal.WhiteSpace.ObjectOperatorSpacing" => 50000,
        "Drupal.WhiteSpace.OpenBracketSpacing" => 50000,
        "Drupal.WhiteSpace.OperatorSpacing" => 50000,
        "Drupal.WhiteSpace.ScopeClosingBrace" => 50000,
        "Drupal.WhiteSpace.ScopeIndent" => 50000,
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
