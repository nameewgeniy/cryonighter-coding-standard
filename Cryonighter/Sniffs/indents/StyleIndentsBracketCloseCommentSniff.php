<?php

/**
 * This sniff prohibits the use of Perl style hash comments.
 *
 * An example of a hash comment is:
 *
 * <code>
 * if ($a == true) {
 *    return true;
 * } // comment
 * </code>
 */

namespace Cryonighter\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class StyleIndentsBracketCloseCommentSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = [
        'PHP',
    ];

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_CLOSE_CURLY_BRACKET,
        ];
    }

    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $errorStatus = false;
        $msg = 'Found COMMENT in closed bracket line;';
        // token cursor
        $cursor = $stackPtr;
        // fix line
        $fixLine = $tokens[$cursor]['line'];
        $commentTags = [
            'T_COMMENT',
            'T_DOC_COMMENT_OPEN_TAG',
            'T_DOC_COMMENT_STRING',
            'T_DOC_COMMENT_CLOSE_TAG',
        ];

        while (($tokens[$stackPtr]['line'] + 1) >= $tokens[$cursor]['line']) {
            $cursor++;

            if (!isset($tokens[$cursor]['type'])) {
                break;
            }

            if ($tokens[$cursor]['line'] > $fixLine) {
                break;
            }

            if (in_array($tokens[$cursor]['type'], $commentTags)) {
                $errorStatus = true;
            }
        }
        
        if ($errorStatus) {
            $phpcsFile->addError($msg, $stackPtr, 'Found');
        }
    }
}