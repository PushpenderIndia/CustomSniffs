<?php
/**
 * Sniff for checking more than one consective blank line.
 *
 * @author Pushpender Singh
 * @link   https://github.com/PushpenderIndia
 * @license https://github.com/PushpenderIndia/rtSniffs/blob/main/LICENSE
 * @package RtSniffs
 */

namespace rtSniffs\Sniffs\Spacing;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Sniff to detect more than one consecutive blank line.
 */
class ExcessiveBlankLineSniff implements Sniff
{
    /**
     * Registers the token types to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_WHITESPACE, T_SEMICOLON, T_OPEN_CURLY_BRACKET, T_CLOSE_CURLY_BRACKET, T_COMMENT];
    }

    /**
     * Processes the token and checks for excessive blank lines.
     *
     * @param File $phpcsFile The file being processed.
     * @param int  $stackPtr  The position of the token in the stack.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Get the current token's line.
        $currentLine = $tokens[$stackPtr]['line'];

        // Find the next non-whitespace token.
        $nextNonWhitespace = $phpcsFile->findNext([T_WHITESPACE], $stackPtr + 1, null, true);

        if ($nextNonWhitespace !== false) {
            $nextLine = $tokens[$nextNonWhitespace]['line'];

            // Calculate the number of blank lines between the current and next token.
            $lineDifference = $nextLine - $currentLine;

            // If there are more than two blank lines (i.e., more than one blank line between code blocks).
            if ($lineDifference > 2) {
                $error = '[rtSniffs] More than one consecutive blank line found';
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'ExcessiveBlankLines');

                if ($fix === true) {
                    // Begin changeset to remove excess blank lines, but leave one.
                    $phpcsFile->fixer->beginChangeset();
                    
                    // Loop through the lines between the current token and the next token.
                    for ($i = $stackPtr + 1; $i < $nextNonWhitespace - 1; $i++) {
                        if ($tokens[$i]['line'] > $currentLine + 1) {
                            // Remove all tokens that span over two blank lines.
                            $phpcsFile->fixer->replaceToken($i, '');
                        }
                    }
                    
                    $phpcsFile->fixer->endChangeset();
                }
            }
        }
    }
}
