<?php
/**
 * Sniff for checking if there is a space before start of single line comment.
 *
 * @author Pushpender Singh
 * @link   https://github.com/PushpenderIndia
 * @license https://github.com/PushpenderIndia/rtSniffs/blob/main/LICENSE
 * @package RtSniffs
 */

namespace rtSniffs\Sniffs\Commenting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Sniff to ensure there is a space before a comment.
 */
class MissingSpaceBeforeCommentSniff implements Sniff
{
    /**
     * Registers the token types to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_COMMENT, T_DOC_COMMENT_OPEN_TAG];
    }

    /**
     * Processes the comment token and checks for a missing space before it.
     *
     * @param File $phpcsFile The file being processed.
     * @param int  $stackPtr  The position of the token in the stack.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check if there is no whitespace before the comment.
        if ($stackPtr > 0 && $tokens[$stackPtr - 1]['code'] !== T_WHITESPACE) {
            $error = '[rtSniffs] Expected space before comment';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'MissingSpaceBeforeComment');

            if ($fix === true) {
                // Insert a space before the comment.
                $phpcsFile->fixer->addContentBefore($stackPtr, ' ');
            }
        }
    }
}
