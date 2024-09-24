<?php

namespace rtSniffs\Sniffs\Spacing;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Sniff to ensure there is a blank line after function, loop, or if statement declarations.
 */
class MissingSpaceAfterDeclarationSniff implements Sniff
{
    /**
     * Registers the token types to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_FUNCTION, T_IF, T_FOREACH, T_WHILE, T_FOR];
    }

    /**
     * Processes the function, loop, or if statement and checks for missing space.
     *
     * @param File $phpcsFile The file being processed.
     * @param int  $stackPtr  The position of the token in the stack.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Find the opening curly brace or semicolon after the function or statement declaration.
        $openBracePtr = $phpcsFile->findNext([T_OPEN_CURLY_BRACKET, T_SEMICOLON], $stackPtr);
        if ($openBracePtr === false) {
            return; // No curly brace or semicolon found, ignore.
        }

        // Check if there is a blank line after the closing curly brace or semicolon.
        $nextNonWhitespace = $phpcsFile->findNext(T_WHITESPACE, ($openBracePtr + 1), null, true);

        if ($tokens[$nextNonWhitespace]['line'] === $tokens[$openBracePtr]['line'] + 1) {
            $error = '[rtSniffs] Expected one blank line after function, loop, or if statement';
            // Work in progress, not activated
            // $phpcsFile->addError( $error , $stackPtr, 'MissingSpaceAfterDeclaration' );

            // TODO: Write code to fix this warning
            // $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'MissingSpaceAfterDeclaration');

            // if ($fix === true) {
            //     // Insert a blank line after the curly brace or semicolon.
            //     $phpcsFile->fixer->addNewlineBefore($nextNonWhitespace);
            // }
        }
    }
}
