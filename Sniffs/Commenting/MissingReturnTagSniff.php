<?php
/**
 * Sniff to check for missing @return tags in function docblocks.
 *
 * This sniff ensures that all functions have a corresponding @return tag
 * in their docblock comments.
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
 * Class to check whether the docblock contains the @return tag or not.
 */
class MissingReturnTagSniff implements Sniff
{
    /**
     * Registers the token types to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_FUNCTION];
    }

    /**
     * Processes the function and checks for the @return tag.
     *
     * @param File $phpcsFile The file being processed.
     * @param int  $stackPtr  The position of the token in the stack.
     * 
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $functionName = $phpcsFile->getDeclarationName($stackPtr);

        // Find the function's end and body to analyze for return types.
        $functionEnd = $phpcsFile->findEndOfStatement($stackPtr);
        $returnType = $this->_getReturnType($phpcsFile, $stackPtr, $functionEnd);

        // Check if a docblock is present.
        $docCommentPtr = $phpcsFile->findPrevious(T_DOC_COMMENT_OPEN_TAG, $stackPtr);

        if ($docCommentPtr !== false) {
            $hasReturnTag = false;

            // Check if @return tag exists in the docblock.
            for ($i = $docCommentPtr; $i < $stackPtr; $i++) {
                if ($tokens[$i]['content'] === '@return') {
                    $hasReturnTag = true;
                    break;
                }
            }

            // If @return is missing, autofix it.
            if (!$hasReturnTag) {
                $error = 'Function "%s" is missing a @return tag in the docblock.';
                $data  = [$functionName];
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'MissingReturnTag', $data);

                if ($fix === true) {
                    // Add @return tag with the detected type before the closing comment tag.
                    $closeCommentPtr = $phpcsFile->findNext(T_DOC_COMMENT_CLOSE_TAG, $docCommentPtr);
                    $phpcsFile->fixer->addContentBefore($closeCommentPtr, "*\n * @return $returnType\n ");
                }
            }
        }
    }

    /**
     * Determine the return type of the function.
     *
     * @param File $phpcsFile   The file being processed.
     * @param int  $stackPtr    The function token position.
     * @param int  $functionEnd The function's end position.
     *
     * @return string The detected return type.
     */
    private function _getReturnType(File $phpcsFile, int $stackPtr, int $functionEnd)
    {
        $tokens = $phpcsFile->getTokens();
        $returnType = 'void'; // Default to 'void'

        for ($i = $stackPtr; $i < $functionEnd; $i++) {
            // Check for return statements
            if ($tokens[$i]['code'] === T_RETURN) {
                $nextToken = $phpcsFile->findNext(T_WHITESPACE, $i + 1, null, true);
                
                // Check what is being returned
                if ($tokens[$nextToken]['code'] === T_LNUMBER) {
                    $returnType = 'int';
                } elseif ($tokens[$nextToken]['code'] === T_CONSTANT_ENCAPSED_STRING) {
                    $returnType = 'string';
                } elseif ($tokens[$nextToken]['code'] === T_DNUMBER) {
                    $returnType = 'float';
                } elseif ($tokens[$nextToken]['code'] === T_TRUE || $tokens[$nextToken]['code'] === T_FALSE) {
                    $returnType = 'bool';
                } elseif ($tokens[$nextToken]['code'] === T_ARRAY || $tokens[$nextToken]['code'] === T_OPEN_SHORT_ARRAY) {
                    $returnType = 'array';
                } elseif ($tokens[$nextToken]['code'] === T_NULL) {
                    $returnType = 'null';
                } else {
                    $returnType = 'mixed'; // If multiple types or undetermined
                }
                break; // We can stop once a return type is found
            }
        }

        return $returnType;
    }
}
