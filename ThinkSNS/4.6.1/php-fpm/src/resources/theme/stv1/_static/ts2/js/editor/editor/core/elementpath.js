/**
 * modified from ckeditor ,elementpath represents element's tree path from body
 * @author: <yiminghe@gmail.com>
 */
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
KISSY.Editor.add("elementpath", function(KE) {
    var S = KISSY,
        DOM = S.DOM,
        dtd = KE.XHTML_DTD,
        KEN = KE.NODE,
        //UA = S.UA,
        TRUE = true,
        FALSE = false,
        NULL = null;
    // Elements that may be considered the "Block boundary" in an element path.
    var pathBlockElements = {
        "address":1,
        "blockquote":1,
        "dl":1,
        "h1":1,
        "h2":1,
        "h3":1,
        "h4":1,
        "h5":1,
        "h6":1,
        "p":1,
        "pre":1,
        "li":1,
        "dt":1,
        "dd":1
    };

    // Elements that may be considered the "Block limit" in an element path.
    var pathBlockLimitElements = {
        "body":1,
        "div":1,
        "table":1,
        "tbody":1,
        "tr":1,
        "td":1,
        "th":1,
        "caption":1,
        "form":1
    };

    // Check if an element contains any block element.
    var checkHasBlock = function(element) {
        element = element[0] || element;
        var childNodes = element.childNodes;

        for (var i = 0, count = childNodes.length; i < count; i++) {
            var child = childNodes[i];

            if (child.nodeType == KEN.NODE_ELEMENT
                && dtd.$block[ child.nodeName.toLowerCase() ])
                return TRUE;
        }

        return FALSE;
    };

    /**
     * @constructor
     * @param lastNode {KISSY.Node}
     */
    function ElementPath(lastNode) {
        var block = NULL;
        var blockLimit = NULL;
        var elements = [];
        var e = lastNode;

        while (e && e[0]) {
            if (e[0].nodeType == KEN.NODE_ELEMENT) {
                if (!this.lastElement)
                    this.lastElement = e;

                var elementName = e._4e_name();

                if (!blockLimit) {
                    if (!block && pathBlockElements[ elementName ])
                        block = e;

                    if (pathBlockLimitElements[ elementName ]) {
                        // DIV is considered the Block, if no block is available (#525)
                        // and if it doesn't contain other blocks.
                        if (!block && elementName == 'div' && !checkHasBlock(e))
                            block = e;
                        else
                            blockLimit = e;
                    }
                }

                elements.push(e);
                if (elementName == 'body')
                    break;
            }
            e = e.parent();
        }

        this["block"] = this.block = block;
        this["blockLimit"] = this.blockLimit = blockLimit;
        this["elements"] = this.elements = elements;
    }

    ElementPath.prototype = {
        /**
         * Compares this element path with another one.
         * @param otherPath {ElementPath} The elementPath object to be
         * compared with this one.
         * @return {boolean} "TRUE" if the paths are equal, containing the same
         * number of elements and the same elements in the same order.
         */
        compare : function(otherPath) {
            var thisElements = this.elements;
            var otherElements = otherPath && otherPath.elements;

            if (!otherElements || thisElements.length != otherElements.length)
                return FALSE;

            for (var i = 0; i < thisElements.length; i++) {
                if (!DOM._4e_equals(thisElements[ i ], otherElements[ i ]))
                    return FALSE;
            }

            return TRUE;
        },

        contains : function(tagNames) {
            var elements = this.elements;
            for (var i = 0; i < elements.length; i++) {
                if (elements[ i ]._4e_name() in tagNames)
                    return elements[ i ];
            }
            return NULL;
        }
    };

    KE["ElementPath"] = KE.ElementPath = ElementPath;
    var ElementPathP = ElementPath.prototype;
    KE.Utils.extern(ElementPathP, {
        "compare":ElementPathP.compare,
        "contains":ElementPathP.contains
    });

});
