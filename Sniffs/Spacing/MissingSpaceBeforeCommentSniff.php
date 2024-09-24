<?php
/**
 * Sniff for checking if there is a space before the start of a single-line comment.
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
 * Sniff to ensure there is a space before a single-line comment.
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
        return [T_COMMENT];
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

        // Check if it's a single-line comment (// or #)
        if (strpos($tokens[$stackPtr]['content'], '//') === 0 || strpos($tokens[$stackPtr]['content'], '#') === 0) {
            $previousToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);

            // Ensure there is at least one space between the previous token and the comment.
            if ($tokens[$stackPtr]['line'] === $tokens[$previousToken]['line'] && 
                $tokens[$stackPtr]['column'] <= $tokens[$previousToken]['column'] + 1) {
                $phpcsFile->addError(
                    '[rtSniffs] Expected at least one space before the single-line comment',
                    $stackPtr,
                    'MissingSpaceBeforeComment'
                );
            }
        }
    }
}
