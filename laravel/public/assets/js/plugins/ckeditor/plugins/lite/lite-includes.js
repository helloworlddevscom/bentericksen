(function(global) {
	"use strict";
	global.ice = {};
}(this || window));/**
 * Rangy, a cross-browser JavaScript range and selection library
 * https://github.com/timdown/rangy
 *
 * Copyright 2015, Tim Down
 * Licensed under the MIT license.
 * Version: 1.3.0
 * Build date: 10 May 2015
 */

(function(factory, root) {
    if (typeof define == "function" && define.amd) {
        // AMD. Register as an anonymous module.
        define(factory);
    } else if (typeof module != "undefined" && typeof exports == "object") {
        // Node/CommonJS style
        module.exports = factory();
    } else {
        // No AMD or CommonJS support so we place Rangy in (probably) the global variable
        root.rangy = factory();
    }
})(function() {

    var OBJECT = "object", FUNCTION = "function", UNDEFINED = "undefined";

    // Minimal set of properties required for DOM Level 2 Range compliance. Comparison constants such as START_TO_START
    // are omitted because ranges in KHTML do not have them but otherwise work perfectly well. See issue 113.
    var domRangeProperties = ["startContainer", "startOffset", "endContainer", "endOffset", "collapsed",
        "commonAncestorContainer"];

    // Minimal set of methods required for DOM Level 2 Range compliance
    var domRangeMethods = ["setStart", "setStartBefore", "setStartAfter", "setEnd", "setEndBefore",
        "setEndAfter", "collapse", "selectNode", "selectNodeContents", "compareBoundaryPoints", "deleteContents",
        "extractContents", "cloneContents", "insertNode", "surroundContents", "cloneRange", "toString", "detach"];

    var textRangeProperties = ["boundingHeight", "boundingLeft", "boundingTop", "boundingWidth", "htmlText", "text"];

    // Subset of TextRange's full set of methods that we're interested in
    var textRangeMethods = ["collapse", "compareEndPoints", "duplicate", "moveToElementText", "parentElement", "select",
        "setEndPoint", "getBoundingClientRect"];

    /*----------------------------------------------------------------------------------------------------------------*/

    // Trio of functions taken from Peter Michaux's article:
    // http://peter.michaux.ca/articles/feature-detection-state-of-the-art-browser-scripting
    function isHostMethod(o, p) {
        var t = typeof o[p];
        return t == FUNCTION || (!!(t == OBJECT && o[p])) || t == "unknown";
    }

    function isHostObject(o, p) {
        return !!(typeof o[p] == OBJECT && o[p]);
    }

    function isHostProperty(o, p) {
        return typeof o[p] != UNDEFINED;
    }

    // Creates a convenience function to save verbose repeated calls to tests functions
    function createMultiplePropertyTest(testFunc) {
        return function(o, props) {
            var i = props.length;
            while (i--) {
                if (!testFunc(o, props[i])) {
                    return false;
                }
            }
            return true;
        };
    }

    // Next trio of functions are a convenience to save verbose repeated calls to previous two functions
    var areHostMethods = createMultiplePropertyTest(isHostMethod);
    var areHostObjects = createMultiplePropertyTest(isHostObject);
    var areHostProperties = createMultiplePropertyTest(isHostProperty);

    function isTextRange(range) {
        return range && areHostMethods(range, textRangeMethods) && areHostProperties(range, textRangeProperties);
    }

    function getBody(doc) {
        return isHostObject(doc, "body") ? doc.body : doc.getElementsByTagName("body")[0];
    }

    var forEach = [].forEach ?
        function(arr, func) {
            arr.forEach(func);
        } :
        function(arr, func) {
            for (var i = 0, len = arr.length; i < len; ++i) {
                func(arr[i], i);
            }
        };

    var modules = {};

    var isBrowser = (typeof window != UNDEFINED && typeof document != UNDEFINED);

    var util = {
        isHostMethod: isHostMethod,
        isHostObject: isHostObject,
        isHostProperty: isHostProperty,
        areHostMethods: areHostMethods,
        areHostObjects: areHostObjects,
        areHostProperties: areHostProperties,
        isTextRange: isTextRange,
        getBody: getBody,
        forEach: forEach
    };

    var api = {
        version: "1.3.0",
        initialized: false,
        isBrowser: isBrowser,
        supported: true,
        util: util,
        features: {},
        modules: modules,
        config: {
            alertOnFail: false,
            alertOnWarn: false,
            preferTextRange: false,
            autoInitialize: (typeof rangyAutoInitialize == UNDEFINED) ? true : rangyAutoInitialize
        }
    };

    function consoleLog(msg) {
        if (typeof console != UNDEFINED && isHostMethod(console, "log")) {
            console.log(msg);
        }
    }

    function alertOrLog(msg, shouldAlert) {
        if (isBrowser && shouldAlert) {
            alert(msg);
        } else  {
            consoleLog(msg);
        }
    }

    function fail(reason) {
        api.initialized = true;
        api.supported = false;
        alertOrLog("Rangy is not supported in this environment. Reason: " + reason, api.config.alertOnFail);
    }

    api.fail = fail;

    function warn(msg) {
        alertOrLog("Rangy warning: " + msg, api.config.alertOnWarn);
    }

    api.warn = warn;

    // Add utility extend() method
    var extend;
    if ({}.hasOwnProperty) {
        util.extend = extend = function(obj, props, deep) {
            var o, p;
            for (var i in props) {
                if (props.hasOwnProperty(i)) {
                    o = obj[i];
                    p = props[i];
                    if (deep && o !== null && typeof o == "object" && p !== null && typeof p == "object") {
                        extend(o, p, true);
                    }
                    obj[i] = p;
                }
            }
            // Special case for toString, which does not show up in for...in loops in IE <= 8
            if (props.hasOwnProperty("toString")) {
                obj.toString = props.toString;
            }
            return obj;
        };

        util.createOptions = function(optionsParam, defaults) {
            var options = {};
            extend(options, defaults);
            if (optionsParam) {
                extend(options, optionsParam);
            }
            return options;
        };
    } else {
        fail("hasOwnProperty not supported");
    }

    // Test whether we're in a browser and bail out if not
    if (!isBrowser) {
        fail("Rangy can only run in a browser");
    }

    // Test whether Array.prototype.slice can be relied on for NodeLists and use an alternative toArray() if not
    (function() {
        var toArray;

        if (isBrowser) {
            var el = document.createElement("div");
            el.appendChild(document.createElement("span"));
            var slice = [].slice;
            try {
                if (slice.call(el.childNodes, 0)[0].nodeType == 1) {
                    toArray = function(arrayLike) {
                        return slice.call(arrayLike, 0);
                    };
                }
            } catch (e) {}
        }

        if (!toArray) {
            toArray = function(arrayLike) {
                var arr = [];
                for (var i = 0, len = arrayLike.length; i < len; ++i) {
                    arr[i] = arrayLike[i];
                }
                return arr;
            };
        }

        util.toArray = toArray;
    })();

    // Very simple event handler wrapper function that doesn't attempt to solve issues such as "this" handling or
    // normalization of event properties
    var addListener;
    if (isBrowser) {
        if (isHostMethod(document, "addEventListener")) {
            addListener = function(obj, eventType, listener) {
                obj.addEventListener(eventType, listener, false);
            };
        } else if (isHostMethod(document, "attachEvent")) {
            addListener = function(obj, eventType, listener) {
                obj.attachEvent("on" + eventType, listener);
            };
        } else {
            fail("Document does not have required addEventListener or attachEvent method");
        }

        util.addListener = addListener;
    }

    var initListeners = [];

    function getErrorDesc(ex) {
        return ex.message || ex.description || String(ex);
    }

    // Initialization
    function init() {
        if (!isBrowser || api.initialized) {
            return;
        }
        var testRange;
        var implementsDomRange = false, implementsTextRange = false;

        // First, perform basic feature tests

        if (isHostMethod(document, "createRange")) {
            testRange = document.createRange();
            if (areHostMethods(testRange, domRangeMethods) && areHostProperties(testRange, domRangeProperties)) {
                implementsDomRange = true;
            }
        }

        var body = getBody(document);
        if (!body || body.nodeName.toLowerCase() != "body") {
            fail("No body element found");
            return;
        }

        if (body && isHostMethod(body, "createTextRange")) {
            testRange = body.createTextRange();
            if (isTextRange(testRange)) {
                implementsTextRange = true;
            }
        }

        if (!implementsDomRange && !implementsTextRange) {
            fail("Neither Range nor TextRange are available");
            return;
        }

        api.initialized = true;
        api.features = {
            implementsDomRange: implementsDomRange,
            implementsTextRange: implementsTextRange
        };

        // Initialize modules
        var module, errorMessage;
        for (var moduleName in modules) {
            if ( (module = modules[moduleName]) instanceof Module ) {
                module.init(module, api);
            }
        }

        // Call init listeners
        for (var i = 0, len = initListeners.length; i < len; ++i) {
            try {
                initListeners[i](api);
            } catch (ex) {
                errorMessage = "Rangy init listener threw an exception. Continuing. Detail: " + getErrorDesc(ex);
                consoleLog(errorMessage);
            }
        }
    }

    function deprecationNotice(deprecated, replacement, module) {
        if (module) {
            deprecated += " in module " + module.name;
        }
        api.warn("DEPRECATED: " + deprecated + " is deprecated. Please use " +
        replacement + " instead.");
    }

    function createAliasForDeprecatedMethod(owner, deprecated, replacement, module) {
        owner[deprecated] = function() {
            deprecationNotice(deprecated, replacement, module);
            return owner[replacement].apply(owner, util.toArray(arguments));
        };
    }

    util.deprecationNotice = deprecationNotice;
    util.createAliasForDeprecatedMethod = createAliasForDeprecatedMethod;

    // Allow external scripts to initialize this library in case it's loaded after the document has loaded
    api.init = init;

    // Execute listener immediately if already initialized
    api.addInitListener = function(listener) {
        if (api.initialized) {
            listener(api);
        } else {
            initListeners.push(listener);
        }
    };

    var shimListeners = [];

    api.addShimListener = function(listener) {
        shimListeners.push(listener);
    };

    function shim(win) {
        win = win || window;
        init();

        // Notify listeners
        for (var i = 0, len = shimListeners.length; i < len; ++i) {
            shimListeners[i](win);
        }
    }

    if (isBrowser) {
        api.shim = api.createMissingNativeApi = shim;
        createAliasForDeprecatedMethod(api, "createMissingNativeApi", "shim");
    }

    function Module(name, dependencies, initializer) {
        this.name = name;
        this.dependencies = dependencies;
        this.initialized = false;
        this.supported = false;
        this.initializer = initializer;
    }

    Module.prototype = {
        init: function() {
            var requiredModuleNames = this.dependencies || [];
            for (var i = 0, len = requiredModuleNames.length, requiredModule, moduleName; i < len; ++i) {
                moduleName = requiredModuleNames[i];

                requiredModule = modules[moduleName];
                if (!requiredModule || !(requiredModule instanceof Module)) {
                    throw new Error("required module '" + moduleName + "' not found");
                }

                requiredModule.init();

                if (!requiredModule.supported) {
                    throw new Error("required module '" + moduleName + "' not supported");
                }
            }

            // Now run initializer
            this.initializer(this);
        },

        fail: function(reason) {
            this.initialized = true;
            this.supported = false;
            throw new Error(reason);
        },

        warn: function(msg) {
            api.warn("Module " + this.name + ": " + msg);
        },

        deprecationNotice: function(deprecated, replacement) {
            api.warn("DEPRECATED: " + deprecated + " in module " + this.name + " is deprecated. Please use " +
                replacement + " instead");
        },

        createError: function(msg) {
            return new Error("Error in Rangy " + this.name + " module: " + msg);
        }
    };

    function createModule(name, dependencies, initFunc) {
        var newModule = new Module(name, dependencies, function(module) {
            if (!module.initialized) {
                module.initialized = true;
                try {
                    initFunc(api, module);
                    module.supported = true;
                } catch (ex) {
                    var errorMessage = "Module '" + name + "' failed to load: " + getErrorDesc(ex);
                    consoleLog(errorMessage);
                    if (ex.stack) {
                        consoleLog(ex.stack);
                    }
                }
            }
        });
        modules[name] = newModule;
        return newModule;
    }

    api.createModule = function(name) {
        // Allow 2 or 3 arguments (second argument is an optional array of dependencies)
        var initFunc, dependencies;
        if (arguments.length == 2) {
            initFunc = arguments[1];
            dependencies = [];
        } else {
            initFunc = arguments[2];
            dependencies = arguments[1];
        }

        var module = createModule(name, dependencies, initFunc);

        // Initialize the module immediately if the core is already initialized
        if (api.initialized && api.supported) {
            module.init();
        }
    };

    api.createCoreModule = function(name, dependencies, initFunc) {
        createModule(name, dependencies, initFunc);
    };

    /*----------------------------------------------------------------------------------------------------------------*/

    // Ensure rangy.rangePrototype and rangy.selectionPrototype are available immediately

    function RangePrototype() {}
    api.RangePrototype = RangePrototype;
    api.rangePrototype = new RangePrototype();

    function SelectionPrototype() {}
    api.selectionPrototype = new SelectionPrototype();

    /*----------------------------------------------------------------------------------------------------------------*/

    // DOM utility methods used by Rangy
    api.createCoreModule("DomUtil", [], function(api, module) {
        var UNDEF = "undefined";
        var util = api.util;
        var getBody = util.getBody;

        // Perform feature tests
        if (!util.areHostMethods(document, ["createDocumentFragment", "createElement", "createTextNode"])) {
            module.fail("document missing a Node creation method");
        }

        if (!util.isHostMethod(document, "getElementsByTagName")) {
            module.fail("document missing getElementsByTagName method");
        }

        var el = document.createElement("div");
        if (!util.areHostMethods(el, ["insertBefore", "appendChild", "cloneNode"] ||
                !util.areHostObjects(el, ["previousSibling", "nextSibling", "childNodes", "parentNode"]))) {
            module.fail("Incomplete Element implementation");
        }

        // innerHTML is required for Range's createContextualFragment method
        if (!util.isHostProperty(el, "innerHTML")) {
            module.fail("Element is missing innerHTML property");
        }

        var textNode = document.createTextNode("test");
        if (!util.areHostMethods(textNode, ["splitText", "deleteData", "insertData", "appendData", "cloneNode"] ||
                !util.areHostObjects(el, ["previousSibling", "nextSibling", "childNodes", "parentNode"]) ||
                !util.areHostProperties(textNode, ["data"]))) {
            module.fail("Incomplete Text Node implementation");
        }

        /*----------------------------------------------------------------------------------------------------------------*/

        // Removed use of indexOf because of a bizarre bug in Opera that is thrown in one of the Acid3 tests. I haven't been
        // able to replicate it outside of the test. The bug is that indexOf returns -1 when called on an Array that
        // contains just the document as a single element and the value searched for is the document.
        var arrayContains = /*Array.prototype.indexOf ?
            function(arr, val) {
                return arr.indexOf(val) > -1;
            }:*/

            function(arr, val) {
                var i = arr.length;
                while (i--) {
                    if (arr[i] === val) {
                        return true;
                    }
                }
                return false;
            };

        // Opera 11 puts HTML elements in the null namespace, it seems, and IE 7 has undefined namespaceURI
        function isHtmlNamespace(node) {
            var ns;
            return typeof node.namespaceURI == UNDEF || ((ns = node.namespaceURI) === null || ns == "http://www.w3.org/1999/xhtml");
        }

        function parentElement(node) {
            var parent = node.parentNode;
            return (parent.nodeType == 1) ? parent : null;
        }

        function getNodeIndex(node) {
            var i = 0;
            while( (node = node.previousSibling) ) {
                ++i;
            }
            return i;
        }

        function getNodeLength(node) {
            switch (node.nodeType) {
                case 7:
                case 10:
                    return 0;
                case 3:
                case 8:
                    return node.length;
                default:
                    return node.childNodes.length;
            }
        }

        function getCommonAncestor(node1, node2) {
            var ancestors = [], n;
            for (n = node1; n; n = n.parentNode) {
                ancestors.push(n);
            }

            for (n = node2; n; n = n.parentNode) {
                if (arrayContains(ancestors, n)) {
                    return n;
                }
            }

            return null;
        }

        function isAncestorOf(ancestor, descendant, selfIsAncestor) {
            var n = selfIsAncestor ? descendant : descendant.parentNode;
            while (n) {
                if (n === ancestor) {
                    return true;
                } else {
                    n = n.parentNode;
                }
            }
            return false;
        }

        function isOrIsAncestorOf(ancestor, descendant) {
            return isAncestorOf(ancestor, descendant, true);
        }

        function getClosestAncestorIn(node, ancestor, selfIsAncestor) {
            var p, n = selfIsAncestor ? node : node.parentNode;
            while (n) {
                p = n.parentNode;
                if (p === ancestor) {
                    return n;
                }
                n = p;
            }
            return null;
        }

        function isCharacterDataNode(node) {
            var t = node.nodeType;
            return t == 3 || t == 4 || t == 8 ; // Text, CDataSection or Comment
        }

        function isTextOrCommentNode(node) {
            if (!node) {
                return false;
            }
            var t = node.nodeType;
            return t == 3 || t == 8 ; // Text or Comment
        }

        function insertAfter(node, precedingNode) {
            var nextNode = precedingNode.nextSibling, parent = precedingNode.parentNode;
            if (nextNode) {
                parent.insertBefore(node, nextNode);
            } else {
                parent.appendChild(node);
            }
            return node;
        }

        // Note that we cannot use splitText() because it is bugridden in IE 9.
        function splitDataNode(node, index, positionsToPreserve) {
            var newNode = node.cloneNode(false);
            newNode.deleteData(0, index);
            node.deleteData(index, node.length - index);
            insertAfter(newNode, node);

            // Preserve positions
            if (positionsToPreserve) {
                for (var i = 0, position; position = positionsToPreserve[i++]; ) {
                    // Handle case where position was inside the portion of node after the split point
                    if (position.node == node && position.offset > index) {
                        position.node = newNode;
                        position.offset -= index;
                    }
                    // Handle the case where the position is a node offset within node's parent
                    else if (position.node == node.parentNode && position.offset > getNodeIndex(node)) {
                        ++position.offset;
                    }
                }
            }
            return newNode;
        }

        function getDocument(node) {
            if (node.nodeType == 9) {
                return node;
            } else if (typeof node.ownerDocument != UNDEF) {
                return node.ownerDocument;
            } else if (typeof node.document != UNDEF) {
                return node.document;
            } else if (node.parentNode) {
                return getDocument(node.parentNode);
            } else {
                throw module.createError("getDocument: no document found for node");
            }
        }

        function getWindow(node) {
            var doc = getDocument(node);
            if (typeof doc.defaultView != UNDEF) {
                return doc.defaultView;
            } else if (typeof doc.parentWindow != UNDEF) {
                return doc.parentWindow;
            } else {
                throw module.createError("Cannot get a window object for node");
            }
        }

        function getIframeDocument(iframeEl) {
            if (typeof iframeEl.contentDocument != UNDEF) {
                return iframeEl.contentDocument;
            } else if (typeof iframeEl.contentWindow != UNDEF) {
                return iframeEl.contentWindow.document;
            } else {
                throw module.createError("getIframeDocument: No Document object found for iframe element");
            }
        }

        function getIframeWindow(iframeEl) {
            if (typeof iframeEl.contentWindow != UNDEF) {
                return iframeEl.contentWindow;
            } else if (typeof iframeEl.contentDocument != UNDEF) {
                return iframeEl.contentDocument.defaultView;
            } else {
                throw module.createError("getIframeWindow: No Window object found for iframe element");
            }
        }

        // This looks bad. Is it worth it?
        function isWindow(obj) {
            return obj && util.isHostMethod(obj, "setTimeout") && util.isHostObject(obj, "document");
        }

        function getContentDocument(obj, module, methodName) {
            var doc;

            if (!obj) {
                doc = document;
            }

            // Test if a DOM node has been passed and obtain a document object for it if so
            else if (util.isHostProperty(obj, "nodeType")) {
                doc = (obj.nodeType == 1 && obj.tagName.toLowerCase() == "iframe") ?
                    getIframeDocument(obj) : getDocument(obj);
            }

            // Test if the doc parameter appears to be a Window object
            else if (isWindow(obj)) {
                doc = obj.document;
            }

            if (!doc) {
                throw module.createError(methodName + "(): Parameter must be a Window object or DOM node");
            }

            return doc;
        }

        function getRootContainer(node) {
            var parent;
            while ( (parent = node.parentNode) ) {
                node = parent;
            }
            return node;
        }

        function comparePoints(nodeA, offsetA, nodeB, offsetB) {
            // See http://www.w3.org/TR/DOM-Level-2-Traversal-Range/ranges.html#Level-2-Range-Comparing
            var nodeC, root, childA, childB, n;
            if (nodeA == nodeB) {
                // Case 1: nodes are the same
                return offsetA === offsetB ? 0 : (offsetA < offsetB) ? -1 : 1;
            } else if ( (nodeC = getClosestAncestorIn(nodeB, nodeA, true)) ) {
                // Case 2: node C (container B or an ancestor) is a child node of A
                return offsetA <= getNodeIndex(nodeC) ? -1 : 1;
            } else if ( (nodeC = getClosestAncestorIn(nodeA, nodeB, true)) ) {
                // Case 3: node C (container A or an ancestor) is a child node of B
                return getNodeIndex(nodeC) < offsetB  ? -1 : 1;
            } else {
                root = getCommonAncestor(nodeA, nodeB);
                if (!root) {
                    throw new Error("comparePoints error: nodes have no common ancestor");
                }

                // Case 4: containers are siblings or descendants of siblings
                childA = (nodeA === root) ? root : getClosestAncestorIn(nodeA, root, true);
                childB = (nodeB === root) ? root : getClosestAncestorIn(nodeB, root, true);

                if (childA === childB) {
                    // This shouldn't be possible
                    throw module.createError("comparePoints got to case 4 and childA and childB are the same!");
                } else {
                    n = root.firstChild;
                    while (n) {
                        if (n === childA) {
                            return -1;
                        } else if (n === childB) {
                            return 1;
                        }
                        n = n.nextSibling;
                    }
                }
            }
        }

        /*----------------------------------------------------------------------------------------------------------------*/

        // Test for IE's crash (IE 6/7) or exception (IE >= 8) when a reference to garbage-collected text node is queried
        var crashyTextNodes = false;

        function isBrokenNode(node) {
            var n;
            try {
                n = node.parentNode;
                return false;
            } catch (e) {
                return true;
            }
        }

        (function() {
            var el = document.createElement("b");
            el.innerHTML = "1";
            var textNode = el.firstChild;
            el.innerHTML = "<br />";
            crashyTextNodes = isBrokenNode(textNode);

            api.features.crashyTextNodes = crashyTextNodes;
        })();

        /*----------------------------------------------------------------------------------------------------------------*/

        function inspectNode(node) {
            if (!node) {
                return "[No node]";
            }
            if (crashyTextNodes && isBrokenNode(node)) {
                return "[Broken node]";
            }
            if (isCharacterDataNode(node)) {
                return '"' + node.data + '"';
            }
            if (node.nodeType == 1) {
                var idAttr = node.id ? ' id="' + node.id + '"' : "";
                return "<" + node.nodeName + idAttr + ">[index:" + getNodeIndex(node) + ",length:" + node.childNodes.length + "][" + (node.innerHTML || "[innerHTML not supported]").slice(0, 25) + "]";
            }
            return node.nodeName;
        }

        function fragmentFromNodeChildren(node) {
            var fragment = getDocument(node).createDocumentFragment(), child;
            while ( (child = node.firstChild) ) {
                fragment.appendChild(child);
            }
            return fragment;
        }

        var getComputedStyleProperty;
        if (typeof window.getComputedStyle != UNDEF) {
            getComputedStyleProperty = function(el, propName) {
                return getWindow(el).getComputedStyle(el, null)[propName];
            };
        } else if (typeof document.documentElement.currentStyle != UNDEF) {
            getComputedStyleProperty = function(el, propName) {
                return el.currentStyle ? el.currentStyle[propName] : "";
            };
        } else {
            module.fail("No means of obtaining computed style properties found");
        }

        function createTestElement(doc, html, contentEditable) {
            var body = getBody(doc);
            var el = doc.createElement("div");
            el.contentEditable = "" + !!contentEditable;
            if (html) {
                el.innerHTML = html;
            }

            // Insert the test element at the start of the body to prevent scrolling to the bottom in iOS (issue #292)
            var bodyFirstChild = body.firstChild;
            if (bodyFirstChild) {
                body.insertBefore(el, bodyFirstChild);
            } else {
                body.appendChild(el);
            }

            return el;
        }

        function removeNode(node) {
            return node.parentNode.removeChild(node);
        }

        function NodeIterator(root) {
            this.root = root;
            this._next = root;
        }

        NodeIterator.prototype = {
            _current: null,

            hasNext: function() {
                return !!this._next;
            },

            next: function() {
                var n = this._current = this._next;
                var child, next;
                if (this._current) {
                    child = n.firstChild;
                    if (child) {
                        this._next = child;
                    } else {
                        next = null;
                        while ((n !== this.root) && !(next = n.nextSibling)) {
                            n = n.parentNode;
                        }
                        this._next = next;
                    }
                }
                return this._current;
            },

            detach: function() {
                this._current = this._next = this.root = null;
            }
        };

        function createIterator(root) {
            return new NodeIterator(root);
        }

        function DomPosition(node, offset) {
            this.node = node;
            this.offset = offset;
        }

        DomPosition.prototype = {
            equals: function(pos) {
                return !!pos && this.node === pos.node && this.offset == pos.offset;
            },

            inspect: function() {
                return "[DomPosition(" + inspectNode(this.node) + ":" + this.offset + ")]";
            },

            toString: function() {
                return this.inspect();
            }
        };

        function DOMException(codeName) {
            this.code = this[codeName];
            this.codeName = codeName;
            this.message = "DOMException: " + this.codeName;
        }

        DOMException.prototype = {
            INDEX_SIZE_ERR: 1,
            HIERARCHY_REQUEST_ERR: 3,
            WRONG_DOCUMENT_ERR: 4,
            NO_MODIFICATION_ALLOWED_ERR: 7,
            NOT_FOUND_ERR: 8,
            NOT_SUPPORTED_ERR: 9,
            INVALID_STATE_ERR: 11,
            INVALID_NODE_TYPE_ERR: 24
        };

        DOMException.prototype.toString = function() {
            return this.message;
        };

        api.dom = {
            arrayContains: arrayContains,
            isHtmlNamespace: isHtmlNamespace,
            parentElement: parentElement,
            getNodeIndex: getNodeIndex,
            getNodeLength: getNodeLength,
            getCommonAncestor: getCommonAncestor,
            isAncestorOf: isAncestorOf,
            isOrIsAncestorOf: isOrIsAncestorOf,
            getClosestAncestorIn: getClosestAncestorIn,
            isCharacterDataNode: isCharacterDataNode,
            isTextOrCommentNode: isTextOrCommentNode,
            insertAfter: insertAfter,
            splitDataNode: splitDataNode,
            getDocument: getDocument,
            getWindow: getWindow,
            getIframeWindow: getIframeWindow,
            getIframeDocument: getIframeDocument,
            getBody: getBody,
            isWindow: isWindow,
            getContentDocument: getContentDocument,
            getRootContainer: getRootContainer,
            comparePoints: comparePoints,
            isBrokenNode: isBrokenNode,
            inspectNode: inspectNode,
            getComputedStyleProperty: getComputedStyleProperty,
            createTestElement: createTestElement,
            removeNode: removeNode,
            fragmentFromNodeChildren: fragmentFromNodeChildren,
            createIterator: createIterator,
            DomPosition: DomPosition
        };

        api.DOMException = DOMException;
    });

    /*----------------------------------------------------------------------------------------------------------------*/

    // Pure JavaScript implementation of DOM Range
    api.createCoreModule("DomRange", ["DomUtil"], function(api, module) {
        var dom = api.dom;
        var util = api.util;
        var DomPosition = dom.DomPosition;
        var DOMException = api.DOMException;

        var isCharacterDataNode = dom.isCharacterDataNode;
        var getNodeIndex = dom.getNodeIndex;
        var isOrIsAncestorOf = dom.isOrIsAncestorOf;
        var getDocument = dom.getDocument;
        var comparePoints = dom.comparePoints;
        var splitDataNode = dom.splitDataNode;
        var getClosestAncestorIn = dom.getClosestAncestorIn;
        var getNodeLength = dom.getNodeLength;
        var arrayContains = dom.arrayContains;
        var getRootContainer = dom.getRootContainer;
        var crashyTextNodes = api.features.crashyTextNodes;

        var removeNode = dom.removeNode;

        /*----------------------------------------------------------------------------------------------------------------*/

        // Utility functions

        function isNonTextPartiallySelected(node, range) {
            return (node.nodeType != 3) &&
                   (isOrIsAncestorOf(node, range.startContainer) || isOrIsAncestorOf(node, range.endContainer));
        }

        function getRangeDocument(range) {
            return range.document || getDocument(range.startContainer);
        }

        function getRangeRoot(range) {
            return getRootContainer(range.startContainer);
        }

        function getBoundaryBeforeNode(node) {
            return new DomPosition(node.parentNode, getNodeIndex(node));
        }

        function getBoundaryAfterNode(node) {
            return new DomPosition(node.parentNode, getNodeIndex(node) + 1);
        }

        function insertNodeAtPosition(node, n, o) {
            var firstNodeInserted = node.nodeType == 11 ? node.firstChild : node;
            if (isCharacterDataNode(n)) {
                if (o == n.length) {
                    dom.insertAfter(node, n);
                } else {
                    n.parentNode.insertBefore(node, o == 0 ? n : splitDataNode(n, o));
                }
            } else if (o >= n.childNodes.length) {
                n.appendChild(node);
            } else {
                n.insertBefore(node, n.childNodes[o]);
            }
            return firstNodeInserted;
        }

        function rangesIntersect(rangeA, rangeB, touchingIsIntersecting) {
            assertRangeValid(rangeA);
            assertRangeValid(rangeB);

            if (getRangeDocument(rangeB) != getRangeDocument(rangeA)) {
                throw new DOMException("WRONG_DOCUMENT_ERR");
            }

            var startComparison = comparePoints(rangeA.startContainer, rangeA.startOffset, rangeB.endContainer, rangeB.endOffset),
                endComparison = comparePoints(rangeA.endContainer, rangeA.endOffset, rangeB.startContainer, rangeB.startOffset);

            return touchingIsIntersecting ? startComparison <= 0 && endComparison >= 0 : startComparison < 0 && endComparison > 0;
        }

        function cloneSubtree(iterator) {
            var partiallySelected;
            for (var node, frag = getRangeDocument(iterator.range).createDocumentFragment(), subIterator; node = iterator.next(); ) {
                partiallySelected = iterator.isPartiallySelectedSubtree();
                node = node.cloneNode(!partiallySelected);
                if (partiallySelected) {
                    subIterator = iterator.getSubtreeIterator();
                    node.appendChild(cloneSubtree(subIterator));
                    subIterator.detach();
                }

                if (node.nodeType == 10) { // DocumentType
                    throw new DOMException("HIERARCHY_REQUEST_ERR");
                }
                frag.appendChild(node);
            }
            return frag;
        }

        function iterateSubtree(rangeIterator, func, iteratorState) {
            var it, n;
            iteratorState = iteratorState || { stop: false };
            for (var node, subRangeIterator; node = rangeIterator.next(); ) {
                if (rangeIterator.isPartiallySelectedSubtree()) {
                    if (func(node) === false) {
                        iteratorState.stop = true;
                        return;
                    } else {
                        // The node is partially selected by the Range, so we can use a new RangeIterator on the portion of
                        // the node selected by the Range.
                        subRangeIterator = rangeIterator.getSubtreeIterator();
                        iterateSubtree(subRangeIterator, func, iteratorState);
                        subRangeIterator.detach();
                        if (iteratorState.stop) {
                            return;
                        }
                    }
                } else {
                    // The whole node is selected, so we can use efficient DOM iteration to iterate over the node and its
                    // descendants
                    it = dom.createIterator(node);
                    while ( (n = it.next()) ) {
                        if (func(n) === false) {
                            iteratorState.stop = true;
                            return;
                        }
                    }
                }
            }
        }

        function deleteSubtree(iterator) {
            var subIterator;
            while (iterator.next()) {
                if (iterator.isPartiallySelectedSubtree()) {
                    subIterator = iterator.getSubtreeIterator();
                    deleteSubtree(subIterator);
                    subIterator.detach();
                } else {
                    iterator.remove();
                }
            }
        }

        function extractSubtree(iterator) {
            for (var node, frag = getRangeDocument(iterator.range).createDocumentFragment(), subIterator; node = iterator.next(); ) {

                if (iterator.isPartiallySelectedSubtree()) {
                    node = node.cloneNode(false);
                    subIterator = iterator.getSubtreeIterator();
                    node.appendChild(extractSubtree(subIterator));
                    subIterator.detach();
                } else {
                    iterator.remove();
                }
                if (node.nodeType == 10) { // DocumentType
                    throw new DOMException("HIERARCHY_REQUEST_ERR");
                }
                frag.appendChild(node);
            }
            return frag;
        }

        function getNodesInRange(range, nodeTypes, filter) {
            var filterNodeTypes = !!(nodeTypes && nodeTypes.length), regex;
            var filterExists = !!filter;
            if (filterNodeTypes) {
                regex = new RegExp("^(" + nodeTypes.join("|") + ")$");
            }

            var nodes = [];
            iterateSubtree(new RangeIterator(range, false), function(node) {
                if (filterNodeTypes && !regex.test(node.nodeType)) {
                    return;
                }
                if (filterExists && !filter(node)) {
                    return;
                }
                // Don't include a boundary container if it is a character data node and the range does not contain any
                // of its character data. See issue 190.
                var sc = range.startContainer;
                if (node == sc && isCharacterDataNode(sc) && range.startOffset == sc.length) {
                    return;
                }

                var ec = range.endContainer;
                if (node == ec && isCharacterDataNode(ec) && range.endOffset == 0) {
                    return;
                }

                nodes.push(node);
            });
            return nodes;
        }

        function inspect(range) {
            var name = (typeof range.getName == "undefined") ? "Range" : range.getName();
            return "[" + name + "(" + dom.inspectNode(range.startContainer) + ":" + range.startOffset + ", " +
                    dom.inspectNode(range.endContainer) + ":" + range.endOffset + ")]";
        }

        /*----------------------------------------------------------------------------------------------------------------*/

        // RangeIterator code partially borrows from IERange by Tim Ryan (http://github.com/timcameronryan/IERange)

        function RangeIterator(range, clonePartiallySelectedTextNodes) {
            this.range = range;
            this.clonePartiallySelectedTextNodes = clonePartiallySelectedTextNodes;


            if (!range.collapsed) {
                this.sc = range.startContainer;
                this.so = range.startOffset;
                this.ec = range.endContainer;
                this.eo = range.endOffset;
                var root = range.commonAncestorContainer;

                if (this.sc === this.ec && isCharacterDataNode(this.sc)) {
                    this.isSingleCharacterDataNode = true;
                    this._first = this._last = this._next = this.sc;
                } else {
                    this._first = this._next = (this.sc === root && !isCharacterDataNode(this.sc)) ?
                        this.sc.childNodes[this.so] : getClosestAncestorIn(this.sc, root, true);
                    this._last = (this.ec === root && !isCharacterDataNode(this.ec)) ?
                        this.ec.childNodes[this.eo - 1] : getClosestAncestorIn(this.ec, root, true);
                }
            }
        }

        RangeIterator.prototype = {
            _current: null,
            _next: null,
            _first: null,
            _last: null,
            isSingleCharacterDataNode: false,

            reset: function() {
                this._current = null;
                this._next = this._first;
            },

            hasNext: function() {
                return !!this._next;
            },

            next: function() {
                // Move to next node
                var current = this._current = this._next;
                if (current) {
                    this._next = (current !== this._last) ? current.nextSibling : null;

                    // Check for partially selected text nodes
                    if (isCharacterDataNode(current) && this.clonePartiallySelectedTextNodes) {
                        if (current === this.ec) {
                            (current = current.cloneNode(true)).deleteData(this.eo, current.length - this.eo);
                        }
                        if (this._current === this.sc) {
                            (current = current.cloneNode(true)).deleteData(0, this.so);
                        }
                    }
                }

                return current;
            },

            remove: function() {
                var current = this._current, start, end;

                if (isCharacterDataNode(current) && (current === this.sc || current === this.ec)) {
                    start = (current === this.sc) ? this.so : 0;
                    end = (current === this.ec) ? this.eo : current.length;
                    if (start != end) {
                        current.deleteData(start, end - start);
                    }
                } else {
                    if (current.parentNode) {
                        removeNode(current);
                    } else {
                    }
                }
            },

            // Checks if the current node is partially selected
            isPartiallySelectedSubtree: function() {
                var current = this._current;
                return isNonTextPartiallySelected(current, this.range);
            },

            getSubtreeIterator: function() {
                var subRange;
                if (this.isSingleCharacterDataNode) {
                    subRange = this.range.cloneRange();
                    subRange.collapse(false);
                } else {
                    subRange = new Range(getRangeDocument(this.range));
                    var current = this._current;
                    var startContainer = current, startOffset = 0, endContainer = current, endOffset = getNodeLength(current);

                    if (isOrIsAncestorOf(current, this.sc)) {
                        startContainer = this.sc;
                        startOffset = this.so;
                    }
                    if (isOrIsAncestorOf(current, this.ec)) {
                        endContainer = this.ec;
                        endOffset = this.eo;
                    }

                    updateBoundaries(subRange, startContainer, startOffset, endContainer, endOffset);
                }
                return new RangeIterator(subRange, this.clonePartiallySelectedTextNodes);
            },

            detach: function() {
                this.range = this._current = this._next = this._first = this._last = this.sc = this.so = this.ec = this.eo = null;
            }
        };

        /*----------------------------------------------------------------------------------------------------------------*/

        var beforeAfterNodeTypes = [1, 3, 4, 5, 7, 8, 10];
        var rootContainerNodeTypes = [2, 9, 11];
        var readonlyNodeTypes = [5, 6, 10, 12];
        var insertableNodeTypes = [1, 3, 4, 5, 7, 8, 10, 11];
        var surroundNodeTypes = [1, 3, 4, 5, 7, 8];

        function createAncestorFinder(nodeTypes) {
            return function(node, selfIsAncestor) {
                var t, n = selfIsAncestor ? node : node.parentNode;
                while (n) {
                    t = n.nodeType;
                    if (arrayContains(nodeTypes, t)) {
                        return n;
                    }
                    n = n.parentNode;
                }
                return null;
            };
        }

        var getDocumentOrFragmentContainer = createAncestorFinder( [9, 11] );
        var getReadonlyAncestor = createAncestorFinder(readonlyNodeTypes);
        var getDocTypeNotationEntityAncestor = createAncestorFinder( [6, 10, 12] );

        function assertNoDocTypeNotationEntityAncestor(node, allowSelf) {
            if (getDocTypeNotationEntityAncestor(node, allowSelf)) {
                throw new DOMException("INVALID_NODE_TYPE_ERR");
            }
        }

        function assertValidNodeType(node, invalidTypes) {
            if (!arrayContains(invalidTypes, node.nodeType)) {
                throw new DOMException("INVALID_NODE_TYPE_ERR");
            }
        }

        function assertValidOffset(node, offset) {
            if (offset < 0 || offset > (isCharacterDataNode(node) ? node.length : node.childNodes.length)) {
                throw new DOMException("INDEX_SIZE_ERR");
            }
        }

        function assertSameDocumentOrFragment(node1, node2) {
            if (getDocumentOrFragmentContainer(node1, true) !== getDocumentOrFragmentContainer(node2, true)) {
                throw new DOMException("WRONG_DOCUMENT_ERR");
            }
        }

        function assertNodeNotReadOnly(node) {
            if (getReadonlyAncestor(node, true)) {
                throw new DOMException("NO_MODIFICATION_ALLOWED_ERR");
            }
        }

        function assertNode(node, codeName) {
            if (!node) {
                throw new DOMException(codeName);
            }
        }

        function isValidOffset(node, offset) {
            return offset <= (isCharacterDataNode(node) ? node.length : node.childNodes.length);
        }

        function isRangeValid(range) {
            return (!!range.startContainer && !!range.endContainer &&
                    !(crashyTextNodes && (dom.isBrokenNode(range.startContainer) || dom.isBrokenNode(range.endContainer))) &&
                    getRootContainer(range.startContainer) == getRootContainer(range.endContainer) &&
                    isValidOffset(range.startContainer, range.startOffset) &&
                    isValidOffset(range.endContainer, range.endOffset));
        }

        function assertRangeValid(range) {
            if (!isRangeValid(range)) {
                throw new Error("Range error: Range is not valid. This usually happens after DOM mutation. Range: (" + range.inspect() + ")");
            }
        }

        /*----------------------------------------------------------------------------------------------------------------*/

        // Test the browser's innerHTML support to decide how to implement createContextualFragment
        var styleEl = document.createElement("style");
        var htmlParsingConforms = false;
        try {
            styleEl.innerHTML = "<b>x</b>";
            htmlParsingConforms = (styleEl.firstChild.nodeType == 3); // Opera incorrectly creates an element node
        } catch (e) {
            // IE 6 and 7 throw
        }

        api.features.htmlParsingConforms = htmlParsingConforms;

        var createContextualFragment = htmlParsingConforms ?

            // Implementation as per HTML parsing spec, trusting in the browser's implementation of innerHTML. See
            // discussion and base code for this implementation at issue 67.
            // Spec: http://html5.org/specs/dom-parsing.html#extensions-to-the-range-interface
            // Thanks to Aleks Williams.
            function(fragmentStr) {
                // "Let node the context object's start's node."
                var node = this.startContainer;
                var doc = getDocument(node);

                // "If the context object's start's node is null, raise an INVALID_STATE_ERR
                // exception and abort these steps."
                if (!node) {
                    throw new DOMException("INVALID_STATE_ERR");
                }

                // "Let element be as follows, depending on node's interface:"
                // Document, Document Fragment: null
                var el = null;

                // "Element: node"
                if (node.nodeType == 1) {
                    el = node;

                // "Text, Comment: node's parentElement"
                } else if (isCharacterDataNode(node)) {
                    el = dom.parentElement(node);
                }

                // "If either element is null or element's ownerDocument is an HTML document
                // and element's local name is "html" and element's namespace is the HTML
                // namespace"
                if (el === null || (
                    el.nodeName == "HTML" &&
                    dom.isHtmlNamespace(getDocument(el).documentElement) &&
                    dom.isHtmlNamespace(el)
                )) {

                // "let element be a new Element with "body" as its local name and the HTML
                // namespace as its namespace.""
                    el = doc.createElement("body");
                } else {
                    el = el.cloneNode(false);
                }

                // "If the node's document is an HTML document: Invoke the HTML fragment parsing algorithm."
                // "If the node's document is an XML document: Invoke the XML fragment parsing algorithm."
                // "In either case, the algorithm must be invoked with fragment as the input
                // and element as the context element."
                el.innerHTML = fragmentStr;

                // "If this raises an exception, then abort these steps. Otherwise, let new
                // children be the nodes returned."

                // "Let fragment be a new DocumentFragment."
                // "Append all new children to fragment."
                // "Return fragment."
                return dom.fragmentFromNodeChildren(el);
            } :

            // In this case, innerHTML cannot be trusted, so fall back to a simpler, non-conformant implementation that
            // previous versions of Rangy used (with the exception of using a body element rather than a div)
            function(fragmentStr) {
                var doc = getRangeDocument(this);
                var el = doc.createElement("body");
                el.innerHTML = fragmentStr;

                return dom.fragmentFromNodeChildren(el);
            };

        function splitRangeBoundaries(range, positionsToPreserve) {
            assertRangeValid(range);

            var sc = range.startContainer, so = range.startOffset, ec = range.endContainer, eo = range.endOffset;
            var startEndSame = (sc === ec);

            if (isCharacterDataNode(ec) && eo > 0 && eo < ec.length) {
                splitDataNode(ec, eo, positionsToPreserve);
            }

            if (isCharacterDataNode(sc) && so > 0 && so < sc.length) {
                sc = splitDataNode(sc, so, positionsToPreserve);
                if (startEndSame) {
                    eo -= so;
                    ec = sc;
                } else if (ec == sc.parentNode && eo >= getNodeIndex(sc)) {
                    eo++;
                }
                so = 0;
            }
            range.setStartAndEnd(sc, so, ec, eo);
        }

        function rangeToHtml(range) {
            assertRangeValid(range);
            var container = range.commonAncestorContainer.parentNode.cloneNode(false);
            container.appendChild( range.cloneContents() );
            return container.innerHTML;
        }

        /*----------------------------------------------------------------------------------------------------------------*/

        var rangeProperties = ["startContainer", "startOffset", "endContainer", "endOffset", "collapsed",
            "commonAncestorContainer"];

        var s2s = 0, s2e = 1, e2e = 2, e2s = 3;
        var n_b = 0, n_a = 1, n_b_a = 2, n_i = 3;

        util.extend(api.rangePrototype, {
            compareBoundaryPoints: function(how, range) {
                assertRangeValid(this);
                assertSameDocumentOrFragment(this.startContainer, range.startContainer);

                var nodeA, offsetA, nodeB, offsetB;
                var prefixA = (how == e2s || how == s2s) ? "start" : "end";
                var prefixB = (how == s2e || how == s2s) ? "start" : "end";
                nodeA = this[prefixA + "Container"];
                offsetA = this[prefixA + "Offset"];
                nodeB = range[prefixB + "Container"];
                offsetB = range[prefixB + "Offset"];
                return comparePoints(nodeA, offsetA, nodeB, offsetB);
            },

            insertNode: function(node) {
                assertRangeValid(this);
                assertValidNodeType(node, insertableNodeTypes);
                assertNodeNotReadOnly(this.startContainer);

                if (isOrIsAncestorOf(node, this.startContainer)) {
                    throw new DOMException("HIERARCHY_REQUEST_ERR");
                }

                // No check for whether the container of the start of the Range is of a type that does not allow
                // children of the type of node: the browser's DOM implementation should do this for us when we attempt
                // to add the node

                var firstNodeInserted = insertNodeAtPosition(node, this.startContainer, this.startOffset);
                this.setStartBefore(firstNodeInserted);
            },

            cloneContents: function() {
                assertRangeValid(this);

                var clone, frag;
                if (this.collapsed) {
                    return getRangeDocument(this).createDocumentFragment();
                } else {
                    if (this.startContainer === this.endContainer && isCharacterDataNode(this.startContainer)) {
                        clone = this.startContainer.cloneNode(true);
                        clone.data = clone.data.slice(this.startOffset, this.endOffset);
                        frag = getRangeDocument(this).createDocumentFragment();
                        frag.appendChild(clone);
                        return frag;
                    } else {
                        var iterator = new RangeIterator(this, true);
                        clone = cloneSubtree(iterator);
                        iterator.detach();
                    }
                    return clone;
                }
            },

            canSurroundContents: function() {
                assertRangeValid(this);
                assertNodeNotReadOnly(this.startContainer);
                assertNodeNotReadOnly(this.endContainer);

                // Check if the contents can be surrounded. Specifically, this means whether the range partially selects
                // no non-text nodes.
                var iterator = new RangeIterator(this, true);
                var boundariesInvalid = (iterator._first && (isNonTextPartiallySelected(iterator._first, this)) ||
                        (iterator._last && isNonTextPartiallySelected(iterator._last, this)));
                iterator.detach();
                return !boundariesInvalid;
            },

            surroundContents: function(node) {
                assertValidNodeType(node, surroundNodeTypes);

                if (!this.canSurroundContents()) {
                    throw new DOMException("INVALID_STATE_ERR");
                }

                // Extract the contents
                var content = this.extractContents();

                // Clear the children of the node
                if (node.hasChildNodes()) {
                    while (node.lastChild) {
                        node.removeChild(node.lastChild);
                    }
                }

                // Insert the new node and add the extracted contents
                insertNodeAtPosition(node, this.startContainer, this.startOffset);
                node.appendChild(content);

                this.selectNode(node);
            },

            cloneRange: function() {
                assertRangeValid(this);
                var range = new Range(getRangeDocument(this));
                var i = rangeProperties.length, prop;
                while (i--) {
                    prop = rangeProperties[i];
                    range[prop] = this[prop];
                }
                return range;
            },

            toString: function() {
                assertRangeValid(this);
                var sc = this.startContainer;
                if (sc === this.endContainer && isCharacterDataNode(sc)) {
                    return (sc.nodeType == 3 || sc.nodeType == 4) ? sc.data.slice(this.startOffset, this.endOffset) : "";
                } else {
                    var textParts = [], iterator = new RangeIterator(this, true);
                    iterateSubtree(iterator, function(node) {
                        // Accept only text or CDATA nodes, not comments
                        if (node.nodeType == 3 || node.nodeType == 4) {
                            textParts.push(node.data);
                        }
                    });
                    iterator.detach();
                    return textParts.join("");
                }
            },

            // The methods below are all non-standard. The following batch were introduced by Mozilla but have since
            // been removed from Mozilla.

            compareNode: function(node) {
                assertRangeValid(this);

                var parent = node.parentNode;
                var nodeIndex = getNodeIndex(node);

                if (!parent) {
                    throw new DOMException("NOT_FOUND_ERR");
                }

                var startComparison = this.comparePoint(parent, nodeIndex),
                    endComparison = this.comparePoint(parent, nodeIndex + 1);

                if (startComparison < 0) { // Node starts before
                    return (endComparison > 0) ? n_b_a : n_b;
                } else {
                    return (endComparison > 0) ? n_a : n_i;
                }
            },

            comparePoint: function(node, offset) {
                assertRangeValid(this);
                assertNode(node, "HIERARCHY_REQUEST_ERR");
                assertSameDocumentOrFragment(node, this.startContainer);

                if (comparePoints(node, offset, this.startContainer, this.startOffset) < 0) {
                    return -1;
                } else if (comparePoints(node, offset, this.endContainer, this.endOffset) > 0) {
                    return 1;
                }
                return 0;
            },

            createContextualFragment: createContextualFragment,

            toHtml: function() {
                return rangeToHtml(this);
            },

            // touchingIsIntersecting determines whether this method considers a node that borders a range intersects
            // with it (as in WebKit) or not (as in Gecko pre-1.9, and the default)
            intersectsNode: function(node, touchingIsIntersecting) {
                assertRangeValid(this);
                if (getRootContainer(node) != getRangeRoot(this)) {
                    return false;
                }

                var parent = node.parentNode, offset = getNodeIndex(node);
                if (!parent) {
                    return true;
                }

                var startComparison = comparePoints(parent, offset, this.endContainer, this.endOffset),
                    endComparison = comparePoints(parent, offset + 1, this.startContainer, this.startOffset);

                return touchingIsIntersecting ? startComparison <= 0 && endComparison >= 0 : startComparison < 0 && endComparison > 0;
            },

            isPointInRange: function(node, offset) {
                assertRangeValid(this);
                assertNode(node, "HIERARCHY_REQUEST_ERR");
                assertSameDocumentOrFragment(node, this.startContainer);

                return (comparePoints(node, offset, this.startContainer, this.startOffset) >= 0) &&
                       (comparePoints(node, offset, this.endContainer, this.endOffset) <= 0);
            },

            // The methods below are non-standard and invented by me.

            // Sharing a boundary start-to-end or end-to-start does not count as intersection.
            intersectsRange: function(range) {
                return rangesIntersect(this, range, false);
            },

            // Sharing a boundary start-to-end or end-to-start does count as intersection.
            intersectsOrTouchesRange: function(range) {
                return rangesIntersect(this, range, true);
            },

            intersection: function(range) {
                if (this.intersectsRange(range)) {
                    var startComparison = comparePoints(this.startContainer, this.startOffset, range.startContainer, range.startOffset),
                        endComparison = comparePoints(this.endContainer, this.endOffset, range.endContainer, range.endOffset);

                    var intersectionRange = this.cloneRange();
                    if (startComparison == -1) {
                        intersectionRange.setStart(range.startContainer, range.startOffset);
                    }
                    if (endComparison == 1) {
                        intersectionRange.setEnd(range.endContainer, range.endOffset);
                    }
                    return intersectionRange;
                }
                return null;
            },

            union: function(range) {
                if (this.intersectsOrTouchesRange(range)) {
                    var unionRange = this.cloneRange();
                    if (comparePoints(range.startContainer, range.startOffset, this.startContainer, this.startOffset) == -1) {
                        unionRange.setStart(range.startContainer, range.startOffset);
                    }
                    if (comparePoints(range.endContainer, range.endOffset, this.endContainer, this.endOffset) == 1) {
                        unionRange.setEnd(range.endContainer, range.endOffset);
                    }
                    return unionRange;
                } else {
                    throw new DOMException("Ranges do not intersect");
                }
            },

            containsNode: function(node, allowPartial) {
                if (allowPartial) {
                    return this.intersectsNode(node, false);
                } else {
                    return this.compareNode(node) == n_i;
                }
            },

            containsNodeContents: function(node) {
                return this.comparePoint(node, 0) >= 0 && this.comparePoint(node, getNodeLength(node)) <= 0;
            },

            containsRange: function(range) {
                var intersection = this.intersection(range);
                return intersection !== null && range.equals(intersection);
            },

            containsNodeText: function(node) {
                var nodeRange = this.cloneRange();
                nodeRange.selectNode(node);
                var textNodes = nodeRange.getNodes([3]);
                if (textNodes.length > 0) {
                    nodeRange.setStart(textNodes[0], 0);
                    var lastTextNode = textNodes.pop();
                    nodeRange.setEnd(lastTextNode, lastTextNode.length);
                    return this.containsRange(nodeRange);
                } else {
                    return this.containsNodeContents(node);
                }
            },

            getNodes: function(nodeTypes, filter) {
                assertRangeValid(this);
                return getNodesInRange(this, nodeTypes, filter);
            },

            getDocument: function() {
                return getRangeDocument(this);
            },

            collapseBefore: function(node) {
                this.setEndBefore(node);
                this.collapse(false);
            },

            collapseAfter: function(node) {
                this.setStartAfter(node);
                this.collapse(true);
            },

            getBookmark: function(containerNode) {
                var doc = getRangeDocument(this);
                var preSelectionRange = api.createRange(doc);
                containerNode = containerNode || dom.getBody(doc);
                preSelectionRange.selectNodeContents(containerNode);
                var range = this.intersection(preSelectionRange);
                var start = 0, end = 0;
                if (range) {
                    preSelectionRange.setEnd(range.startContainer, range.startOffset);
                    start = preSelectionRange.toString().length;
                    end = start + range.toString().length;
                }

                return {
                    start: start,
                    end: end,
                    containerNode: containerNode
                };
            },

            moveToBookmark: function(bookmark) {
                var containerNode = bookmark.containerNode;
                var charIndex = 0;
                this.setStart(containerNode, 0);
                this.collapse(true);
                var nodeStack = [containerNode], node, foundStart = false, stop = false;
                var nextCharIndex, i, childNodes;

                while (!stop && (node = nodeStack.pop())) {
                    if (node.nodeType == 3) {
                        nextCharIndex = charIndex + node.length;
                        if (!foundStart && bookmark.start >= charIndex && bookmark.start <= nextCharIndex) {
                            this.setStart(node, bookmark.start - charIndex);
                            foundStart = true;
                        }
                        if (foundStart && bookmark.end >= charIndex && bookmark.end <= nextCharIndex) {
                            this.setEnd(node, bookmark.end - charIndex);
                            stop = true;
                        }
                        charIndex = nextCharIndex;
                    } else {
                        childNodes = node.childNodes;
                        i = childNodes.length;
                        while (i--) {
                            nodeStack.push(childNodes[i]);
                        }
                    }
                }
            },

            getName: function() {
                return "DomRange";
            },

            equals: function(range) {
                return Range.rangesEqual(this, range);
            },

            isValid: function() {
                return isRangeValid(this);
            },

            inspect: function() {
                return inspect(this);
            },

            detach: function() {
                // In DOM4, detach() is now a no-op.
            }
        });

        function copyComparisonConstantsToObject(obj) {
            obj.START_TO_START = s2s;
            obj.START_TO_END = s2e;
            obj.END_TO_END = e2e;
            obj.END_TO_START = e2s;

            obj.NODE_BEFORE = n_b;
            obj.NODE_AFTER = n_a;
            obj.NODE_BEFORE_AND_AFTER = n_b_a;
            obj.NODE_INSIDE = n_i;
        }

        function copyComparisonConstants(constructor) {
            copyComparisonConstantsToObject(constructor);
            copyComparisonConstantsToObject(constructor.prototype);
        }

        function createRangeContentRemover(remover, boundaryUpdater) {
            return function() {
                assertRangeValid(this);

                var sc = this.startContainer, so = this.startOffset, root = this.commonAncestorContainer;

                var iterator = new RangeIterator(this, true);

                // Work out where to position the range after content removal
                var node, boundary;
                if (sc !== root) {
                    node = getClosestAncestorIn(sc, root, true);
                    boundary = getBoundaryAfterNode(node);
                    sc = boundary.node;
                    so = boundary.offset;
                }

                // Check none of the range is read-only
                iterateSubtree(iterator, assertNodeNotReadOnly);

                iterator.reset();

                // Remove the content
                var returnValue = remover(iterator);
                iterator.detach();

                // Move to the new position
                boundaryUpdater(this, sc, so, sc, so);

                return returnValue;
            };
        }

        function createPrototypeRange(constructor, boundaryUpdater) {
            function createBeforeAfterNodeSetter(isBefore, isStart) {
                return function(node) {
                    assertValidNodeType(node, beforeAfterNodeTypes);
                    assertValidNodeType(getRootContainer(node), rootContainerNodeTypes);

                    var boundary = (isBefore ? getBoundaryBeforeNode : getBoundaryAfterNode)(node);
                    (isStart ? setRangeStart : setRangeEnd)(this, boundary.node, boundary.offset);
                };
            }

            function setRangeStart(range, node, offset) {
                var ec = range.endContainer, eo = range.endOffset;
                if (node !== range.startContainer || offset !== range.startOffset) {
                    // Check the root containers of the range and the new boundary, and also check whether the new boundary
                    // is after the current end. In either case, collapse the range to the new position
                    if (getRootContainer(node) != getRootContainer(ec) || comparePoints(node, offset, ec, eo) == 1) {
                        ec = node;
                        eo = offset;
                    }
                    boundaryUpdater(range, node, offset, ec, eo);
                }
            }

            function setRangeEnd(range, node, offset) {
                var sc = range.startContainer, so = range.startOffset;
                if (node !== range.endContainer || offset !== range.endOffset) {
                    // Check the root containers of the range and the new boundary, and also check whether the new boundary
                    // is after the current end. In either case, collapse the range to the new position
                    if (getRootContainer(node) != getRootContainer(sc) || comparePoints(node, offset, sc, so) == -1) {
                        sc = node;
                        so = offset;
                    }
                    boundaryUpdater(range, sc, so, node, offset);
                }
            }

            // Set up inheritance
            var F = function() {};
            F.prototype = api.rangePrototype;
            constructor.prototype = new F();

            util.extend(constructor.prototype, {
                setStart: function(node, offset) {
                    assertNoDocTypeNotationEntityAncestor(node, true);
                    assertValidOffset(node, offset);

                    setRangeStart(this, node, offset);
                },

                setEnd: function(node, offset) {
                    assertNoDocTypeNotationEntityAncestor(node, true);
                    assertValidOffset(node, offset);

                    setRangeEnd(this, node, offset);
                },

                /**
                 * Convenience method to set a range's start and end boundaries. Overloaded as follows:
                 * - Two parameters (node, offset) creates a collapsed range at that position
                 * - Three parameters (node, startOffset, endOffset) creates a range contained with node starting at
                 *   startOffset and ending at endOffset
                 * - Four parameters (startNode, startOffset, endNode, endOffset) creates a range starting at startOffset in
                 *   startNode and ending at endOffset in endNode
                 */
                setStartAndEnd: function() {
                    var args = arguments;
                    var sc = args[0], so = args[1], ec = sc, eo = so;

                    switch (args.length) {
                        case 3:
                            eo = args[2];
                            break;
                        case 4:
                            ec = args[2];
                            eo = args[3];
                            break;
                    }

                    boundaryUpdater(this, sc, so, ec, eo);
                },

                setBoundary: function(node, offset, isStart) {
                    this["set" + (isStart ? "Start" : "End")](node, offset);
                },

                setStartBefore: createBeforeAfterNodeSetter(true, true),
                setStartAfter: createBeforeAfterNodeSetter(false, true),
                setEndBefore: createBeforeAfterNodeSetter(true, false),
                setEndAfter: createBeforeAfterNodeSetter(false, false),

                collapse: function(isStart) {
                    assertRangeValid(this);
                    if (isStart) {
                        boundaryUpdater(this, this.startContainer, this.startOffset, this.startContainer, this.startOffset);
                    } else {
                        boundaryUpdater(this, this.endContainer, this.endOffset, this.endContainer, this.endOffset);
                    }
                },

                selectNodeContents: function(node) {
                    assertNoDocTypeNotationEntityAncestor(node, true);

                    boundaryUpdater(this, node, 0, node, getNodeLength(node));
                },

                selectNode: function(node) {
                    assertNoDocTypeNotationEntityAncestor(node, false);
                    assertValidNodeType(node, beforeAfterNodeTypes);

                    var start = getBoundaryBeforeNode(node), end = getBoundaryAfterNode(node);
                    boundaryUpdater(this, start.node, start.offset, end.node, end.offset);
                },

                extractContents: createRangeContentRemover(extractSubtree, boundaryUpdater),

                deleteContents: createRangeContentRemover(deleteSubtree, boundaryUpdater),

                canSurroundContents: function() {
                    assertRangeValid(this);
                    assertNodeNotReadOnly(this.startContainer);
                    assertNodeNotReadOnly(this.endContainer);

                    // Check if the contents can be surrounded. Specifically, this means whether the range partially selects
                    // no non-text nodes.
                    var iterator = new RangeIterator(this, true);
                    var boundariesInvalid = (iterator._first && isNonTextPartiallySelected(iterator._first, this) ||
                            (iterator._last && isNonTextPartiallySelected(iterator._last, this)));
                    iterator.detach();
                    return !boundariesInvalid;
                },

                splitBoundaries: function() {
                    splitRangeBoundaries(this);
                },

                splitBoundariesPreservingPositions: function(positionsToPreserve) {
                    splitRangeBoundaries(this, positionsToPreserve);
                },

                normalizeBoundaries: function() {
                    assertRangeValid(this);

                    var sc = this.startContainer, so = this.startOffset, ec = this.endContainer, eo = this.endOffset;

                    var mergeForward = function(node) {
                        var sibling = node.nextSibling;
                        if (sibling && sibling.nodeType == node.nodeType) {
                            ec = node;
                            eo = node.length;
                            node.appendData(sibling.data);
                            removeNode(sibling);
                        }
                    };

                    var mergeBackward = function(node) {
                        var sibling = node.previousSibling;
                        if (sibling && sibling.nodeType == node.nodeType) {
                            sc = node;
                            var nodeLength = node.length;
                            so = sibling.length;
                            node.insertData(0, sibling.data);
                            removeNode(sibling);
                            if (sc == ec) {
                                eo += so;
                                ec = sc;
                            } else if (ec == node.parentNode) {
                                var nodeIndex = getNodeIndex(node);
                                if (eo == nodeIndex) {
                                    ec = node;
                                    eo = nodeLength;
                                } else if (eo > nodeIndex) {
                                    eo--;
                                }
                            }
                        }
                    };

                    var normalizeStart = true;
                    var sibling;

                    if (isCharacterDataNode(ec)) {
                        if (eo == ec.length) {
                            mergeForward(ec);
                        } else if (eo == 0) {
                            sibling = ec.previousSibling;
                            if (sibling && sibling.nodeType == ec.nodeType) {
                                eo = sibling.length;
                                if (sc == ec) {
                                    normalizeStart = false;
                                }
                                sibling.appendData(ec.data);
                                removeNode(ec);
                                ec = sibling;
                            }
                        }
                    } else {
                        if (eo > 0) {
                            var endNode = ec.childNodes[eo - 1];
                            if (endNode && isCharacterDataNode(endNode)) {
                                mergeForward(endNode);
                            }
                        }
                        normalizeStart = !this.collapsed;
                    }

                    if (normalizeStart) {
                        if (isCharacterDataNode(sc)) {
                            if (so == 0) {
                                mergeBackward(sc);
                            } else if (so == sc.length) {
                                sibling = sc.nextSibling;
                                if (sibling && sibling.nodeType == sc.nodeType) {
                                    if (ec == sibling) {
                                        ec = sc;
                                        eo += sc.length;
                                    }
                                    sc.appendData(sibling.data);
                                    removeNode(sibling);
                                }
                            }
                        } else {
                            if (so < sc.childNodes.length) {
                                var startNode = sc.childNodes[so];
                                if (startNode && isCharacterDataNode(startNode)) {
                                    mergeBackward(startNode);
                                }
                            }
                        }
                    } else {
                        sc = ec;
                        so = eo;
                    }

                    boundaryUpdater(this, sc, so, ec, eo);
                },

                collapseToPoint: function(node, offset) {
                    assertNoDocTypeNotationEntityAncestor(node, true);
                    assertValidOffset(node, offset);
                    this.setStartAndEnd(node, offset);
                }
            });

            copyComparisonConstants(constructor);
        }

        /*----------------------------------------------------------------------------------------------------------------*/

        // Updates commonAncestorContainer and collapsed after boundary change
        function updateCollapsedAndCommonAncestor(range) {
            range.collapsed = (range.startContainer === range.endContainer && range.startOffset === range.endOffset);
            range.commonAncestorContainer = range.collapsed ?
                range.startContainer : dom.getCommonAncestor(range.startContainer, range.endContainer);
        }

        function updateBoundaries(range, startContainer, startOffset, endContainer, endOffset) {
            range.startContainer = startContainer;
            range.startOffset = startOffset;
            range.endContainer = endContainer;
            range.endOffset = endOffset;
            range.document = dom.getDocument(startContainer);

            updateCollapsedAndCommonAncestor(range);
        }

        function Range(doc) {
            this.startContainer = doc;
            this.startOffset = 0;
            this.endContainer = doc;
            this.endOffset = 0;
            this.document = doc;
            updateCollapsedAndCommonAncestor(this);
        }

        createPrototypeRange(Range, updateBoundaries);

        util.extend(Range, {
            rangeProperties: rangeProperties,
            RangeIterator: RangeIterator,
            copyComparisonConstants: copyComparisonConstants,
            createPrototypeRange: createPrototypeRange,
            inspect: inspect,
            toHtml: rangeToHtml,
            getRangeDocument: getRangeDocument,
            rangesEqual: function(r1, r2) {
                return r1.startContainer === r2.startContainer &&
                    r1.startOffset === r2.startOffset &&
                    r1.endContainer === r2.endContainer &&
                    r1.endOffset === r2.endOffset;
            }
        });

        api.DomRange = Range;
    });

    /*----------------------------------------------------------------------------------------------------------------*/

    // Wrappers for the browser's native DOM Range and/or TextRange implementation
    api.createCoreModule("WrappedRange", ["DomRange"], function(api, module) {
        var WrappedRange, WrappedTextRange;
        var dom = api.dom;
        var util = api.util;
        var DomPosition = dom.DomPosition;
        var DomRange = api.DomRange;
        var getBody = dom.getBody;
        var getContentDocument = dom.getContentDocument;
        var isCharacterDataNode = dom.isCharacterDataNode;


        /*----------------------------------------------------------------------------------------------------------------*/

        if (api.features.implementsDomRange) {
            // This is a wrapper around the browser's native DOM Range. It has two aims:
            // - Provide workarounds for specific browser bugs
            // - provide convenient extensions, which are inherited from Rangy's DomRange

            (function() {
                var rangeProto;
                var rangeProperties = DomRange.rangeProperties;

                function updateRangeProperties(range) {
                    var i = rangeProperties.length, prop;
                    while (i--) {
                        prop = rangeProperties[i];
                        range[prop] = range.nativeRange[prop];
                    }
                    // Fix for broken collapsed property in IE 9.
                    range.collapsed = (range.startContainer === range.endContainer && range.startOffset === range.endOffset);
                }

                function updateNativeRange(range, startContainer, startOffset, endContainer, endOffset) {
                    var startMoved = (range.startContainer !== startContainer || range.startOffset != startOffset);
                    var endMoved = (range.endContainer !== endContainer || range.endOffset != endOffset);
                    var nativeRangeDifferent = !range.equals(range.nativeRange);

                    // Always set both boundaries for the benefit of IE9 (see issue 35)
                    if (startMoved || endMoved || nativeRangeDifferent) {
                        range.setEnd(endContainer, endOffset);
                        range.setStart(startContainer, startOffset);
                    }
                }

                var createBeforeAfterNodeSetter;

                WrappedRange = function(range) {
                    if (!range) {
                        throw module.createError("WrappedRange: Range must be specified");
                    }
                    this.nativeRange = range;
                    updateRangeProperties(this);
                };

                DomRange.createPrototypeRange(WrappedRange, updateNativeRange);

                rangeProto = WrappedRange.prototype;

                rangeProto.selectNode = function(node) {
                    this.nativeRange.selectNode(node);
                    updateRangeProperties(this);
                };

                rangeProto.cloneContents = function() {
                    return this.nativeRange.cloneContents();
                };

                // Due to a long-standing Firefox bug that I have not been able to find a reliable way to detect,
                // insertNode() is never delegated to the native range.

                rangeProto.surroundContents = function(node) {
                    this.nativeRange.surroundContents(node);
                    updateRangeProperties(this);
                };

                rangeProto.collapse = function(isStart) {
                    this.nativeRange.collapse(isStart);
                    updateRangeProperties(this);
                };

                rangeProto.cloneRange = function() {
                    return new WrappedRange(this.nativeRange.cloneRange());
                };

                rangeProto.refresh = function() {
                    updateRangeProperties(this);
                };

                rangeProto.toString = function() {
                    return this.nativeRange.toString();
                };

                // Create test range and node for feature detection

                var testTextNode = document.createTextNode("test");
                getBody(document).appendChild(testTextNode);
                var range = document.createRange();

                /*--------------------------------------------------------------------------------------------------------*/

                // Test for Firefox 2 bug that prevents moving the start of a Range to a point after its current end and
                // correct for it

                range.setStart(testTextNode, 0);
                range.setEnd(testTextNode, 0);

                try {
                    range.setStart(testTextNode, 1);

                    rangeProto.setStart = function(node, offset) {
                        this.nativeRange.setStart(node, offset);
                        updateRangeProperties(this);
                    };

                    rangeProto.setEnd = function(node, offset) {
                        this.nativeRange.setEnd(node, offset);
                        updateRangeProperties(this);
                    };

                    createBeforeAfterNodeSetter = function(name) {
                        return function(node) {
                            this.nativeRange[name](node);
                            updateRangeProperties(this);
                        };
                    };

                } catch(ex) {

                    rangeProto.setStart = function(node, offset) {
                        try {
                            this.nativeRange.setStart(node, offset);
                        } catch (ex) {
                            this.nativeRange.setEnd(node, offset);
                            this.nativeRange.setStart(node, offset);
                        }
                        updateRangeProperties(this);
                    };

                    rangeProto.setEnd = function(node, offset) {
                        try {
                            this.nativeRange.setEnd(node, offset);
                        } catch (ex) {
                            this.nativeRange.setStart(node, offset);
                            this.nativeRange.setEnd(node, offset);
                        }
                        updateRangeProperties(this);
                    };

                    createBeforeAfterNodeSetter = function(name, oppositeName) {
                        return function(node) {
                            try {
                                this.nativeRange[name](node);
                            } catch (ex) {
                                this.nativeRange[oppositeName](node);
                                this.nativeRange[name](node);
                            }
                            updateRangeProperties(this);
                        };
                    };
                }

                rangeProto.setStartBefore = createBeforeAfterNodeSetter("setStartBefore", "setEndBefore");
                rangeProto.setStartAfter = createBeforeAfterNodeSetter("setStartAfter", "setEndAfter");
                rangeProto.setEndBefore = createBeforeAfterNodeSetter("setEndBefore", "setStartBefore");
                rangeProto.setEndAfter = createBeforeAfterNodeSetter("setEndAfter", "setStartAfter");

                /*--------------------------------------------------------------------------------------------------------*/

                // Always use DOM4-compliant selectNodeContents implementation: it's simpler and less code than testing
                // whether the native implementation can be trusted
                rangeProto.selectNodeContents = function(node) {
                    this.setStartAndEnd(node, 0, dom.getNodeLength(node));
                };

                /*--------------------------------------------------------------------------------------------------------*/

                // Test for and correct WebKit bug that has the behaviour of compareBoundaryPoints round the wrong way for
                // constants START_TO_END and END_TO_START: https://bugs.webkit.org/show_bug.cgi?id=20738

                range.selectNodeContents(testTextNode);
                range.setEnd(testTextNode, 3);

                var range2 = document.createRange();
                range2.selectNodeContents(testTextNode);
                range2.setEnd(testTextNode, 4);
                range2.setStart(testTextNode, 2);

                if (range.compareBoundaryPoints(range.START_TO_END, range2) == -1 &&
                        range.compareBoundaryPoints(range.END_TO_START, range2) == 1) {
                    // This is the wrong way round, so correct for it

                    rangeProto.compareBoundaryPoints = function(type, range) {
                        range = range.nativeRange || range;
                        if (type == range.START_TO_END) {
                            type = range.END_TO_START;
                        } else if (type == range.END_TO_START) {
                            type = range.START_TO_END;
                        }
                        return this.nativeRange.compareBoundaryPoints(type, range);
                    };
                } else {
                    rangeProto.compareBoundaryPoints = function(type, range) {
                        return this.nativeRange.compareBoundaryPoints(type, range.nativeRange || range);
                    };
                }

                /*--------------------------------------------------------------------------------------------------------*/

                // Test for IE deleteContents() and extractContents() bug and correct it. See issue 107.

                var el = document.createElement("div");
                el.innerHTML = "123";
                var textNode = el.firstChild;
                var body = getBody(document);
                body.appendChild(el);

                range.setStart(textNode, 1);
                range.setEnd(textNode, 2);
                range.deleteContents();

                if (textNode.data == "13") {
                    // Behaviour is correct per DOM4 Range so wrap the browser's implementation of deleteContents() and
                    // extractContents()
                    rangeProto.deleteContents = function() {
                        this.nativeRange.deleteContents();
                        updateRangeProperties(this);
                    };

                    rangeProto.extractContents = function() {
                        var frag = this.nativeRange.extractContents();
                        updateRangeProperties(this);
                        return frag;
                    };
                } else {
                }

                body.removeChild(el);
                body = null;

                /*--------------------------------------------------------------------------------------------------------*/

                // Test for existence of createContextualFragment and delegate to it if it exists
                if (util.isHostMethod(range, "createContextualFragment")) {
                    rangeProto.createContextualFragment = function(fragmentStr) {
                        return this.nativeRange.createContextualFragment(fragmentStr);
                    };
                }

                /*--------------------------------------------------------------------------------------------------------*/

                // Clean up
                getBody(document).removeChild(testTextNode);

                rangeProto.getName = function() {
                    return "WrappedRange";
                };

                api.WrappedRange = WrappedRange;

                api.createNativeRange = function(doc) {
                    doc = getContentDocument(doc, module, "createNativeRange");
                    return doc.createRange();
                };
            })();
        }

        if (api.features.implementsTextRange) {
            /*
            This is a workaround for a bug where IE returns the wrong container element from the TextRange's parentElement()
            method. For example, in the following (where pipes denote the selection boundaries):

            <ul id="ul"><li id="a">| a </li><li id="b"> b |</li></ul>

            var range = document.selection.createRange();
            alert(range.parentElement().id); // Should alert "ul" but alerts "b"

            This method returns the common ancestor node of the following:
            - the parentElement() of the textRange
            - the parentElement() of the textRange after calling collapse(true)
            - the parentElement() of the textRange after calling collapse(false)
            */
            var getTextRangeContainerElement = function(textRange) {
                var parentEl = textRange.parentElement();
                var range = textRange.duplicate();
                range.collapse(true);
                var startEl = range.parentElement();
                range = textRange.duplicate();
                range.collapse(false);
                var endEl = range.parentElement();
                var startEndContainer = (startEl == endEl) ? startEl : dom.getCommonAncestor(startEl, endEl);

                return startEndContainer == parentEl ? startEndContainer : dom.getCommonAncestor(parentEl, startEndContainer);
            };

            var textRangeIsCollapsed = function(textRange) {
                return textRange.compareEndPoints("StartToEnd", textRange) == 0;
            };

            // Gets the boundary of a TextRange expressed as a node and an offset within that node. This function started
            // out as an improved version of code found in Tim Cameron Ryan's IERange (http://code.google.com/p/ierange/)
            // but has grown, fixing problems with line breaks in preformatted text, adding workaround for IE TextRange
            // bugs, handling for inputs and images, plus optimizations.
            var getTextRangeBoundaryPosition = function(textRange, wholeRangeContainerElement, isStart, isCollapsed, startInfo) {
                var workingRange = textRange.duplicate();
                workingRange.collapse(isStart);
                var containerElement = workingRange.parentElement();

                // Sometimes collapsing a TextRange that's at the start of a text node can move it into the previous node, so
                // check for that
                if (!dom.isOrIsAncestorOf(wholeRangeContainerElement, containerElement)) {
                    containerElement = wholeRangeContainerElement;
                }


                // Deal with nodes that cannot "contain rich HTML markup". In practice, this means form inputs, images and
                // similar. See http://msdn.microsoft.com/en-us/library/aa703950%28VS.85%29.aspx
                if (!containerElement.canHaveHTML) {
                    var pos = new DomPosition(containerElement.parentNode, dom.getNodeIndex(containerElement));
                    return {
                        boundaryPosition: pos,
                        nodeInfo: {
                            nodeIndex: pos.offset,
                            containerElement: pos.node
                        }
                    };
                }

                var workingNode = dom.getDocument(containerElement).createElement("span");

                // Workaround for HTML5 Shiv's insane violation of document.createElement(). See Rangy issue 104 and HTML5
                // Shiv issue 64: https://github.com/aFarkas/html5shiv/issues/64
                if (workingNode.parentNode) {
                    dom.removeNode(workingNode);
                }

                var comparison, workingComparisonType = isStart ? "StartToStart" : "StartToEnd";
                var previousNode, nextNode, boundaryPosition, boundaryNode;
                var start = (startInfo && startInfo.containerElement == containerElement) ? startInfo.nodeIndex : 0;
                var childNodeCount = containerElement.childNodes.length;
                var end = childNodeCount;

                // Check end first. Code within the loop assumes that the endth child node of the container is definitely
                // after the range boundary.
                var nodeIndex = end;

                while (true) {
                    if (nodeIndex == childNodeCount) {
                        containerElement.appendChild(workingNode);
                    } else {
                        containerElement.insertBefore(workingNode, containerElement.childNodes[nodeIndex]);
                    }
                    workingRange.moveToElementText(workingNode);
                    comparison = workingRange.compareEndPoints(workingComparisonType, textRange);
                    if (comparison == 0 || start == end) {
                        break;
                    } else if (comparison == -1) {
                        if (end == start + 1) {
                            // We know the endth child node is after the range boundary, so we must be done.
                            break;
                        } else {
                            start = nodeIndex;
                        }
                    } else {
                        end = (end == start + 1) ? start : nodeIndex;
                    }
                    nodeIndex = Math.floor((start + end) / 2);
                    containerElement.removeChild(workingNode);
                }


                // We've now reached or gone past the boundary of the text range we're interested in
                // so have identified the node we want
                boundaryNode = workingNode.nextSibling;

                if (comparison == -1 && boundaryNode && isCharacterDataNode(boundaryNode)) {
                    // This is a character data node (text, comment, cdata). The working range is collapsed at the start of
                    // the node containing the text range's boundary, so we move the end of the working range to the
                    // boundary point and measure the length of its text to get the boundary's offset within the node.
                    workingRange.setEndPoint(isStart ? "EndToStart" : "EndToEnd", textRange);

                    var offset;

                    if (/[\r\n]/.test(boundaryNode.data)) {
                        /*
                        For the particular case of a boundary within a text node containing rendered line breaks (within a
                        <pre> element, for example), we need a slightly complicated approach to get the boundary's offset in
                        IE. The facts:

                        - Each line break is represented as \r in the text node's data/nodeValue properties
                        - Each line break is represented as \r\n in the TextRange's 'text' property
                        - The 'text' property of the TextRange does not contain trailing line breaks

                        To get round the problem presented by the final fact above, we can use the fact that TextRange's
                        moveStart() and moveEnd() methods return the actual number of characters moved, which is not
                        necessarily the same as the number of characters it was instructed to move. The simplest approach is
                        to use this to store the characters moved when moving both the start and end of the range to the
                        start of the document body and subtracting the start offset from the end offset (the
                        "move-negative-gazillion" method). However, this is extremely slow when the document is large and
                        the range is near the end of it. Clearly doing the mirror image (i.e. moving the range boundaries to
                        the end of the document) has the same problem.

                        Another approach that works is to use moveStart() to move the start boundary of the range up to the
                        end boundary one character at a time and incrementing a counter with the value returned by the
                        moveStart() call. However, the check for whether the start boundary has reached the end boundary is
                        expensive, so this method is slow (although unlike "move-negative-gazillion" is largely unaffected
                        by the location of the range within the document).

                        The approach used below is a hybrid of the two methods above. It uses the fact that a string
                        containing the TextRange's 'text' property with each \r\n converted to a single \r character cannot
                        be longer than the text of the TextRange, so the start of the range is moved that length initially
                        and then a character at a time to make up for any trailing line breaks not contained in the 'text'
                        property. This has good performance in most situations compared to the previous two methods.
                        */
                        var tempRange = workingRange.duplicate();
                        var rangeLength = tempRange.text.replace(/\r\n/g, "\r").length;

                        offset = tempRange.moveStart("character", rangeLength);
                        while ( (comparison = tempRange.compareEndPoints("StartToEnd", tempRange)) == -1) {
                            offset++;
                            tempRange.moveStart("character", 1);
                        }
                    } else {
                        offset = workingRange.text.length;
                    }
                    boundaryPosition = new DomPosition(boundaryNode, offset);
                } else {

                    // If the boundary immediately follows a character data node and this is the end boundary, we should favour
                    // a position within that, and likewise for a start boundary preceding a character data node
                    previousNode = (isCollapsed || !isStart) && workingNode.previousSibling;
                    nextNode = (isCollapsed || isStart) && workingNode.nextSibling;
                    if (nextNode && isCharacterDataNode(nextNode)) {
                        boundaryPosition = new DomPosition(nextNode, 0);
                    } else if (previousNode && isCharacterDataNode(previousNode)) {
                        boundaryPosition = new DomPosition(previousNode, previousNode.data.length);
                    } else {
                        boundaryPosition = new DomPosition(containerElement, dom.getNodeIndex(workingNode));
                    }
                }

                // Clean up
                dom.removeNode(workingNode);

                return {
                    boundaryPosition: boundaryPosition,
                    nodeInfo: {
                        nodeIndex: nodeIndex,
                        containerElement: containerElement
                    }
                };
            };

            // Returns a TextRange representing the boundary of a TextRange expressed as a node and an offset within that
            // node. This function started out as an optimized version of code found in Tim Cameron Ryan's IERange
            // (http://code.google.com/p/ierange/)
            var createBoundaryTextRange = function(boundaryPosition, isStart) {
                var boundaryNode, boundaryParent, boundaryOffset = boundaryPosition.offset;
                var doc = dom.getDocument(boundaryPosition.node);
                var workingNode, childNodes, workingRange = getBody(doc).createTextRange();
                var nodeIsDataNode = isCharacterDataNode(boundaryPosition.node);

                if (nodeIsDataNode) {
                    boundaryNode = boundaryPosition.node;
                    boundaryParent = boundaryNode.parentNode;
                } else {
                    childNodes = boundaryPosition.node.childNodes;
                    boundaryNode = (boundaryOffset < childNodes.length) ? childNodes[boundaryOffset] : null;
                    boundaryParent = boundaryPosition.node;
                }

                // Position the range immediately before the node containing the boundary
                workingNode = doc.createElement("span");

                // Making the working element non-empty element persuades IE to consider the TextRange boundary to be within
                // the element rather than immediately before or after it
                workingNode.innerHTML = "&#feff;";

                // insertBefore is supposed to work like appendChild if the second parameter is null. However, a bug report
                // for IERange suggests that it can crash the browser: http://code.google.com/p/ierange/issues/detail?id=12
                if (boundaryNode) {
                    boundaryParent.insertBefore(workingNode, boundaryNode);
                } else {
                    boundaryParent.appendChild(workingNode);
                }

                workingRange.moveToElementText(workingNode);
                workingRange.collapse(!isStart);

                // Clean up
                boundaryParent.removeChild(workingNode);

                // Move the working range to the text offset, if required
                if (nodeIsDataNode) {
                    workingRange[isStart ? "moveStart" : "moveEnd"]("character", boundaryOffset);
                }

                return workingRange;
            };

            /*------------------------------------------------------------------------------------------------------------*/

            // This is a wrapper around a TextRange, providing full DOM Range functionality using rangy's DomRange as a
            // prototype

            WrappedTextRange = function(textRange) {
                this.textRange = textRange;
                this.refresh();
            };

            WrappedTextRange.prototype = new DomRange(document);

            WrappedTextRange.prototype.refresh = function() {
                var start, end, startBoundary;

                // TextRange's parentElement() method cannot be trusted. getTextRangeContainerElement() works around that.
                var rangeContainerElement = getTextRangeContainerElement(this.textRange);

                if (textRangeIsCollapsed(this.textRange)) {
                    end = start = getTextRangeBoundaryPosition(this.textRange, rangeContainerElement, true,
                        true).boundaryPosition;
                } else {
                    startBoundary = getTextRangeBoundaryPosition(this.textRange, rangeContainerElement, true, false);
                    start = startBoundary.boundaryPosition;

                    // An optimization used here is that if the start and end boundaries have the same parent element, the
                    // search scope for the end boundary can be limited to exclude the portion of the element that precedes
                    // the start boundary
                    end = getTextRangeBoundaryPosition(this.textRange, rangeContainerElement, false, false,
                        startBoundary.nodeInfo).boundaryPosition;
                }

                this.setStart(start.node, start.offset);
                this.setEnd(end.node, end.offset);
            };

            WrappedTextRange.prototype.getName = function() {
                return "WrappedTextRange";
            };

            DomRange.copyComparisonConstants(WrappedTextRange);

            var rangeToTextRange = function(range) {
                if (range.collapsed) {
                    return createBoundaryTextRange(new DomPosition(range.startContainer, range.startOffset), true);
                } else {
                    var startRange = createBoundaryTextRange(new DomPosition(range.startContainer, range.startOffset), true);
                    var endRange = createBoundaryTextRange(new DomPosition(range.endContainer, range.endOffset), false);
                    var textRange = getBody( DomRange.getRangeDocument(range) ).createTextRange();
                    textRange.setEndPoint("StartToStart", startRange);
                    textRange.setEndPoint("EndToEnd", endRange);
                    return textRange;
                }
            };

            WrappedTextRange.rangeToTextRange = rangeToTextRange;

            WrappedTextRange.prototype.toTextRange = function() {
                return rangeToTextRange(this);
            };

            api.WrappedTextRange = WrappedTextRange;

            // IE 9 and above have both implementations and Rangy makes both available. The next few lines sets which
            // implementation to use by default.
            if (!api.features.implementsDomRange || api.config.preferTextRange) {
                // Add WrappedTextRange as the Range property of the global object to allow expression like Range.END_TO_END to work
                var globalObj = (function(f) { return f("return this;")(); })(Function);
                if (typeof globalObj.Range == "undefined") {
                    globalObj.Range = WrappedTextRange;
                }

                api.createNativeRange = function(doc) {
                    doc = getContentDocument(doc, module, "createNativeRange");
                    return getBody(doc).createTextRange();
                };

                api.WrappedRange = WrappedTextRange;
            }
        }

        api.createRange = function(doc) {
            doc = getContentDocument(doc, module, "createRange");
            return new api.WrappedRange(api.createNativeRange(doc));
        };

        api.createRangyRange = function(doc) {
            doc = getContentDocument(doc, module, "createRangyRange");
            return new DomRange(doc);
        };

        util.createAliasForDeprecatedMethod(api, "createIframeRange", "createRange");
        util.createAliasForDeprecatedMethod(api, "createIframeRangyRange", "createRangyRange");

        api.addShimListener(function(win) {
            var doc = win.document;
            if (typeof doc.createRange == "undefined") {
                doc.createRange = function() {
                    return api.createRange(doc);
                };
            }
            doc = win = null;
        });
    });

    /*----------------------------------------------------------------------------------------------------------------*/

    // This module creates a selection object wrapper that conforms as closely as possible to the Selection specification
    // in the HTML Editing spec (http://dvcs.w3.org/hg/editing/raw-file/tip/editing.html#selections)
    api.createCoreModule("WrappedSelection", ["DomRange", "WrappedRange"], function(api, module) {
        api.config.checkSelectionRanges = true;

        var BOOLEAN = "boolean";
        var NUMBER = "number";
        var dom = api.dom;
        var util = api.util;
        var isHostMethod = util.isHostMethod;
        var DomRange = api.DomRange;
        var WrappedRange = api.WrappedRange;
        var DOMException = api.DOMException;
        var DomPosition = dom.DomPosition;
        var getNativeSelection;
        var selectionIsCollapsed;
        var features = api.features;
        var CONTROL = "Control";
        var getDocument = dom.getDocument;
        var getBody = dom.getBody;
        var rangesEqual = DomRange.rangesEqual;


        // Utility function to support direction parameters in the API that may be a string ("backward", "backwards",
        // "forward" or "forwards") or a Boolean (true for backwards).
        function isDirectionBackward(dir) {
            return (typeof dir == "string") ? /^backward(s)?$/i.test(dir) : !!dir;
        }

        function getWindow(win, methodName) {
            if (!win) {
                return window;
            } else if (dom.isWindow(win)) {
                return win;
            } else if (win instanceof WrappedSelection) {
                return win.win;
            } else {
                var doc = dom.getContentDocument(win, module, methodName);
                return dom.getWindow(doc);
            }
        }

        function getWinSelection(winParam) {
            return getWindow(winParam, "getWinSelection").getSelection();
        }

        function getDocSelection(winParam) {
            return getWindow(winParam, "getDocSelection").document.selection;
        }

        function winSelectionIsBackward(sel) {
            var backward = false;
            if (sel.anchorNode) {
                backward = (dom.comparePoints(sel.anchorNode, sel.anchorOffset, sel.focusNode, sel.focusOffset) == 1);
            }
            return backward;
        }

        // Test for the Range/TextRange and Selection features required
        // Test for ability to retrieve selection
        var implementsWinGetSelection = isHostMethod(window, "getSelection"),
            implementsDocSelection = util.isHostObject(document, "selection");

        features.implementsWinGetSelection = implementsWinGetSelection;
        features.implementsDocSelection = implementsDocSelection;

        var useDocumentSelection = implementsDocSelection && (!implementsWinGetSelection || api.config.preferTextRange);

        if (useDocumentSelection) {
            getNativeSelection = getDocSelection;
            api.isSelectionValid = function(winParam) {
                var doc = getWindow(winParam, "isSelectionValid").document, nativeSel = doc.selection;

                // Check whether the selection TextRange is actually contained within the correct document
                return (nativeSel.type != "None" || getDocument(nativeSel.createRange().parentElement()) == doc);
            };
        } else if (implementsWinGetSelection) {
            getNativeSelection = getWinSelection;
            api.isSelectionValid = function() {
                return true;
            };
        } else {
            module.fail("Neither document.selection or window.getSelection() detected.");
            return false;
        }

        api.getNativeSelection = getNativeSelection;

        var testSelection = getNativeSelection();

        // In Firefox, the selection is null in an iframe with display: none. See issue #138.
        if (!testSelection) {
            module.fail("Native selection was null (possibly issue 138?)");
            return false;
        }

        var testRange = api.createNativeRange(document);
        var body = getBody(document);

        // Obtaining a range from a selection
        var selectionHasAnchorAndFocus = util.areHostProperties(testSelection,
            ["anchorNode", "focusNode", "anchorOffset", "focusOffset"]);

        features.selectionHasAnchorAndFocus = selectionHasAnchorAndFocus;

        // Test for existence of native selection extend() method
        var selectionHasExtend = isHostMethod(testSelection, "extend");
        features.selectionHasExtend = selectionHasExtend;

        // Test if rangeCount exists
        var selectionHasRangeCount = (typeof testSelection.rangeCount == NUMBER);
        features.selectionHasRangeCount = selectionHasRangeCount;

        var selectionSupportsMultipleRanges = false;
        var collapsedNonEditableSelectionsSupported = true;

        var addRangeBackwardToNative = selectionHasExtend ?
            function(nativeSelection, range) {
                var doc = DomRange.getRangeDocument(range);
                var endRange = api.createRange(doc);
                endRange.collapseToPoint(range.endContainer, range.endOffset);
                nativeSelection.addRange(getNativeRange(endRange));
                nativeSelection.extend(range.startContainer, range.startOffset);
            } : null;

        if (util.areHostMethods(testSelection, ["addRange", "getRangeAt", "removeAllRanges"]) &&
                typeof testSelection.rangeCount == NUMBER && features.implementsDomRange) {

            (function() {
                // Previously an iframe was used but this caused problems in some circumstances in IE, so tests are
                // performed on the current document's selection. See issue 109.

                // Note also that if a selection previously existed, it is wiped and later restored by these tests. This
                // will result in the selection direction begin reversed if the original selection was backwards and the
                // browser does not support setting backwards selections (Internet Explorer, I'm looking at you).
                var sel = window.getSelection();
                if (sel) {
                    // Store the current selection
                    var originalSelectionRangeCount = sel.rangeCount;
                    var selectionHasMultipleRanges = (originalSelectionRangeCount > 1);
                    var originalSelectionRanges = [];
                    var originalSelectionBackward = winSelectionIsBackward(sel);
                    for (var i = 0; i < originalSelectionRangeCount; ++i) {
                        originalSelectionRanges[i] = sel.getRangeAt(i);
                    }

                    // Create some test elements
                    var testEl = dom.createTestElement(document, "", false);
                    var textNode = testEl.appendChild( document.createTextNode("\u00a0\u00a0\u00a0") );

                    // Test whether the native selection will allow a collapsed selection within a non-editable element
                    var r1 = document.createRange();

                    r1.setStart(textNode, 1);
                    r1.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(r1);
                    collapsedNonEditableSelectionsSupported = (sel.rangeCount == 1);
                    sel.removeAllRanges();

                    // Test whether the native selection is capable of supporting multiple ranges.
                    if (!selectionHasMultipleRanges) {
                        // Doing the original feature test here in Chrome 36 (and presumably later versions) prints a
                        // console error of "Discontiguous selection is not supported." that cannot be suppressed. There's
                        // nothing we can do about this while retaining the feature test so we have to resort to a browser
                        // sniff. I'm not happy about it. See
                        // https://code.google.com/p/chromium/issues/detail?id=399791
                        var chromeMatch = window.navigator.appVersion.match(/Chrome\/(.*?) /);
                        if (chromeMatch && parseInt(chromeMatch[1]) >= 36) {
                            selectionSupportsMultipleRanges = false;
                        } else {
                            var r2 = r1.cloneRange();
                            r1.setStart(textNode, 0);
                            r2.setEnd(textNode, 3);
                            r2.setStart(textNode, 2);
                            sel.addRange(r1);
                            sel.addRange(r2);
                            selectionSupportsMultipleRanges = (sel.rangeCount == 2);
                        }
                    }

                    // Clean up
                    dom.removeNode(testEl);
                    sel.removeAllRanges();

                    for (i = 0; i < originalSelectionRangeCount; ++i) {
                        if (i == 0 && originalSelectionBackward) {
                            if (addRangeBackwardToNative) {
                                addRangeBackwardToNative(sel, originalSelectionRanges[i]);
                            } else {
                                api.warn("Rangy initialization: original selection was backwards but selection has been restored forwards because the browser does not support Selection.extend");
                                sel.addRange(originalSelectionRanges[i]);
                            }
                        } else {
                            sel.addRange(originalSelectionRanges[i]);
                        }
                    }
                }
            })();
        }

        features.selectionSupportsMultipleRanges = selectionSupportsMultipleRanges;
        features.collapsedNonEditableSelectionsSupported = collapsedNonEditableSelectionsSupported;

        // ControlRanges
        var implementsControlRange = false, testControlRange;

        if (body && isHostMethod(body, "createControlRange")) {
            testControlRange = body.createControlRange();
            if (util.areHostProperties(testControlRange, ["item", "add"])) {
                implementsControlRange = true;
            }
        }
        features.implementsControlRange = implementsControlRange;

        // Selection collapsedness
        if (selectionHasAnchorAndFocus) {
            selectionIsCollapsed = function(sel) {
                return sel.anchorNode === sel.focusNode && sel.anchorOffset === sel.focusOffset;
            };
        } else {
            selectionIsCollapsed = function(sel) {
                return sel.rangeCount ? sel.getRangeAt(sel.rangeCount - 1).collapsed : false;
            };
        }

        function updateAnchorAndFocusFromRange(sel, range, backward) {
            var anchorPrefix = backward ? "end" : "start", focusPrefix = backward ? "start" : "end";
            sel.anchorNode = range[anchorPrefix + "Container"];
            sel.anchorOffset = range[anchorPrefix + "Offset"];
            sel.focusNode = range[focusPrefix + "Container"];
            sel.focusOffset = range[focusPrefix + "Offset"];
        }

        function updateAnchorAndFocusFromNativeSelection(sel) {
            var nativeSel = sel.nativeSelection;
            sel.anchorNode = nativeSel.anchorNode;
            sel.anchorOffset = nativeSel.anchorOffset;
            sel.focusNode = nativeSel.focusNode;
            sel.focusOffset = nativeSel.focusOffset;
        }

        function updateEmptySelection(sel) {
            sel.anchorNode = sel.focusNode = null;
            sel.anchorOffset = sel.focusOffset = 0;
            sel.rangeCount = 0;
            sel.isCollapsed = true;
            sel._ranges.length = 0;
        }

        function getNativeRange(range) {
            var nativeRange;
            if (range instanceof DomRange) {
                nativeRange = api.createNativeRange(range.getDocument());
                nativeRange.setEnd(range.endContainer, range.endOffset);
                nativeRange.setStart(range.startContainer, range.startOffset);
            } else if (range instanceof WrappedRange) {
                nativeRange = range.nativeRange;
            } else if (features.implementsDomRange && (range instanceof dom.getWindow(range.startContainer).Range)) {
                nativeRange = range;
            }
            return nativeRange;
        }

        function rangeContainsSingleElement(rangeNodes) {
            if (!rangeNodes.length || rangeNodes[0].nodeType != 1) {
                return false;
            }
            for (var i = 1, len = rangeNodes.length; i < len; ++i) {
                if (!dom.isAncestorOf(rangeNodes[0], rangeNodes[i])) {
                    return false;
                }
            }
            return true;
        }

        function getSingleElementFromRange(range) {
            var nodes = range.getNodes();
            if (!rangeContainsSingleElement(nodes)) {
                throw module.createError("getSingleElementFromRange: range " + range.inspect() + " did not consist of a single element");
            }
            return nodes[0];
        }

        // Simple, quick test which only needs to distinguish between a TextRange and a ControlRange
        function isTextRange(range) {
            return !!range && typeof range.text != "undefined";
        }

        function updateFromTextRange(sel, range) {
            // Create a Range from the selected TextRange
            var wrappedRange = new WrappedRange(range);
            sel._ranges = [wrappedRange];

            updateAnchorAndFocusFromRange(sel, wrappedRange, false);
            sel.rangeCount = 1;
            sel.isCollapsed = wrappedRange.collapsed;
        }

        function updateControlSelection(sel) {
            // Update the wrapped selection based on what's now in the native selection
            sel._ranges.length = 0;
            if (sel.docSelection.type == "None") {
                updateEmptySelection(sel);
            } else {
                var controlRange = sel.docSelection.createRange();
                if (isTextRange(controlRange)) {
                    // This case (where the selection type is "Control" and calling createRange() on the selection returns
                    // a TextRange) can happen in IE 9. It happens, for example, when all elements in the selected
                    // ControlRange have been removed from the ControlRange and removed from the document.
                    updateFromTextRange(sel, controlRange);
                } else {
                    sel.rangeCount = controlRange.length;
                    var range, doc = getDocument(controlRange.item(0));
                    for (var i = 0; i < sel.rangeCount; ++i) {
                        range = api.createRange(doc);
                        range.selectNode(controlRange.item(i));
                        sel._ranges.push(range);
                    }
                    sel.isCollapsed = sel.rangeCount == 1 && sel._ranges[0].collapsed;
                    updateAnchorAndFocusFromRange(sel, sel._ranges[sel.rangeCount - 1], false);
                }
            }
        }

        function addRangeToControlSelection(sel, range) {
            var controlRange = sel.docSelection.createRange();
            var rangeElement = getSingleElementFromRange(range);

            // Create a new ControlRange containing all the elements in the selected ControlRange plus the element
            // contained by the supplied range
            var doc = getDocument(controlRange.item(0));
            var newControlRange = getBody(doc).createControlRange();
            for (var i = 0, len = controlRange.length; i < len; ++i) {
                newControlRange.add(controlRange.item(i));
            }
            try {
                newControlRange.add(rangeElement);
            } catch (ex) {
                throw module.createError("addRange(): Element within the specified Range could not be added to control selection (does it have layout?)");
            }
            newControlRange.select();

            // Update the wrapped selection based on what's now in the native selection
            updateControlSelection(sel);
        }

        var getSelectionRangeAt;

        if (isHostMethod(testSelection, "getRangeAt")) {
            // try/catch is present because getRangeAt() must have thrown an error in some browser and some situation.
            // Unfortunately, I didn't write a comment about the specifics and am now scared to take it out. Let that be a
            // lesson to us all, especially me.
            getSelectionRangeAt = function(sel, index) {
                try {
                    return sel.getRangeAt(index);
                } catch (ex) {
                    return null;
                }
            };
        } else if (selectionHasAnchorAndFocus) {
            getSelectionRangeAt = function(sel) {
                var doc = getDocument(sel.anchorNode);
                var range = api.createRange(doc);
                range.setStartAndEnd(sel.anchorNode, sel.anchorOffset, sel.focusNode, sel.focusOffset);

                // Handle the case when the selection was selected backwards (from the end to the start in the
                // document)
                if (range.collapsed !== this.isCollapsed) {
                    range.setStartAndEnd(sel.focusNode, sel.focusOffset, sel.anchorNode, sel.anchorOffset);
                }

                return range;
            };
        }

        function WrappedSelection(selection, docSelection, win) {
            this.nativeSelection = selection;
            this.docSelection = docSelection;
            this._ranges = [];
            this.win = win;
            this.refresh();
        }

        WrappedSelection.prototype = api.selectionPrototype;

        function deleteProperties(sel) {
            sel.win = sel.anchorNode = sel.focusNode = sel._ranges = null;
            sel.rangeCount = sel.anchorOffset = sel.focusOffset = 0;
            sel.detached = true;
        }

        var cachedRangySelections = [];

        function actOnCachedSelection(win, action) {
            var i = cachedRangySelections.length, cached, sel;
            while (i--) {
                cached = cachedRangySelections[i];
                sel = cached.selection;
                if (action == "deleteAll") {
                    deleteProperties(sel);
                } else if (cached.win == win) {
                    if (action == "delete") {
                        cachedRangySelections.splice(i, 1);
                        return true;
                    } else {
                        return sel;
                    }
                }
            }
            if (action == "deleteAll") {
                cachedRangySelections.length = 0;
            }
            return null;
        }

        var getSelection = function(win) {
            // Check if the parameter is a Rangy Selection object
            if (win && win instanceof WrappedSelection) {
                win.refresh();
                return win;
            }

            win = getWindow(win, "getNativeSelection");

            var sel = actOnCachedSelection(win);
            var nativeSel = getNativeSelection(win), docSel = implementsDocSelection ? getDocSelection(win) : null;
            if (sel) {
                sel.nativeSelection = nativeSel;
                sel.docSelection = docSel;
                sel.refresh();
            } else {
                sel = new WrappedSelection(nativeSel, docSel, win);
                cachedRangySelections.push( { win: win, selection: sel } );
            }
            return sel;
        };

        api.getSelection = getSelection;

        util.createAliasForDeprecatedMethod(api, "getIframeSelection", "getSelection");

        var selProto = WrappedSelection.prototype;

        function createControlSelection(sel, ranges) {
            // Ensure that the selection becomes of type "Control"
            var doc = getDocument(ranges[0].startContainer);
            var controlRange = getBody(doc).createControlRange();
            for (var i = 0, el, len = ranges.length; i < len; ++i) {
                el = getSingleElementFromRange(ranges[i]);
                try {
                    controlRange.add(el);
                } catch (ex) {
                    throw module.createError("setRanges(): Element within one of the specified Ranges could not be added to control selection (does it have layout?)");
                }
            }
            controlRange.select();

            // Update the wrapped selection based on what's now in the native selection
            updateControlSelection(sel);
        }

        // Selecting a range
        if (!useDocumentSelection && selectionHasAnchorAndFocus && util.areHostMethods(testSelection, ["removeAllRanges", "addRange"])) {
            selProto.removeAllRanges = function() {
                this.nativeSelection.removeAllRanges();
                updateEmptySelection(this);
            };

            var addRangeBackward = function(sel, range) {
                addRangeBackwardToNative(sel.nativeSelection, range);
                sel.refresh();
            };

            if (selectionHasRangeCount) {
                selProto.addRange = function(range, direction) {
                    if (implementsControlRange && implementsDocSelection && this.docSelection.type == CONTROL) {
                        addRangeToControlSelection(this, range);
                    } else {
                        if (isDirectionBackward(direction) && selectionHasExtend) {
                            addRangeBackward(this, range);
                        } else {
                            var previousRangeCount;
                            if (selectionSupportsMultipleRanges) {
                                previousRangeCount = this.rangeCount;
                            } else {
                                this.removeAllRanges();
                                previousRangeCount = 0;
                            }
                            // Clone the native range so that changing the selected range does not affect the selection.
                            // This is contrary to the spec but is the only way to achieve consistency between browsers. See
                            // issue 80.
                            var clonedNativeRange = getNativeRange(range).cloneRange();
                            try {
                                this.nativeSelection.addRange(clonedNativeRange);
                            } catch (ex) {
                            }

                            // Check whether adding the range was successful
                            this.rangeCount = this.nativeSelection.rangeCount;

                            if (this.rangeCount == previousRangeCount + 1) {
                                // The range was added successfully

                                // Check whether the range that we added to the selection is reflected in the last range extracted from
                                // the selection
                                if (api.config.checkSelectionRanges) {
                                    var nativeRange = getSelectionRangeAt(this.nativeSelection, this.rangeCount - 1);
                                    if (nativeRange && !rangesEqual(nativeRange, range)) {
                                        // Happens in WebKit with, for example, a selection placed at the start of a text node
                                        range = new WrappedRange(nativeRange);
                                    }
                                }
                                this._ranges[this.rangeCount - 1] = range;
                                updateAnchorAndFocusFromRange(this, range, selectionIsBackward(this.nativeSelection));
                                this.isCollapsed = selectionIsCollapsed(this);
                            } else {
                                // The range was not added successfully. The simplest thing is to refresh
                                this.refresh();
                            }
                        }
                    }
                };
            } else {
                selProto.addRange = function(range, direction) {
                    if (isDirectionBackward(direction) && selectionHasExtend) {
                        addRangeBackward(this, range);
                    } else {
                        this.nativeSelection.addRange(getNativeRange(range));
                        this.refresh();
                    }
                };
            }

            selProto.setRanges = function(ranges) {
                if (implementsControlRange && implementsDocSelection && ranges.length > 1) {
                    createControlSelection(this, ranges);
                } else {
                    this.removeAllRanges();
                    for (var i = 0, len = ranges.length; i < len; ++i) {
                        this.addRange(ranges[i]);
                    }
                }
            };
        } else if (isHostMethod(testSelection, "empty") && isHostMethod(testRange, "select") &&
                   implementsControlRange && useDocumentSelection) {

            selProto.removeAllRanges = function() {
                // Added try/catch as fix for issue #21
                try {
                    this.docSelection.empty();

                    // Check for empty() not working (issue #24)
                    if (this.docSelection.type != "None") {
                        // Work around failure to empty a control selection by instead selecting a TextRange and then
                        // calling empty()
                        var doc;
                        if (this.anchorNode) {
                            doc = getDocument(this.anchorNode);
                        } else if (this.docSelection.type == CONTROL) {
                            var controlRange = this.docSelection.createRange();
                            if (controlRange.length) {
                                doc = getDocument( controlRange.item(0) );
                            }
                        }
                        if (doc) {
                            var textRange = getBody(doc).createTextRange();
                            textRange.select();
                            this.docSelection.empty();
                        }
                    }
                } catch(ex) {}
                updateEmptySelection(this);
            };

            selProto.addRange = function(range) {
                if (this.docSelection.type == CONTROL) {
                    addRangeToControlSelection(this, range);
                } else {
                    api.WrappedTextRange.rangeToTextRange(range).select();
                    this._ranges[0] = range;
                    this.rangeCount = 1;
                    this.isCollapsed = this._ranges[0].collapsed;
                    updateAnchorAndFocusFromRange(this, range, false);
                }
            };

            selProto.setRanges = function(ranges) {
                this.removeAllRanges();
                var rangeCount = ranges.length;
                if (rangeCount > 1) {
                    createControlSelection(this, ranges);
                } else if (rangeCount) {
                    this.addRange(ranges[0]);
                }
            };
        } else {
            module.fail("No means of selecting a Range or TextRange was found");
            return false;
        }

        selProto.getRangeAt = function(index) {
            if (index < 0 || index >= this.rangeCount) {
                throw new DOMException("INDEX_SIZE_ERR");
            } else {
                // Clone the range to preserve selection-range independence. See issue 80.
                return this._ranges[index].cloneRange();
            }
        };

        var refreshSelection;

        if (useDocumentSelection) {
            refreshSelection = function(sel) {
                var range;
                if (api.isSelectionValid(sel.win)) {
                    range = sel.docSelection.createRange();
                } else {
                    range = getBody(sel.win.document).createTextRange();
                    range.collapse(true);
                }

                if (sel.docSelection.type == CONTROL) {
                    updateControlSelection(sel);
                } else if (isTextRange(range)) {
                    updateFromTextRange(sel, range);
                } else {
                    updateEmptySelection(sel);
                }
            };
        } else if (isHostMethod(testSelection, "getRangeAt") && typeof testSelection.rangeCount == NUMBER) {
            refreshSelection = function(sel) {
                if (implementsControlRange && implementsDocSelection && sel.docSelection.type == CONTROL) {
                    updateControlSelection(sel);
                } else {
                    sel._ranges.length = sel.rangeCount = sel.nativeSelection.rangeCount;
                    if (sel.rangeCount) {
                        for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                            sel._ranges[i] = new api.WrappedRange(sel.nativeSelection.getRangeAt(i));
                        }
                        updateAnchorAndFocusFromRange(sel, sel._ranges[sel.rangeCount - 1], selectionIsBackward(sel.nativeSelection));
                        sel.isCollapsed = selectionIsCollapsed(sel);
                    } else {
                        updateEmptySelection(sel);
                    }
                }
            };
        } else if (selectionHasAnchorAndFocus && typeof testSelection.isCollapsed == BOOLEAN && typeof testRange.collapsed == BOOLEAN && features.implementsDomRange) {
            refreshSelection = function(sel) {
                var range, nativeSel = sel.nativeSelection;
                if (nativeSel.anchorNode) {
                    range = getSelectionRangeAt(nativeSel, 0);
                    sel._ranges = [range];
                    sel.rangeCount = 1;
                    updateAnchorAndFocusFromNativeSelection(sel);
                    sel.isCollapsed = selectionIsCollapsed(sel);
                } else {
                    updateEmptySelection(sel);
                }
            };
        } else {
            module.fail("No means of obtaining a Range or TextRange from the user's selection was found");
            return false;
        }

        selProto.refresh = function(checkForChanges) {
            var oldRanges = checkForChanges ? this._ranges.slice(0) : null;
            var oldAnchorNode = this.anchorNode, oldAnchorOffset = this.anchorOffset;

            refreshSelection(this);
            if (checkForChanges) {
                // Check the range count first
                var i = oldRanges.length;
                if (i != this._ranges.length) {
                    return true;
                }

                // Now check the direction. Checking the anchor position is the same is enough since we're checking all the
                // ranges after this
                if (this.anchorNode != oldAnchorNode || this.anchorOffset != oldAnchorOffset) {
                    return true;
                }

                // Finally, compare each range in turn
                while (i--) {
                    if (!rangesEqual(oldRanges[i], this._ranges[i])) {
                        return true;
                    }
                }
                return false;
            }
        };

        // Removal of a single range
        var removeRangeManually = function(sel, range) {
            var ranges = sel.getAllRanges();
            sel.removeAllRanges();
            for (var i = 0, len = ranges.length; i < len; ++i) {
                if (!rangesEqual(range, ranges[i])) {
                    sel.addRange(ranges[i]);
                }
            }
            if (!sel.rangeCount) {
                updateEmptySelection(sel);
            }
        };

        if (implementsControlRange && implementsDocSelection) {
            selProto.removeRange = function(range) {
                if (this.docSelection.type == CONTROL) {
                    var controlRange = this.docSelection.createRange();
                    var rangeElement = getSingleElementFromRange(range);

                    // Create a new ControlRange containing all the elements in the selected ControlRange minus the
                    // element contained by the supplied range
                    var doc = getDocument(controlRange.item(0));
                    var newControlRange = getBody(doc).createControlRange();
                    var el, removed = false;
                    for (var i = 0, len = controlRange.length; i < len; ++i) {
                        el = controlRange.item(i);
                        if (el !== rangeElement || removed) {
                            newControlRange.add(controlRange.item(i));
                        } else {
                            removed = true;
                        }
                    }
                    newControlRange.select();

                    // Update the wrapped selection based on what's now in the native selection
                    updateControlSelection(this);
                } else {
                    removeRangeManually(this, range);
                }
            };
        } else {
            selProto.removeRange = function(range) {
                removeRangeManually(this, range);
            };
        }

        // Detecting if a selection is backward
        var selectionIsBackward;
        if (!useDocumentSelection && selectionHasAnchorAndFocus && features.implementsDomRange) {
            selectionIsBackward = winSelectionIsBackward;

            selProto.isBackward = function() {
                return selectionIsBackward(this);
            };
        } else {
            selectionIsBackward = selProto.isBackward = function() {
                return false;
            };
        }

        // Create an alias for backwards compatibility. From 1.3, everything is "backward" rather than "backwards"
        selProto.isBackwards = selProto.isBackward;

        // Selection stringifier
        // This is conformant to the old HTML5 selections draft spec but differs from WebKit and Mozilla's implementation.
        // The current spec does not yet define this method.
        selProto.toString = function() {
            var rangeTexts = [];
            for (var i = 0, len = this.rangeCount; i < len; ++i) {
                rangeTexts[i] = "" + this._ranges[i];
            }
            return rangeTexts.join("");
        };

        function assertNodeInSameDocument(sel, node) {
            if (sel.win.document != getDocument(node)) {
                throw new DOMException("WRONG_DOCUMENT_ERR");
            }
        }

        // No current browser conforms fully to the spec for this method, so Rangy's own method is always used
        selProto.collapse = function(node, offset) {
            assertNodeInSameDocument(this, node);
            var range = api.createRange(node);
            range.collapseToPoint(node, offset);
            this.setSingleRange(range);
            this.isCollapsed = true;
        };

        selProto.collapseToStart = function() {
            if (this.rangeCount) {
                var range = this._ranges[0];
                this.collapse(range.startContainer, range.startOffset);
            } else {
                throw new DOMException("INVALID_STATE_ERR");
            }
        };

        selProto.collapseToEnd = function() {
            if (this.rangeCount) {
                var range = this._ranges[this.rangeCount - 1];
                this.collapse(range.endContainer, range.endOffset);
            } else {
                throw new DOMException("INVALID_STATE_ERR");
            }
        };

        // The spec is very specific on how selectAllChildren should be implemented and not all browsers implement it as
        // specified so the native implementation is never used by Rangy.
        selProto.selectAllChildren = function(node) {
            assertNodeInSameDocument(this, node);
            var range = api.createRange(node);
            range.selectNodeContents(node);
            this.setSingleRange(range);
        };

        selProto.deleteFromDocument = function() {
            // Sepcial behaviour required for IE's control selections
            if (implementsControlRange && implementsDocSelection && this.docSelection.type == CONTROL) {
                var controlRange = this.docSelection.createRange();
                var element;
                while (controlRange.length) {
                    element = controlRange.item(0);
                    controlRange.remove(element);
                    dom.removeNode(element);
                }
                this.refresh();
            } else if (this.rangeCount) {
                var ranges = this.getAllRanges();
                if (ranges.length) {
                    this.removeAllRanges();
                    for (var i = 0, len = ranges.length; i < len; ++i) {
                        ranges[i].deleteContents();
                    }
                    // The spec says nothing about what the selection should contain after calling deleteContents on each
                    // range. Firefox moves the selection to where the final selected range was, so we emulate that
                    this.addRange(ranges[len - 1]);
                }
            }
        };

        // The following are non-standard extensions
        selProto.eachRange = function(func, returnValue) {
            for (var i = 0, len = this._ranges.length; i < len; ++i) {
                if ( func( this.getRangeAt(i) ) ) {
                    return returnValue;
                }
            }
        };

        selProto.getAllRanges = function() {
            var ranges = [];
            this.eachRange(function(range) {
                ranges.push(range);
            });
            return ranges;
        };

        selProto.setSingleRange = function(range, direction) {
            this.removeAllRanges();
            this.addRange(range, direction);
        };

        selProto.callMethodOnEachRange = function(methodName, params) {
            var results = [];
            this.eachRange( function(range) {
                results.push( range[methodName].apply(range, params || []) );
            } );
            return results;
        };

        function createStartOrEndSetter(isStart) {
            return function(node, offset) {
                var range;
                if (this.rangeCount) {
                    range = this.getRangeAt(0);
                    range["set" + (isStart ? "Start" : "End")](node, offset);
                } else {
                    range = api.createRange(this.win.document);
                    range.setStartAndEnd(node, offset);
                }
                this.setSingleRange(range, this.isBackward());
            };
        }

        selProto.setStart = createStartOrEndSetter(true);
        selProto.setEnd = createStartOrEndSetter(false);

        // Add select() method to Range prototype. Any existing selection will be removed.
        api.rangePrototype.select = function(direction) {
            getSelection( this.getDocument() ).setSingleRange(this, direction);
        };

        selProto.changeEachRange = function(func) {
            var ranges = [];
            var backward = this.isBackward();

            this.eachRange(function(range) {
                func(range);
                ranges.push(range);
            });

            this.removeAllRanges();
            if (backward && ranges.length == 1) {
                this.addRange(ranges[0], "backward");
            } else {
                this.setRanges(ranges);
            }
        };

        selProto.containsNode = function(node, allowPartial) {
            return this.eachRange( function(range) {
                return range.containsNode(node, allowPartial);
            }, true ) || false;
        };

        selProto.getBookmark = function(containerNode) {
            return {
                backward: this.isBackward(),
                rangeBookmarks: this.callMethodOnEachRange("getBookmark", [containerNode])
            };
        };

        selProto.moveToBookmark = function(bookmark) {
            var selRanges = [];
            for (var i = 0, rangeBookmark, range; rangeBookmark = bookmark.rangeBookmarks[i++]; ) {
                range = api.createRange(this.win);
                range.moveToBookmark(rangeBookmark);
                selRanges.push(range);
            }
            if (bookmark.backward) {
                this.setSingleRange(selRanges[0], "backward");
            } else {
                this.setRanges(selRanges);
            }
        };

        selProto.saveRanges = function() {
            return {
                backward: this.isBackward(),
                ranges: this.callMethodOnEachRange("cloneRange")
            };
        };

        selProto.restoreRanges = function(selRanges) {
            this.removeAllRanges();
            for (var i = 0, range; range = selRanges.ranges[i]; ++i) {
                this.addRange(range, (selRanges.backward && i == 0));
            }
        };

        selProto.toHtml = function() {
            var rangeHtmls = [];
            this.eachRange(function(range) {
                rangeHtmls.push( DomRange.toHtml(range) );
            });
            return rangeHtmls.join("");
        };

        if (features.implementsTextRange) {
            selProto.getNativeTextRange = function() {
                var sel, textRange;
                if ( (sel = this.docSelection) ) {
                    var range = sel.createRange();
                    if (isTextRange(range)) {
                        return range;
                    } else {
                        throw module.createError("getNativeTextRange: selection is a control selection");
                    }
                } else if (this.rangeCount > 0) {
                    return api.WrappedTextRange.rangeToTextRange( this.getRangeAt(0) );
                } else {
                    throw module.createError("getNativeTextRange: selection contains no range");
                }
            };
        }

        function inspect(sel) {
            var rangeInspects = [];
            var anchor = new DomPosition(sel.anchorNode, sel.anchorOffset);
            var focus = new DomPosition(sel.focusNode, sel.focusOffset);
            var name = (typeof sel.getName == "function") ? sel.getName() : "Selection";

            if (typeof sel.rangeCount != "undefined") {
                for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                    rangeInspects[i] = DomRange.inspect(sel.getRangeAt(i));
                }
            }
            return "[" + name + "(Ranges: " + rangeInspects.join(", ") +
                    ")(anchor: " + anchor.inspect() + ", focus: " + focus.inspect() + "]";
        }

        selProto.getName = function() {
            return "WrappedSelection";
        };

        selProto.inspect = function() {
            return inspect(this);
        };

        selProto.detach = function() {
            actOnCachedSelection(this.win, "delete");
            deleteProperties(this);
        };

        WrappedSelection.detachAll = function() {
            actOnCachedSelection(null, "deleteAll");
        };

        WrappedSelection.inspect = inspect;
        WrappedSelection.isDirectionBackward = isDirectionBackward;

        api.Selection = WrappedSelection;

        api.selectionPrototype = selProto;

        api.addShimListener(function(win) {
            if (typeof win.getSelection == "undefined") {
                win.getSelection = function() {
                    return getSelection(win);
                };
            }
            win = null;
        });
    });
    

    /*----------------------------------------------------------------------------------------------------------------*/

    // Wait for document to load before initializing
    var docReady = false;

    var loadHandler = function(e) {
        if (!docReady) {
            docReady = true;
            if (!api.initialized && api.config.autoInitialize) {
                init();
            }
        }
    };

    if (isBrowser) {
        // Test whether the document has already been loaded and initialize immediately if so
        if (document.readyState == "complete") {
            loadHandler();
        } else {
            if (isHostMethod(document, "addEventListener")) {
                document.addEventListener("DOMContentLoaded", loadHandler, false);
            }

            // Add a fallback in case the DOMContentLoaded event isn't supported
            addListener(window, "load", loadHandler);
        }
    }

    return api;
}, this);(function (ice, $) {
	/**
	 * TODO
	 * 1. Each time an ice node is removed, refresh change set
	 */

	"use strict";

	var win = window || {},
		rangy = ice.rangy || win.rangy,
		defaults, InlineChangeEditor;
	
	ice.rangy = rangy;
	
	/* constants */
	var BREAK_ELEMENT = "br",
		PARAGRAPH_ELEMENT = "p",
		INSERT_TYPE = "insertType",
		DELETE_TYPE = "deleteType",
		ignoreKeyCodes = [
			{start: 0, end: 31}, // everything below space, special cases handled separately
			{start: 33, end: 40}, // nav keys
			{start: 45, end: 45}, // insert
			{start: 91, end: 93}, // windows keys
			{start: 112, end: 123}, // function keys
			{start: 144, end: 145}
		];

	defaults = {
	// ice node attribute names:
		attributes: {
			changeId: "data-cid",
			userId: "data-userid",
			userName: "data-username",
			sessionId: "data-session-id",
			time: "data-time",
			lastTime: "data-last-change-time",
			changeData: "data-changedata" // arbitrary data to associate with the node, e.g. version
		},
		// Prepended to `changeType.alias` for classname uniqueness, if needed
		attrValuePrefix: '',
		
		// Block element tagname, which wrap text and other inline nodes in `this.element`
		blockEl: 'p',
		
		// All permitted block element tagnames
		blockEls: ['div','p', 'ol', 'ul', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote'],
		
		// Unique style prefix, prepended to a digit, incremented for each encountered user, and stored
		// in ice node class attributes - cts1, cts2, cts3, ...
		stylePrefix: 'cts',
		currentUser: {
			id: null,
			name: null
		},
	
		// Default change types are insert and delete. Plugins or outside apps should extend this
		// if they want to manage new change types. The changeType name is used as a primary
		// reference for ice nodes; the `alias`, is dropped in the class attribute and is the
		// primary method of identifying ice nodes; and `tag` is used for construction only.
		// Invoking `this.getCleanContent()` will remove all delete type nodes and remove the tags
		// for the other types, leaving the html content in place.
		changeTypes: {
			insertType: {
				tag: 'ins',
				alias: 'ins',
				action: 'Inserted'
			},
			deleteType: {
				tag: 'del',
				alias: 'del',
				action: 'Deleted'
			}
		},

		// Sets this.element with the contentEditable element
		contentEditable: undefined,//dfl, start with a neutral value
	
		// Switch for toggling track changes on/off - when `false` events will be ignored.
		_isTracking: true,
	
		tooltips: false,
		
		tooltipsDelay: 1,
	
		_isVisible : true, // state of change tracking visibility
		
		_changeData : null, // a string you can associate with the current change set, e.g. version
		
		_handleSelectAll: false, // if true, handle ctrl/cmd-A in the change tracker
		
		_sessionId: null
	};
	
	function isIgnoredKeyCode(key) {
		if (! key) {
			return true;
		}
		var i, len = ignoreKeyCodes.length, rec;
		
		for (i = 0; i < len; ++i) {
			rec = ignoreKeyCodes[i];
			if (key >= rec.start && key <= rec.end) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @class ice.InlineChangeEditor
	 * The change tracking engine
	 * interacts with a <code>contenteditable</code> DOM element
	 */
	InlineChangeEditor = function (options) {

		// Data structure for modelling changes in the element according to the following model:
		//	[changeid] => {`type`, `time`, `userid`, `username`}
		options || (options = {});
		if (!options.element) {
			throw new Error("options.element must be defined for ice construction.");
		}
	
		this._changes = {};
		// Tracks all of the styles for users according to the following model:
		//	[userId] => styleId; where style is "this.stylePrefix" + "this.uniqueStyleIndex"
		this._userStyles = {};
		this.currentUser = {name: '', id: ''};
		this._styles = {}; // dfl, moved from prototype
		this._savedNodesMap = {};
		this.$this = $(this);
		this._browser = ice.dom.browser();
		this._tooltipMouseOver = this._tooltipMouseOver.bind(this);
		this._tooltipMouseOut = this._tooltipMouseOut.bind(this);
		
		$.extend(true, this, defaults, options);
		if (options.tooltips && (! $.isFunction(options.hostMethods.showTooltip) || ! $.isFunction(options.hostMethods.hideTooltip))) {
			throw new Error("hostMethods.showTooltip and hostMethods.hideTooltip must be defined if tooltips is true");
		}
		var us = options.userStyles || {}; // dfl, moved from prototype, allow preconfig
		for (var id in us) {
			if (us.hasOwnProperty(id)) {
				var st = us[id];
				if (! isNaN(st)) {
					this._userStyles[id] = this.stylePrefix + '-' + st;
					this._uniqueStyleIndex = Math.max(st, this._uniqueStyleIndex);
					this._styles[st] = true;
				}
			}
		}
		logError = options.hostMethods.logError || function(){ return undefined; };
		// cache css selectors
		this._insertSelector = '.' + this._getIceNodeClass(INSERT_TYPE);
		this._deleteSelector = '.' + this._getIceNodeClass(DELETE_TYPE);
		this._iceSelector = this._insertSelector + ',' + this._deleteSelector;
		
/*		this._domObserver = new window.MutationObserver(this._onDomMutation.bind(this));
		this._domObserverConfig = {
//			attributes: true,
			childList: true,
			characterData: false,
			subtree: true
		};
		this._domObserverTimeout = null; */
	};

	InlineChangeEditor.prototype = {
	
		// Incremented for each new user and appended to they style prefix, and dropped in the
		// ice node class attribute.
		_uniqueStyleIndex: 0,
	
		_browserType: null,
	
		// One change may create multiple ice nodes, so this keeps track of the current batch id.
		_batchChangeId: null,
	
		// Incremented for each new change, dropped in the changeIdAttribute.
		_uniqueIDIndex: 1,
	
		// Temporary bookmark tags for deletes, when delete placeholding is active.
		_delBookmark: 'tempdel',
		isPlaceHoldingDeletes: false,
	
		/**
		 * Turns on change tracking - sets up events, if needed, and initializes the environment,
		 * range, and editor.
		 */
		startTracking: function (options) {
			// dfl:set contenteditable only if it has been explicitly set
			if (typeof(this.contentEditable) == "boolean") {
				this.element.setAttribute('contentEditable', this.contentEditable);
			}
		
			
			this.initializeEnvironment();
			this.initializeEditor();
			this.initializeRange();
			this._updateTooltipsState(); //dfl
			
			return this;
		},
	
		/**
		 * Removes contenteditability and stops event handling.
		 * @param {Boolean} onlyICE if true, stop tracking but don't remove the contenteditable property of the tracked element
		 */
		stopTracking: function (onlyICE) {
	
			this._isTracking = false;
			try { // dfl added try/catch for ie
				// If we are handling events setup the delegate to handle various events on `this.element`.
				var e = this.element;
				if (e) {
					this.unlistenToEvents();
				}
		
				// dfl:reset contenteditable unless requested not to do so
				if (! onlyICE && (typeof(this.contentEditable) !== "undefined")) {
					this.element.setAttribute('contentEditable', !this.contentEditable);
				}
			}
			catch (e){
				logError(e, "While trying to stop tracking");
			}

			this._updateTooltipsState();
			return this;
		},
		
		listenToEvents: function() {
			if (this.element && ! this._boundEventHandler) {
				this.unlistenToEvents();
				this._boundEventHandler = this.handleEvent.bind(this);
				this.element.addEventListener("keydown", this._boundEventHandler, true);
			}
		},
		
		unlistenToEvents: function() {
			if (this.element && this._boundEventHandler) {
				this.element.removeEventListener("keydown", this._boundEventHandler, true);
			}
			this._boundEventHandler = null;
		},
	
		/**
		 * Initializes the `env` object with pointers to key objects of the page.
		 */
		initializeEnvironment: function () {
			this.env || (this.env = {});
			this.env.element = this.element;
			this.env.document = this.element.ownerDocument;
			this.env.window = this.env.document.defaultView || this.env.document.parentWindow || window;
			this.env.frame = this.env.window.frameElement;
			this.env.selection = this.selection = new ice.Selection(this.env);
		},
	
		/**
		 * Initializes the internal range object and sets focus to the editing element.
		 */
		initializeRange: function () {
		},
	
		/**
		 * Initializes the content in the editor - cleans non-block nodes found between blocks and
		 * initializes the editor with any tracking tags found in the editing element.
		 */
		initializeEditor: function () {
			this._loadFromDom(); // refactored by dfl
			this._updateTooltipsState(); // dfl
		},
		
		/**
		 * Check whether or not this tracker is tracking changes.
		 * @return {Boolean} Is this tracker tracking?
		 */
		isTracking: function() {
			return this._isTracking;
		},
	
		/**
		 * Turn on change tracking and event handling.
		 */
		enableChangeTracking: function () {
			this._isTracking = true;
		},
	
		/**
		 * Turn off change tracking and event handling.
		 */
		disableChangeTracking: function () {
			this._isTracking = false;
		},
	
		/**
		 * Sets or toggles the tracking and event handling state.
		 * @param {Boolean} bTrack if undefined, the tracking state is toggled, otherwise set to the parameter
		 */
		toggleChangeTracking: function (bTrack) {
			bTrack = (undefined === bTrack) ? ! this._isTracking : Boolean(bTrack);
			this._isTracking = bTrack;
		},
		
		/**
		 * Gets the current user
		 * @return {Object} an object with the properties id, name
		 */
		getCurrentUser: function() {
			var u = this.currentUser || {},
				id = (u.id === null || u.id === undefined) ? "" : String(u.id);
			return {name: u.name || "", id: id};
		},
		
		/**
		 * Set the user to be tracked. 
		 * @param {Object} inUser and object has the following properties:
		 * {`id`, `name`}
		 */
		setCurrentUser: function (inUser) {
			var user = {};
			inUser = inUser || {};
			user.name = inUser.name? String(inUser.name) : "";
			if (inUser.id !== undefined && inUser.id !== null) {
				user.id = String(inUser.id);
			}
			else {
				user.id = "";
			}
			
			this.currentUser = user;
			for (var key in this._changes) {
				var change = this._changes[key];
				if (change.userid == user.id) {
					change.username = user.name;
				}
			}
			var nodes = this.getIceNodes(),
				userId,
				userIdAttr = this.attributes.userId;
			nodes.each((function(i,node) {
				userId = node.getAttribute(userIdAttr);
				if (userId === null || userId === user.id) {
					node.setAttribute(this.attributes.userName, user.name);
				}
			}).bind(this));
		},

		/**
		 * Set the session id. If the session id is not null, the tracker aggregates change span
		 * from the same user only if they have the same session id
		 */
		setSessionId: function (sid) {
			this._sessionId = sid;
		},
		
		/**
		 * Sets or toggles the tooltips state.
		 * @param {Boolean} bTooltips if undefined, the tracking state is toggled, otherwise set to the parameter
		 */
		toggleTooltips: function(bTooltips) {
			bTooltips = (undefined === bTooltips) ? ! this.tooltips : Boolean(bTooltips);
			this.tooltips = bTooltips;
			this._updateTooltipsState();
		},
	
		visible: function(el) {
			if(el.nodeType === ice.dom.TEXT_NODE) el = el.parentNode;
			var rect = el.getBoundingClientRect();
			return ( rect.top > 0 && rect.left > 0);
		},

	 	
		/**
		 * Returns a tracking tag for the given `changeType`, with the optional `childNode` appended.
		 * @private
		 */
		_createIceNode: function (changeType, childNode, changeId) {
			var node = this.env.document.createElement(this.changeTypes[changeType].tag);
			node.setAttribute("class", this._getIceNodeClass(changeType));
	
			if (childNode) {
				node.appendChild(childNode);
			}
			this._addChange(changeType, [node], changeId);
	
			return node;
		},
	
		/**
		 * Inserts the given string/node into the given range with tracking tags, collapsing (deleting)
		 * the range first if needed. If range is undefined, then the range from the Selection object
		 * is used. If the range is in a parent delete node, then the range is positioned after the delete.
		 * @param options may contain <strong>nodes</strong> (DOM element or array of dom elements) or <strong>text</strong> (string). 
		 * @return {Boolean} true if the action should continue, false if the action was finished in the insert sequence
		 */
		insert: function (options) {
			this.hostMethods.beforeInsert && this.hostMethods.beforeInsert();

			var _rng = this.getCurrentRange(),
				range = this._isRangeInElement(_rng, this.element),
				hostRange = range ? null : this.hostMethods.getHostRange(),
				changeid = this._startBatchChange(),
				hadSelection = Boolean(range && !range.collapsed),
				ret = false;
			
			options = options || {};
			
			
		// If we have any nodes selected, then we want to delete them before inserting the new text.
			try {
				if (hadSelection) {
					this._deleteContents(false, range); 
				// Update the range
					range = this.getCurrentRange();
				}
				if (range || hostRange) {
					var nodes = options.nodes;
					if (nodes && ! $.isArray(nodes)) {
						nodes = [nodes];
					}
			
					// If we are in a non-tracking/void element, move the range to the end/outside.
					this._moveRangeToValidTrackingPos(range, hostRange);
			
					// insertnodes returns true if the text was inserted
					ret = this._insertNodes(range, hostRange, {nodes: nodes, text: options.text, insertStubText: options.insertStubText !== false});
				}
			}
			catch(e) {
				logError(e, "while trying to insert nodes");
			}
			finally {
				this._endBatchChange(changeid, nodes || options.text || ret);
			}
			return ret;//isPropagating;
		},
	
		/**
		 * Deletes the contents in the given range or the range from the Selection object. If the range
		 * is not collapsed, then a selection delete is handled; otherwise, it deletes one character
		 * to the left or right if the right parameter is false or true, respectively.
		 * @return true if deletion was handled.
		 * @private
		 */
		_deleteContents: function (right, range) {
			var prevent = true, changeid,
				browser = this._browser;
			
			this.hostMethods.beforeDelete && this.hostMethods.beforeDelete();
			if (range) {
				this.selection.addRange(range);
			} 
			else {
				range = this.getCurrentRange();
			}
			changeid = this._startBatchChange();
			try {
				if (range.collapsed === false) {
					range = this._deleteSelection(range);
	/*				if(this._browser.mozilla){
						if(range.startContainer.parentNode.previousSibling){
							range.setEnd(range.startContainer.parentNode.previousSibling, 0);
							range.moveEnd(ice.dom.CHARACTER_UNIT, ice.dom.getNodeCharacterLength(range.endContainer));
						}
						else { 
							range.setEndAfter(range.startContainer.parentNode);
						}
						range.collapse(false);
					}
					else { */
						if(range && ! this.visible(range.endContainer)) {
							range.setEnd(range.endContainer, Math.max(0, range.endOffset - 1));
							range.collapse(false);
						}
//					}
				}
				else {
					this._cleanupSelection(range, false, true);
					// if we're inside a current insert range, let the editor take care of the deletion
					if (this._isCurrentUserIceNode(this._getIceNode(range.startContainer, INSERT_TYPE))) {
						return false;
					}

			        if (right) {
						// RIGHT DELETE
						if(browser["type"] === "mozilla"){
							prevent = this._deleteRight(range);
							// Handling track change show/hide
							if(!this.visible(range.endContainer)){
								if(range.endContainer.parentNode.nextSibling){
			//						range.setEnd(range.endContainer.parentNode.nextSibling, 0);
									range.setEndBefore(range.endContainer.parentNode.nextSibling);
								} else {
									range.setEndAfter(range.endContainer);
								}
								range.collapse(false);
							}
						}
						else {
							// Calibrate Cursor before deleting
							if(range.endOffset === ice.dom.getNodeCharacterLength(range.endContainer)){
								var next = range.startContainer.nextSibling;
								if ($(next).is(this._deleteSelector)) {
									while(next){
										if ($(next).is(this._deleteSelector)) {
											next = next.nextSibling;
											continue;
										}
										range.setStart(next, 0);
										range.collapse(true);
										break;
									}
								}
							}
			
							// Delete
							prevent = this._deleteRight(range);
			
							// Calibrate Cursor after deleting
							if(!this.visible(range.endContainer)){
								if ($(range.endContainer.parentNode).is(this._iceSelector)) {
			//						range.setStart(range.endContainer.parentNode.nextSibling, 0);
									range.setStartAfter(range.endContainer.parentNode);
									range.collapse(true);
								}
							}
						}
					}
					else {
						// LEFT DELETE
						if(browser.mozilla){
							prevent = this._deleteLeft(range);
							// Handling track change show/hide
							if(!this.visible(range.startContainer)){
								if(range.startContainer.parentNode.previousSibling){
									range.setEnd(range.startContainer.parentNode.previousSibling, 0);
								} else {
									range.setEnd(range.startContainer.parentNode, 0);
								}
								range.moveEnd(ice.dom.CHARACTER_UNIT, ice.dom.getNodeCharacterLength(range.endContainer));
								range.collapse(false);
							}
						}
						else {
							if(!this.visible(range.startContainer)){
								if(range.endOffset === ice.dom.getNodeCharacterLength(range.endContainer)){
									var prev = range.startContainer.previousSibling;
									if ($(prev).is(this._deleteSelector)) {
										while(prev){
											if ($(prev).is(this._deleteSelector)) {
												prev = prev.prevSibling;
												continue;
											}
											range.setEndBefore(prev.nextSibling, 0);
											range.collapse(false);
											break;
										}
									}
								}
							}
							prevent = this._deleteLeft(range);
						}
					}
				}
		
				range && this.selection.addRange(range);
			}
			finally {
				this._endBatchChange(changeid, prevent);
			}
			return prevent;
		},
	
		/**
		 * Returns the changes - a hash of objects with the following properties:
		 * [changeid] => {`type`, `time`, `userid`, `username`, `lastTime`, `data`}
		 * @param {LITE.AcceptRejectOptions} [options=null] filtering options for the changes to be accepted
		 */
		getChanges: function (options) {
			var changes = options ? this._filterChanges(options) : this._changes;
			return $.extend({}, changes);
		},
	
		/**
		 * Returns an array with the user ids who made the changes
		 */
		getChangeUserids: function () {
			var self = this,
				keys = Object.keys(this._changes),
				result = keys.map(function(key) {
					return self._changes[keys[key]].userid
				});

			// probably makes the list unique
			return result.sort().filter(function (el, i, a) {
				if (i === a.indexOf(el)) return 1;
				return 0;
			});
		},
	
		/**
		 * Returns the html contents for the tracked element.
		 */
		getElementContent: function () {
			return this.element.innerHTML;
		},
	
		/**
		 * Returns the html contents, without tracking tags, for `this.element` or
		 * the optional `body` param which can be of either type string or node.
		 * Delete tags, and their html content, are completely removed; all other
		 * change type tags are removed, leaving the html content in place. After
		 * cleaning, the optional `callback` is executed, which should further
		 * modify and return the element body.
		 *
		 * prepare gets run before the body is cleaned by ice.
		 */
		getCleanContent: function (body, callback, prepare) {
			var newBody = this.getCleanDOM(body, {callback:callback, prepare: prepare, clone: true});
			return (newBody && newBody.innerHTML) || "";
		},
		
		/**
		 * Returns a clone of the DOM, without tracking tags, for `this.element` or
		 * the optional `body` param which can be of either type string or node.
		 * Delete tags, and their html content, are completely removed; all other
		 * change type tags are removed, leaving the html content in place. 
		 * @param body If not null, the node or html to process
		 * @param options may contain:
		 * <ul><li>callback - executed after cleaning, should return the processed body</li>
		 * <li>clone If true, process a clone of the target element</li>
		 * <li>prepare function to run on body before the cleaning</li>
		 */
		getCleanDOM : function(body, options) {
			var classList = '',
				self = this;
			options = options || {};
			$.each(this.changeTypes, function (type, i) {
				if (type !== DELETE_TYPE) {
					if (i > 0) {
						classList += ',';
					}
					classList += '.' + self._getIceNodeClass(type);
				}
			});
			if (body) {
				if (typeof body === 'string') {
					body = $('<div>' + body + '</div>');
				}
				else if (options.clone){
					body = $(body).clone()[0];
				}
			} 
			else {
				body = options.clone? $(this.element).clone()[0] : this.element;
			}
			return this._cleanBody(body, classList, options);
		},
		
		_cleanBody: function(body, classList, options) {
			body = options.prepare ? options.prepare.call(this, body) : body;
			var $body = $(body),
				changes = $body.find(classList);
			$.each(changes, function (i,el) {
				while (el.firstChild) {
					el.parentNode.insertBefore(el.firstChild, el);
				}
				el.parentNode.removeChild(el);
			});
			
			$body.find(this._deleteSelector).remove();
	
			body = options.callback ? options.callback.call(this, body) : body;
	
			return body;
		},
	
		/**
		 * Accepts all changes in the element body - removes delete nodes, and removes outer
		 * insert tags keeping the inner content in place.
		 * @param {LITE.AcceptRejectOptions} options=null filtering options for the changes to be accepted
		 */
		acceptAll: function (options) {
			if (options) {
				return this._acceptRejectSome(options, true);
			}
			else {
				this.getCleanDOM(this.element, {
					clone: false
				});
				this._changes = {}; // dfl, reset the changes table
				this._triggerChange({ isText: true }); // notify the world that our change count has changed
			}
		},
	
		/**
		 * Rejects all changes in the element body - removes insert nodes, and removes outer
		 * delete tags keeping the inner content in place.*
		 * @param {LITE.AcceptRejectOptions} options=null filtering options for the changes to be accepted
		 */
		rejectAll: function (options) {
			if (options) {
				return this._acceptRejectSome(options, false);
			}
			else {
				var insSel = this._insertSelector,
					delSel = this._deleteSelector,
					content, self = this,
					$element = $(this.element);
		
				$element.find(insSel).each(function(i,e) {
					self._removeNode(e);
				});
				$element.find(delSel).each(
					function (i, el) {
					content = ice.dom.contents(el);
					ice.dom.replaceWith(el, content);
					$.each(content, function(i,e) {
						var parent = e && e.parentNode;
						self._normalizeNode(parent);
					});
				});
				this._changes = {}; // dfl, reset the changes table
				this._triggerChange({ isText: true }); // notify the world that our change count has changed
			}
		},
	
		/**
		 * Accepts the change at the given, or first tracking parent node of, `node`.	If
		 * `node` is undefined then the startContainer of the current collapsed range will be used.
		 * In the case of insert, inner content will be used to replace the containing tag; and in
		 * the case of delete, the node will be removed.
		 */
		acceptChange: function (node) {
			this.acceptRejectChange(node, { isAccept: true });
		},
	
		/**
		 * Rejects the change at the given, or first tracking parent node of, `node`.	If
		 * `node` is undefined then the startContainer of the current collapsed range will be used.
		 * In the case of delete, inner content will be used to replace the containing tag; and in
		 * the case of insert, the node will be removed.
		 */
		rejectChange: function (node) {
			this.acceptRejectChange(node, { isAccept: false });
		},
	
		/**
		 * Handles accepting or rejecting tracking changes
		 */
		acceptRejectChange: function (node, options) {
			var delSel, insSel, selector, removeSel, replaceSel, 
				trackNode, changes, dom = ice.dom, nChanges,
				self = this, changeId, content, userStyle,
				$element = $(this.element),
				userStyles = this._userStyles,
				userId, userAttr = this.attributes.userId,
				delClass = this._getIceNodeClass(DELETE_TYPE), 
				insClass = this._getIceNodeClass(INSERT_TYPE),
				isAccept = options && options.isAccept,
				dontNotify = options && (options.notify === false);
		
			if (!node) {
				var range = this.getCurrentRange();
				if (! range || !range.collapsed) {
					return;
				}
				node = range.startContainer;
			}
		
			delSel = removeSel = '.' + delClass;
			insSel = replaceSel = '.' + insClass;
			if (!isAccept) {
				removeSel = insSel;
				replaceSel = delSel;
			}
	
			selector = delSel + ',' + insSel;
			trackNode = dom.getNode(node, selector);
			changeId = trackNode.getAttribute(this.attributes.changeId);
				// Some changes are done in batches so there may be other tracking
				// nodes with the same `changeIdAttribute` batch number.
			changes = $element.find(removeSel + '[' + this.attributes.changeId + '=' + changeId + ']');
			nChanges = changes.length;
			changes.each(function(i, changeNode) {
				self._removeNode(changeNode);
			});

			// we handle the replaced nodes after the deleted nodes because, well, the engine may b buggy, resulting in some nesting
			changes = $element.find(replaceSel + '[' + this.attributes.changeId + '=' + changeId + ']');
			nChanges += changes.length;
		
			$.each(changes, function (i, node) {
				if (isNewlineNode(node)) {
					return stripNode(node);
				}
				userId = node.getAttribute(userAttr);
				userStyle = userId !== null ? userStyles[userId] || "" :"";
				
				content = ice.dom.contents(node); 
				// work around a situation where the browser extracts the node style and applies it to the content
				$(node).removeClass(insClass + ' ' + delClass + ' ' + userStyle);
				dom.replaceWith(node, content);
				$.each(content, function(i,e) {
					var txt = ice.dom.TEXT_NODE == e.nodeType && e.nodeValue;
					if (txt) {
						var found = false;
						while (txt.indexOf("  ") >= 0) {
							found = true;
							txt = txt.replace("  ", " \u00a0"); // replace two spaces with space+nbsp
						}
						if (found) {
							e.nodeValue = txt;
						}
					}
					var parent = e && e.parentNode;
					self._normalizeNode(parent);
				});
			});

			/* begin dfl: if changes were accepted/rejected, remove change trigger change event */
			delete this._changes[changeId];
			if (nChanges > 0 && ! dontNotify) {
				this._triggerChange({ isText: true });
			}
			/* end dfl */
		},
	
		/**
		 * Returns true if the given `node`, or the current collapsed range is in a tracking
		 * node; otherwise, false.
		 * @param node The node to test or null to test the selection
		 * @param onlyNode if true, test only the node
		 * @param cleanupDOM - if false, don't mess with the selection, just test
		 */
		isInsideChange: function (node, onlyNode, cleanupDOM) {
			try {
				return Boolean(this.currentChangeNode(node, onlyNode, cleanupDOM));
			}
			catch (e) {
				logError(e, "While testing if isInsideChange");
				return false;
			}
		},
	
		/**
		 * Returns a jquery list of all the tracking nodes in the current editable element
		 */
		getIceNodes : function() {
			var classList = [];
			var self = this;
			$.each(this.changeTypes, // iterate over type map
				function (type) {
					classList.push('.' + self._getIceNodeClass(type));
				});
			classList = classList.join(',');
			return $(this.element).find(classList);
		},
		
		/**
		 * Returns this `node` or the first parent tracking node with the given `changeType`.
		 * @private
		 */
		_getIceNode: function (node, changeType) {
			var selector = this.changeTypes[changeType].tag + '.' + this._getIceNodeClass(changeType);
			return ice.dom.getNode((node && node.$) || node, selector);
		},
		
		_isNodeOfChangeType: function(node, changeType) {
			if (! node) {
				return false;
			}
			var selector = '.' + this._getIceNodeClass(changeType);
			return $(node.$ || node).is(selector);
		},
		
		_isInsertNode: function(node) {
			return this._isNodeOfChangeType(node, INSERT_TYPE);
		},
		
		_isDeleteNode: function(node) {
			return this._isNodeOfChangeType(node, DELETE_TYPE);
		},
		
		_normalizeNode: function(node) {
			return 	ice.dom.normalizeNode(node, this._browser.msie);
		},
	
		/**
		 * Sets the given `range` to the first position, to the right, where it is outside of
		 * void elements.
		 * @private
		 */
		_moveRangeToValidTrackingPos: function (range, hostRange) {
			// set range to hostRange if available
			if (! (range = (hostRange || range))) {
				return;
			}
			
			var voidEl,
				el, searchBack = -1, elNode,
				visited = [], newEdge, edgeNode,
				fnode = hostRange ? this.hostMethods.getHostNode : nativeElement,
				found = false;
			while (! found) {
				el = range.startContainer;
				if (! el || visited.indexOf(el) >= 0) {
					return; // loop
				}
				elNode = fnode(el);
				visited.push(el);
				voidEl = this._getVoidElement({ node: elNode, checkEmpty: false });
				if (voidEl) {
					if ((voidEl !== el) && (visited.indexOf(voidEl) >= 0)) {
						return; // loop
					}
					visited.push(voidEl);
				}
				else {
					found = ice.dom.isTextContainer(elNode);
				}
				if (! found) { // in void element or non text container
					if (-1 == searchBack) {
						searchBack = ! isOnRightEdge(fnode(range.startContainer), range.startOffset);
					}
					newEdge = searchBack ? ice.dom.findPrevTextContainer(voidEl || elNode, this.element) :
							ice.dom.findNextTextContainer(voidEl || elNode, this.element);
					edgeNode = newEdge.node;
					// we have a new edge node

					if (hostRange) {
						edgeNode = this.hostMethods.makeHostElement(edgeNode);
					}
					try { 
						if (searchBack) {
							range.setStart(edgeNode, newEdge.offset);
						}
						else {
							range.setEnd(edgeNode, newEdge.offset);
						}
						range.collapse(searchBack);
					}
					catch (e) { // if we can't set the selection for whatever reason, end of document etc., break
						logError(e, "While trying to move range to valid tracking position");
						break;
					}
				}
			}
		},
		
		/**
		 * Utility function
		 * Returns the range if its startcontainer is a descendant of (or equal to) the given top element
		 * @private
		 */
		_isRangeInElement: function(range, top) {
			var start = range && range.startContainer;
			while (start) {
				if (start == top) {
					return range;
				}
				start = start.parentNode;
			}
			return null;
		},
	
	
		/**
		 * Returns the given `node` or the first parent node that matches against the list of void elements.
		 * dfl: added try/catch
		 * @private
		 */
		_getVoidElement: function (options) {
			if (! options) {
				return null;
			}
			var node = options.node,
				checkEmpty = options.checkEmpty !== false;
			
			try {
				var voidParent = this._getIceNode(node, DELETE_TYPE);
				if (! voidParent) {
					if (3 == node.nodeType && (checkEmpty && node.nodeValue == '\u200B')) {
						return node;
					}
				}
				return voidParent;
			}
			catch(e) {
				logError(e, "While trying to get void element of", node);
				return null;
			}
		},
		
		/**
		 * @private
		 * If the range is collapsed, removes empty nodes around the caret
		 * @param range the range to clean up
		 * @param isHostRange if true, the range is a ckeditor range
		 * @param changeSelection if true, the selected node can also be cleaned up
		 */
		_cleanupSelection: function(range, isHostRange, changeSelection) {
			var start;
			if (! range || ! range.collapsed || ! (start = range.startContainer)) {
				return;
			}
			if (isHostRange) {
				start = this.hostMethods.getHostNode(start);
			}
			var nt = start.nodeType;
			if (ice.dom.TEXT_NODE == nt) {
				return this._cleanupTextSelection(range, start, isHostRange, changeSelection);
			}
			else {
				return this._cleanupElementSelection(range, isHostRange);
			}
		},
		
		/**
		 * @private
		 * assumes range is valid for this operation
		 */
		_cleanupTextSelection: function(range, start, isHostRange, changeSelection) {
			this._cleanupAroundNode(start);
			if (changeSelection && ice.dom.isEmptyTextNode(start)) {
				var parent = start.parentNode, 
					ind = ice.dom.getNodeIndex(start),
					f = isHostRange ? this.hostMethods.makeHostElement : nativeElement;
				parent.removeChild(start);
				ind = Math.max(0, ind);
				range.setStart(f(parent), ind);
				range.setEnd(f(parent), ind);
			}
		},

			
			/**
		 * @private
		 * assumes range is valid for this operation
		 */
		_cleanupElementSelection: function(range, isHostRange) {
			var start, includeStart = false,
				parent = isHostRange ? this.hostMethods.getHostNode(range.startContainer) : range.startContainer,
				childCount = parent.childNodes.length;
			if (childCount < 1) {
				return;
			}
			try {
				if (range.startOffset > 0) {
					start = parent.childNodes[range.startOffset - 1];
				}
				else {
					start = parent.firstChild;
					includeStart = true;
				}
				if (! start) {
					return;
				}
			}
			catch(e) {
				return;
			}
			this._cleanupAroundNode(start, includeStart);
			if (range.startOffset === 0) {
				return;
			}
			var ind = ice.dom.getNodeIndex(start) + 1;
			if (ice.dom.isEmptyTextNode(start)) {
				ind = Math.max(0, ind - 1);
				parent.removeChild(start);
			}
			if (parent.childNodes.length !== childCount) {
				var f = isHostRange ? this.hostMethods.makeHostElement : nativeElement;
				range.setStart(f(parent), ind);
				range.setEnd(f(parent), ind);
			}
		},
		
		_cleanupAroundNode: function(node, includeNode) {
			var parent = node.parentNode,
				anchor = node.nextSibling,
				isEmpty,
				tmp;
			while (anchor) {
				isEmpty = ($(anchor).is(this._iceSelector) && ice.dom.hasNoTextOrStubContent(anchor)) 
					|| ice.dom.isEmptyTextNode(anchor);
				if (isEmpty) {
					tmp = anchor;
					anchor = anchor.nextSibling;
					parent.removeChild(tmp);
				}
				else {
					anchor = anchor.nextSibling;
				}
			}
			anchor = node.previousSibling;
			while (anchor) {
				isEmpty = ($(anchor).is(this._iceSelector) && ice.dom.hasNoTextOrStubContent(anchor)) 
				|| ice.dom.isEmptyTextNode(anchor);
				if (isEmpty) {
					tmp = anchor;
					anchor = anchor.previousSibling;
					parent.removeChild(tmp);
				}
				else {
					anchor = anchor.previousSibling;
				}
			}
			if (includeNode && ice.dom.isEmptyTextNode(node)) {
				parent.removeChild(node);
			}
		},
	
		/**
		 * Returns true if node has a user id attribute that matches the current user id.
		 * @private
		 */
		_isCurrentUserIceNode: function (node) {
			var ret = Boolean(node && $(node).attr(this.attributes.userId) === this.currentUser.id);
			if (ret && this._sessionId) {
				ret = node.getAttribute(this.attributes.sessionId) === this._sessionId;
			}
			return ret;
		},
	
		/**
		 * With the given alias, searches the changeTypes objects and returns the
		 * associated key for the alias.
		 * @private
		 */
		_getChangeTypeFromAlias: function (alias) {
			var type, ctnType = null;
			for (type in this.changeTypes) {
				if (this.changeTypes.hasOwnProperty(type)) {
					if (this.changeTypes[type].alias == alias) {
						ctnType = type;
					}
				}
			}
	
			return ctnType;
		},
	
/**
 * @private
 */				
		_getIceNodeClass: function (changeType) {
			return this.attrValuePrefix + this.changeTypes[changeType].alias;
		},
	
		/**
		 * @private
		 */				
		_getUserStyle: function (userid) {
			if (userid === null || userid === "" || "undefined" == typeof userid) {
				return this.stylePrefix;
			}
			var styleIndex = null;
			if (this._userStyles[userid]) {
				styleIndex = this._userStyles[userid];
			}
			else {
				styleIndex = this._setUserStyle(userid, this._getNewStyleId());
			}
			return styleIndex;
		},
	
		/**
		 * @private
		 */
		_setUserStyle: function (userid, styleIndex) {
			var style = this.stylePrefix + '-' + styleIndex;
			if (!this._styles[styleIndex]) {
				this._styles[styleIndex] = true;
			}
			return this._userStyles[userid] = style;
		},
	
		_getNewStyleId: function () {
			var id = ++this._uniqueStyleIndex;
			if (this._styles[id]) {
			// Dupe.. create another..
				return this._getNewStyleId();
			} 
			else {
				this._styles[id] = true;
				return id;
			}
		},
	
		_addChange: function (ctnType, ctNodes, changeIdToUse) {
			var changeid = changeIdToUse || this._batchChangeId || this.getNewChangeId(),
				self = this;

			if (!this._changes[changeid]) {
				var now =  (new Date()).getTime();
				// Create the change object.
				this._changes[changeid] = {
					type: ctnType,
					time: now,
					lastTime: now,
					sessionId: this._sessionId,
					userid: String(this.currentUser.id),// dfl: must stringify for consistency - when we read the props from dom attrs they are strings
					username: this.currentUser.name,
					data : this._changeData || ""
				};
				this._triggerChange({ text: false }); //dfl
			}
			$.each(ctNodes, function (i) {
				self._addNodeToChange(changeid, ctNodes[i]);
			});
	
			return changeid;
		},
	
		/**
		 * Adds tracking attributes from the change with changeid to the ctNode.
		 * @param changeid Id of an existing change.
		 * @param ctNode The element to add for the change.
		 * @private
		 */
		_addNodeToChange: function (changeid, ctNode) {
			var change = this.getChange(changeid),
				attributes = {};
			
			if (!ctNode.getAttribute(this.attributes.changeId)) {
				attributes[this.attributes.changeId] = changeid;
			}
// handle missing userid, try to set username according to userid
			var userId = ctNode.getAttribute(this.attributes.userId); 
			if (! userId) {
				userId = change.userid;
				attributes[this.attributes.userId] = userId;
			}
			if (userId == change.userid) {
				attributes[this.attributes.userName] = change.username;
			}
			
// add change data
			var changeData = ctNode.getAttribute(this.attributes.changeData);
			if (null === changeData) {
				attributes[this.attributes.changeData] = this._changeData || "";
			}
			
			if (!ctNode.getAttribute(this.attributes.time)) {
				attributes[this.attributes.time] = change.time;
			}
			
			if (!ctNode.getAttribute(this.attributes.lastTime)) {
				attributes[this.attributes.lastTime] = change.lastTime;
			}
			
			if (change.sessionId && ! ctNode.getAttribute(this.attributes.sessionId)) {
				attributes[this.attributes.sessionId] = change.sessionId;
			}
			
			if (! change.style) {
				change.style = this._getUserStyle(change.userid);
			}
			$(ctNode).attr(attributes).addClass(change.style);
			/* Added by dfl */
			this._updateNodeTooltip(ctNode);
		},
	
		getChange: function (changeid) {
			return this._changes[changeid] || null;
		},
	
		getNewChangeId: function () {
			var id = ++this._uniqueIDIndex;
			if (this._changes[id]) {
				// Dupe.. create another..
				id = this.getNewChangeId();
			}
			return id;
		},
	
		/**
		 * @private
		 * Start a batch change if none is already underway
		 * @return a change id if a new batch has been started, otherwise null
		 */
		_startBatchChange: function () {
			return this._batchChangeId ? null : 
				(this._batchChangeId = this.getNewChangeId());
		},
		
		/**
		 * Returns the top level DOM element handled by this change tracker
		 */
		getContentElement: function() {
			return this.element;
		},
		
		/**
		 * @private
		 * End the batch change
		 * @param changeid If not identical to the current change id, no action is taken
		 * this allows callers to start a batch change but end it only if the change was really started by the caller
		 * @param wasTextChanged if true, notify that text was changed in this batch
		 */
		_endBatchChange: function (changeid, wasTextChanged) {
			if (changeid && (changeid === this._batchChangeId)) {
				this._batchChangeId = null;
				
				if (wasTextChanged) {
					this._triggerChange({ isText: true });
				}
			}
		},
	
		getCurrentRange: function () {
			try {
				return this.selection.getRangeAt(0);
			}
			catch (e) {
				logError(e, "While trying to get current range");
				return null;
			}
		},
	
		_insertNodes: function (_range, hostRange, _data) {
			var range = hostRange || _range,
				data = _data || {},
				_start = range.startContainer,
				start = (_start && _start.$) || _start,
				f = hostRange ? this.hostMethods.makeHostElement : nativeElement,
				nodes = data.nodes,
				insertStubText = data.insertStubText !== false,
				text = data.text, i, len,
				doc= this.env.document,
				inserted = false;
				
			var ctNode = this._getIceNode(start, INSERT_TYPE),
				inCurrentUserInsert = this._isCurrentUserIceNode(ctNode);
	
			this._cleanupSelection(range, Boolean(hostRange), true);
			if (inCurrentUserInsert) {
				var head = nodes && nodes[0],
					changeId = ctNode.getAttribute(this.attributes.changeId);
				if (head) {
					inserted = true;
					range.insertNode(f(head));
					var parent = head.parentNode,
						sibling = head.nextSibling;

					len = nodes.length;
					for (i = 1; i < len; ++i) {
						if (sibling) {
							parent.insertBefore(nodes[i], sibling);
						}
						else {
							parent.appendChild(nodes[i]);
						}
					}
					/* Now move the caret to the end of the last node inserted */
					var tail = nodes[len - 1];
					if (ice.dom.TEXT_NODE == tail.nodeType) {
						range.setEnd(tail, (tail.nodeValue && tail.nodeValue.length) || 0);
					}
					else {
						range.setEndAfter(tail);
					}
					range.collapse();
					if (hostRange) {
						this.hostMethods.setHostRange(hostRange);
					}
					else {
						this.selection.addRange(range);
					}
				}
				else {
					prepareSelectionForInsert(null, range, doc, true);
				}
				// even if there was no data to insert, we are probably setting up for a char insertion
				this._updateChangeTime(changeId);
			}
			else {
				// If we aren't in an insert node which belongs to the current user, then create a new ins node
				var node = this._createIceNode(INSERT_TYPE);
				if (ctNode) {
					var nChildren = ctNode.childNodes.length;
					this._normalizeNode(ctNode);
					if (nChildren !== ctNode.childNodes.length) { // normalization removed nodes, refresh range
						if (hostRange) {
							hostRange = range = this.hostMethods.getHostRange();
						}
						else {
							range.refresh();
						}
					}
					if (ctNode) {
						var end = (hostRange && this.hostMethods.getHostNode(hostRange.endContainer)) || range.endContainer;
						// if inserting before the end of a tracked node by another user
						if ((end.nodeType == 3 && range.endOffset < range.endContainer.length) || (end !== ctNode.lastChild)) {
							ctNode = this._splitNode(ctNode, range.endContainer, range.endOffset);
						}
					}
				}
				if (ctNode) {
					range.setStartAfter(f(ctNode));
					range.collapse(true);
				}

				
				range.insertNode(f(node));
				len = (nodes && nodes.length) || 0;
				if (len) {
					inserted = true;
					for (i = 0; i < len; ++i) {
						node.appendChild(nodes[i]);
					}
					range.setEndAfter(f(node.lastChild));
					range.collapse();
				}
				else if (text) {
					inserted = true;
					var tn = doc.createTextNode(text);
					node.appendChild(tn);
					range.setEnd(tn, 1);
					range.collapse();
				}
				else {
					prepareSelectionForInsert(node, range, doc, insertStubText);
				}
				if (hostRange) {
					this.hostMethods.setHostRange(hostRange);
				}
				else {
					this.selection.addRange(range);
				}
			}
			return inserted;
		},
		
		/**
		 * @private
		 * updates the change with the current time stamp and copies to change nodes
		 */
		_updateChangeTime: function(changeId) {
			var change = this._changes[changeId];
			if (change) {
				var now = (new Date()).getTime(),
					nodes = $(this.element).find('[' + this.attributes.changeId + '=' + changeId + ']'),
					attr = this.attributes.lastTime;
				change.lastTime = now; 
				nodes.each(function(index, node) {
					node.setAttribute(attr, now);
				});
			}
		},
	
		_handleVoidEl: function(el, range) {
			// If `el` is or is in a void element, but not a delete
			// then collapse the `range` and return `true`.
			var voidEl = el && this._getVoidElement({ node: el });
			if (voidEl && !this._getIceNode(voidEl, DELETE_TYPE)) {
				range.collapse(true);
				return true;
			}
			return false;
		},
	
		_deleteSelection: function (range) {
	
			// Bookmark the range and get elements between.
			var bookmark = new ice.Bookmark(this.env, range),
				elements = ice.dom.getElementsBetween(bookmark.start, bookmark.end),
				betweenBlocks = [],
				deleteNodes = [], // used to collect the new deletion nodes
				addDeleteOptions = { deleteNodesCollection: deleteNodes, moveLeft: true, range: null };

			// elements length may change during the loop so don't optimize
			for (var i = 0; i < elements.length; i++) {
				var elem = elements[i];
				if (! elem || ! elem.parentNode) { // maybe removed as a side effect of removing other stuff
					continue;
				}
				if (ice.dom.isBlockElement(elem)) {
					betweenBlocks.push(elem);
					if (!ice.dom.canContainTextElement(elem)) {
						// Ignore containers that are not supposed to contain text. Check children instead.
						for (var k = 0; k < elem.childNodes.length; k++) {
							elements.push(elem.childNodes[k]);
						}
						continue;
					}
				}
				// Ignore empty space nodes
				if (ice.dom.isEmptyTextNode(elem)) {
					this._removeNode(elem);
					continue;
				}
		
				if (!this._getVoidElement({ node: elem })) {
					// If the element is not a text or stub node, go deeper and check the children.
					if (elem.nodeType !== ice.dom.TEXT_NODE) {
						// Browsers like to insert breaks into empty paragraphs - remove them
						if (isBRNode(elem)) {
							this._addDeleteTrackingToBreak(elem, addDeleteOptions);
							continue;
						}
			
						if (ice.dom.isStubElement(elem)) {
							this._addDeleteTracking(elem, addDeleteOptions);
							continue;
						}
						if (ice.dom.hasNoTextOrStubContent(elem)) {
							this._removeNode(elem);
							continue;
						}
						
//						if (isParagraphNode(elem)) {
//							this._addDeleteTrackingToBreak(elem, addDeleteOptions);
//						}
			
						for (var j = 0; j < elem.childNodes.length; j++) {
							var child = elem.childNodes[j];
							elements.push(child);
						}
						continue;
					}
					var parentBlock = ice.dom.getBlockParent(elem);
					this._addDeleteTracking(elem, addDeleteOptions);
					if (ice.dom.hasNoTextOrStubContent(parentBlock)) {
						ice.dom.remove(parentBlock);
					}
				}
			}
			
			if (deleteNodes.length) {
				bookmark.remove();
				this._cleanupAroundNode(deleteNodes[0]);
				range.setStartBefore(deleteNodes[0]);
				range.collapse(true);
				this.selection.addRange(range);
			}
			else {	
				bookmark.selectStartAndCollapse();
				if (range = this.getCurrentRange()) {
					this._cleanupSelection(range, false, false);
					range = this.getCurrentRange();			
				}
			}
			return range;
		},
	
		/**
		 * Deletes to the right (delete key)
		 * @private
		 */
		_deleteRight: function (range) {
	
			var parentBlock = ice.dom.isBlockElement(range.startContainer) && range.startContainer || ice.dom.getBlockParent(range.startContainer, this.element) || null,
				isEmptyBlock = parentBlock ? (ice.dom.hasNoTextOrStubContent(parentBlock)) : false,
				nextBlock = parentBlock && ice.dom.getNextContentNode(parentBlock, this.element),
				nextBlockIsEmpty = nextBlock ? (ice.dom.hasNoTextOrStubContent(nextBlock)) : false,
				initialContainer = range.endContainer,
				initialOffset = range.endOffset, i,
				commonAncestor = range.commonAncestorContainer,
				nextContainer, returnValue = false;
	
	
			// If the current block is empty then let the browser handle the delete/event.
			if (isEmptyBlock) {
				return false;
			}
	
			// Some bugs in Firefox and Webkit make the caret disappear out of text nodes, so we try to put them back in.
			if (isBRNode(commonAncestor)) {
				this._addDeleteTrackingToBreak(commonAncestor, {range: range});
				return true;
			}
			
			if (commonAncestor.nodeType !== ice.dom.TEXT_NODE) {
				// If placed at the beginning of a container that cannot contain text, such as an ul element, place the caret at the beginning of the first item.
				if (initialOffset === 0 && ice.dom.isBlockElement(commonAncestor) && (!ice.dom.canContainTextElement(commonAncestor))) {
					var firstItem = commonAncestor.firstElementChild;
					if (firstItem) {
						range.setStart(firstItem, 0);
						range.collapse();
						return this._deleteRight(range);
					}
				}
		
				if (commonAncestor.childNodes.length > initialOffset) {
					var next = commonAncestor.childNodes[initialOffset];
					if (isBRNode(next)) {
						this._addDeleteTrackingToBreak(next, {range: range});
						return true;
					}
					range.setStart(commonAncestor.childNodes[initialOffset], 0);
					range.collapse(true);
					returnValue = this._deleteRight(range);
					range.refresh();
					return returnValue;
				}
				else {
					nextContainer = ice.dom.getNextContentNode(commonAncestor, this.element);
			
					if (nextContainer) {
						if (isBRNode(nextContainer)) {
							this._addDeleteTrackingToBreak(nextContainer, { range: range }); 
							return true;
						}
						range.setEnd(nextContainer, 0);
					}
					range.collapse();
					return this._deleteRight(range);
				}
			}
	
			// Move range to position the cursor on the inside of any adjacent container that it is going
			// to potentially delete into or after a stub element.	E.G.:	test|<em>text</em>	->	test<em>|text</em> or
			// text1 |<img> text2 -> text1 <img>| text2

			try {
				range.moveEnd(ice.dom.CHARACTER_UNIT, 1);
				range.moveEnd(ice.dom.CHARACTER_UNIT, -1);
			}
			catch (ignore){}
	
			// Handle cases of the caret is at the end of a container or placed directly in a block element
			if (initialOffset === initialContainer.data.length && (!ice.dom.hasNoTextOrStubContent(initialContainer))) {
				nextContainer = ice.dom.getNextNode(initialContainer, this.element);
		
				// If the next container is outside of ICE then do nothing.
				if (!nextContainer) {
					range.selectNodeContents(initialContainer);
					range.collapse();
					return false;
				}
		
				// If the next container is <br> element find the next node
				if (isBRNode(nextContainer)) {
					this._addDeleteTrackingToBreak(nextContainer, { range: range }); 
					return true;
//					nextContainer = ice.dom.getNextNode(nextContainer, this.element);
				}
		
				// If the next container is a text node, look at the parent node instead.
				if (nextContainer.nodeType === ice.dom.TEXT_NODE) {
					nextContainer = nextContainer.parentNode;
				}
		
				// If the next container is non-editable, enclose it with a delete ice node and add an empty text node after it to position the caret.
				if (!nextContainer.isContentEditable) {
					returnValue = this._addDeleteTracking(nextContainer, {range:null, moveLeft:false, merge: true});
					var emptySpaceNode = this.env.document.createTextNode('');
					nextContainer.parentNode.insertBefore(emptySpaceNode, nextContainer.nextSibling);
					range.selectNode(emptySpaceNode);
					range.collapse(true);
					return returnValue;
				}
		
				if (this._handleVoidEl(nextContainer, range)) {
					return true;
				}
		
				// If the caret was placed directly before a stub element, enclose the element with a delete ice node.
				if (ice.dom.isChildOf(nextContainer, parentBlock) && ice.dom.isStubElement(nextContainer)) {
					return this._addDeleteTracking(nextContainer, {range:range, moveLeft:false, merge:true});
				}
	
			}
	
			if (this._handleVoidEl(nextContainer, range)) {
				return true;
			}
	
			if (ice.dom.isOnBlockBoundary(range.startContainer, range.endContainer, this.element)) {
				if (this.mergeBlocks && $(ice.dom.getBlockParent(nextContainer, this.element)).is(this.blockEl)) {
					// Since the range is moved by character, it may have passed through empty blocks.
					// <p>text {RANGE.START}</p><p></p><p>{RANGE.END} text</p>
					if (nextBlock !== ice.dom.getBlockParent(range.endContainer, this.element)) {
						range.setEnd(nextBlock, 0);
					}
					// The browsers like to auto-insert breaks into empty paragraphs - remove them.
					var elements = ice.dom.getElementsBetween(range.startContainer, range.endContainer);
					for (i = 0; i < elements.length; i++) {
						ice.dom.remove(elements[i]);
					}
					return ice.dom.mergeBlockWithSibling(range, ice.dom.getBlockParent(range.endContainer, this.element) || parentBlock);
				}
				else {
					// If the next block is empty, remove the next block.
					if (nextBlockIsEmpty) {
						ice.dom.remove(nextBlock);
						range.collapse(true);
						return true;
					}
		
					// Place the caret at the start of the next block.
					range.setStart(nextBlock, 0);
					range.collapse(true);
					return true;
				}
			}
	
			var entireTextNode = range.endContainer,
				deletedCharacter = splitTextAt(entireTextNode, range.endOffset, 1);
	
			return this._addDeleteTracking(deletedCharacter, {range:range, moveLeft:false, merge:true});
	
		},
	
		/**
		 * Deletes to the left (backspace)
		 * @private
		 */
		_deleteLeft: function (range) {
			var parentBlock = ice.dom.isBlockElement(range.startContainer) && range.startContainer || ice.dom.getBlockParent(range.startContainer, this.element) || null,
			isEmptyBlock = parentBlock ? ice.dom.hasNoTextOrStubContent(parentBlock) : false,
			prevBlock = parentBlock && ice.dom.getPrevContentNode(parentBlock, this.element), // || ice.dom.getBlockParent(parentBlock, this.element) || null,
			prevBlockIsEmpty = prevBlock ? ice.dom.hasNoTextOrStubContent(prevBlock) : false,
			initialContainer = range.startContainer,
			initialOffset = range.startOffset,
			commonAncestor = range.commonAncestorContainer,
			lastSelectable, prevContainer;
	
			// If the current block is empty, then let the browser handle the key/event.
			if (isEmptyBlock) {
				return false;
			}

			if (isBRNode(commonAncestor)) {
				this._addDeleteTrackingToBreak(commonAncestor, {range: range, moveLeft: true});
				return true;
			}
			
			// Handle cases of the caret is at the start of a container or outside a text node
			if (initialOffset === 0 || commonAncestor.nodeType !== ice.dom.TEXT_NODE) {
			// If placed at the end of a container that cannot contain text, such as an ul element, place the caret at the end of the last item.
				if (ice.dom.isBlockElement(commonAncestor) && (!ice.dom.canContainTextElement(commonAncestor))) {
					if (initialOffset === 0) {
						var firstItem = commonAncestor.firstElementChild;
						if (firstItem) {
							range.setStart(firstItem, 0);
							range.collapse();
							return this._deleteLeft(range);
						}
					} 
					else {
						var lastItem = commonAncestor.lastElementChild;
						if (lastItem) {
							lastSelectable = range.getLastSelectableChild(lastItem);
							if (lastSelectable) {
								range.setStart(lastSelectable, lastSelectable.data.length);
								range.collapse();
								return this._deleteLeft(range);
							}
						}
					}
				}
		
				if (initialOffset === 0) {
					prevContainer = ice.dom.getPrevContentNode(initialContainer, this.element);
				} 
				else {
					prevContainer = commonAncestor.childNodes[initialOffset - 1];
				}
		
				// If the previous container is outside of ICE then do nothing.
				if (!prevContainer) {
					return false;
				}
		
				// Firefox finds an ice node wrapped around an image instead of the image itself sometimes, so we make sure to look at the image instead.
				if ($(prevContainer).is(this._iceSelector) && prevContainer.childNodes.length > 0 && prevContainer.lastChild) {
					prevContainer = prevContainer.lastChild;
				}
				
				if (isBRNode(prevContainer)) {
					this._addDeleteTrackingToBreak(prevContainer, { range: range, moveLeft: true });
					return true;
				}
		
				// If the previous container is a text node, look at the parent node instead.
				if (prevContainer.nodeType === ice.dom.TEXT_NODE) {
					prevContainer = prevContainer.parentNode;
				}
		
				// If the previous container is non-editable, enclose it with a delete ice node and add an empty text node before it to position the caret.
				if (!prevContainer.isContentEditable) {
					var returnValue = this._addDeleteTracking(prevContainer, {range:null, moveLeft:true, merge:true});
					var emptySpaceNode = document.createTextNode('');
					prevContainer.parentNode.insertBefore(emptySpaceNode, prevContainer);
					range.selectNode(emptySpaceNode);
					range.collapse(true);
					return returnValue;
				}
		
				if (this._handleVoidEl(prevContainer, range)) {
					return true;
				}
		
				// If the caret was placed directly after a stub element, enclose the element with a delete ice node.
				if (ice.dom.isStubElement(prevContainer) && ice.dom.isChildOf(prevContainer, parentBlock) || !prevContainer.isContentEditable) {
					 this._addDeleteTracking(prevContainer, {range:range, moveLeft:true, merge:true});
					 return true;
				}
		
				// If the previous container is a stub element between blocks
				// then just delete and leave the range/cursor in place.
				if (ice.dom.isStubElement(prevContainer)) {
					ice.dom.remove(prevContainer);
					range.collapse(true);
					return false;
				}
		
				if (prevContainer !== parentBlock && !ice.dom.isChildOf(prevContainer, parentBlock)) {
		
					if (!ice.dom.canContainTextElement(prevContainer)) {
						prevContainer = prevContainer.lastElementChild;
					}
					// Before putting the caret into the last selectable child, lets see if the last element is a stub element. If it is, we need to put the caret there manually.
					if (prevContainer.lastChild && prevContainer.lastChild.nodeType !== ice.dom.TEXT_NODE && ice.dom.isStubElement(prevContainer.lastChild) && prevContainer.lastChild.tagName !== 'BR') {
						range.setStartAfter(prevContainer.lastChild);
						range.collapse(true);
						return true;
					}
					// Find the last selectable part of the prevContainer. If it exists, put the caret there.
					lastSelectable = range.getLastSelectableChild(prevContainer);
		
					if (lastSelectable && !ice.dom.isOnBlockBoundary(range.startContainer, lastSelectable, this.element)) {
						range.selectNodeContents(lastSelectable);
						range.collapse();
						return true;
					}
				}
			}
	
			// Firefox: If an image is at the start of the paragraph and the user has just deleted the image using backspace, an empty text node is created in the delete node before
			// the image, but the caret is placed with the image. We move the caret to the empty text node and execute deleteFromLeft again.
			if (initialOffset === 1 && !ice.dom.isBlockElement(commonAncestor) && range.startContainer.childNodes.length > 1 && range.startContainer.childNodes[0].nodeType === ice.dom.TEXT_NODE && range.startContainer.childNodes[0].data.length === 0) {
				range.setStart(range.startContainer, 0);
				return this._deleteLeft(range);
			}
	
			// Move range to position the cursor on the inside of any adjacent container that it is going
			// to potentially delete into or before a stub element.	E.G.: <em>text</em>| test	->	<em>text|</em> test or
			// text1 <img>| text2 -> text1 |<img> text2
			try {
				range.moveStart(ice.dom.CHARACTER_UNIT, -1);
				range.moveStart(ice.dom.CHARACTER_UNIT, 1);
			}
			catch(ignore){}
	
			// Handles cases in which the caret is at the start of the block.
			if (ice.dom.isOnBlockBoundary(range.startContainer, range.endContainer, this.element)) {
		
				// If the previous block is empty, remove the previous block.
				if (prevBlockIsEmpty) {
					ice.dom.remove(prevBlock);
					range.collapse();
					return true;
				}
		
				// If the previous Block ends with a stub element, set the caret behind it.
				if (prevBlock && prevBlock.lastChild && ice.dom.isStubElement(prevBlock.lastChild)) {
					range.setStartAfter(prevBlock.lastChild);
					range.collapse(true);
					return true;
				}
		
				// Place the caret at the end of the previous block.
				lastSelectable = range.getLastSelectableChild(prevBlock);
				if (lastSelectable) {
					range.setStart(lastSelectable, lastSelectable.data.length);
					range.collapse(true);
				} 
				else if (prevBlock) {
					range.setStart(prevBlock, prevBlock.childNodes.length);
					range.collapse(true);
				}
		
				return true;
			}
	
			var entireTextNode = range.startContainer;
			if (entireTextNode && (entireTextNode.nodeType === ice.dom.TEXT_NODE)) {
				var deletedCharacter = splitTextAt(entireTextNode, range.startOffset - 1, 1);
				this._addDeleteTracking(deletedCharacter, {range:range, moveLeft:true, merge:true});
				return true;
			}
			
			return false;
	
		},
		
		_removeNode: function(node) {
			var parent = node && node.parentNode;
			if (parent) {
				parent.removeChild(node);
				if (parent !== this.element && ice.dom.hasNoTextOrStubContent(parent)) {
					this._removeNode(parent);
				}
			}
		},
	
		/**
		 * @private
		 * Adds delete tracking to the provided node. The node is checked for containment in various tracking contexts
		 * (e.g. inside an insert block, delete block)
		 */
		_addDeleteTracking: function (contentNode, options) {
	
			var moveLeft = options && options.moveLeft,
				contentAddNode = this._getIceNode(contentNode, INSERT_TYPE),
				ctNode, range;
			options = options || {};
	
			if (contentAddNode) {
				return this._addDeletionInInsertNode(contentNode, contentAddNode, options);
			}
			
			range = options.range;
			if (range && this._getIceNode(contentNode, DELETE_TYPE)) {
				return this._deleteInDeleted(contentNode, options);
	
			}
			// Webkit likes to insert empty text nodes next to elements. We remove them here.
			if (contentNode.previousSibling && ice.dom.isEmptyTextNode(contentNode.previousSibling)) {
				contentNode.parentNode.removeChild(contentNode.previousSibling);
			}
			if (contentNode.nextSibling && ice.dom.isEmptyTextNode(contentNode.nextSibling)) {
				contentNode.parentNode.removeChild(contentNode.nextSibling);
			}
			var prevDelNode = this._getIceNode(contentNode.previousSibling, DELETE_TYPE),
				nextDelNode = this._getIceNode(contentNode.nextSibling, DELETE_TYPE);
	
			if (prevDelNode && this._isCurrentUserIceNode(prevDelNode)) {
				ctNode = prevDelNode;
				ctNode.appendChild(contentNode);
				if (nextDelNode && this._isCurrentUserIceNode(nextDelNode)) {
					var nextDelContents = ice.dom.extractContent(nextDelNode);
					ctNode.appendChild(nextDelContents);
					nextDelNode.parentNode.removeChild(nextDelNode);
				}
			} 
			else if (nextDelNode && this._isCurrentUserIceNode(nextDelNode)) {
				ctNode = nextDelNode;
				ctNode.insertBefore(contentNode, ctNode.firstChild);
			} 
			else { // not in the neighborhood of a delete node
				var changeId = this.getAdjacentChangeId(contentNode, moveLeft);
				ctNode = this._createIceNode(DELETE_TYPE, null, changeId);
				if (options.deleteNodesCollection) {
					options.deleteNodesCollection.push(ctNode);
				}
				contentNode.parentNode.insertBefore(ctNode, contentNode);
				ctNode.appendChild(contentNode);
			}
			if (range) {
				if (ice.dom.isStubElement(contentNode)) {
					range.selectNode(contentNode);
				} 
				else {
					range.selectNodeContents(contentNode);
				}
				if (moveLeft) {
					range.collapse(true);
				} 
				else {
					range.collapse();
				}
			}
			if (ctNode) {
				this._normalizeNode(ctNode);
				range && range.refresh();
			}
	
			return true;
	
		},
		
		/**
		 * @private
		 * Adds delete tracking to a BR node
		 */
		_addDeleteTrackingToBreak: function (brNode, options) {
			var moveLeft = Boolean(options && options.moveLeft);
			function move() {
				var range = options && options.range;
				if (range) {
					if (isBRNode(brNode) || ice.dom.hasNoTextOrStubContent(brNode) || moveLeft) {
						if (moveLeft) {
							range.setStartBefore(brNode);
							range.setEndBefore(brNode);
						}
						else {
							range.setStartAfter(brNode);
							range.setEndAfter(brNode);
						}
					}
					else if (brNode.firstChild) {
						range.setStartBefore(brNode.firstChild);
						range.setEndBefore(brNode.firstChild);
					}
					range.collapse();
				}	
			}
			
			if (! isBRNode(brNode)) {
				logError("addDeleteTracking to BR: not a break element");
				return;
			}
			
			
			// if this is a delete node, just move the caret
			if (this._isDeleteNode(brNode)) {
				return move();
			}
			// remove all attrs and classes from the node'
			stripNode(brNode);
			var type = DELETE_TYPE;
			
			ice.dom.addClass(brNode, this._getIceNodeClass(type));
			var changeId = this.getAdjacentChangeId(brNode, moveLeft);
			
			this._addChange(type, [brNode], changeId);
			
			move();
		},
		
		/**
		 * Handle the case of deletion inside a delete element
		 * @private
		 */
		_deleteInDeleted: function(contentNode, options) {
			var range = options.range, 
				moveLeft = options.moveLeft,
				ctNode;

			// It if the contentNode a text node, merge it with text nodes before and after it.
			this._normalizeNode(contentNode);// dfl - support ie8
	
			var found = false;
			if (moveLeft) {
				// Move to the left until there is valid sibling.
				var previousSibling = ice.dom.getPrevContentNode(contentNode, this.element);
				while (!found) {
					ctNode = this._getIceNode(previousSibling, DELETE_TYPE);
					if (!ctNode) {
						found = true;
					} 
					else {
						previousSibling = ice.dom.getPrevContentNode(previousSibling, this.element);
					}
				}
				if (previousSibling) {
					var lastSelectable = range.getLastSelectableChild(previousSibling);
					if (lastSelectable) {
						previousSibling = lastSelectable;
					}
					range.setStart(previousSibling, ice.dom.getNodeCharacterLength(previousSibling));
					range.collapse(true);
				}
			} 
			else {
				// Move the range to the right until there is valid sibling.
	
				var nextSibling = ice.dom.getNextContentNode(contentNode, this.element);
				while (!found) {
					ctNode = this._getIceNode(nextSibling, DELETE_TYPE);
					if (!ctNode) {
						found = true;
					} 
					else {
						nextSibling = ice.dom.getNextContentNode(nextSibling, this.element);
					}
				}
	
				if (nextSibling) {
					range.selectNodeContents(nextSibling);
					range.collapse(true);
				}
			}
			return true;
		},
		

/**
 * @private
 * Adds delete tracking markup around a content node
 * @param contentNode the content to be marked as deleted
 * @param contentAddNode the insert node surrounding the content
 * @param options may contain range, moveLeft, deleteNodesCollection, merge
 */
		_addDeletionInInsertNode: function(contentNode, contentAddNode, options) {
			var range = options && options.range,
				moveLeft = options && options.moveLeft;
			options = options || {};
			if (this._isCurrentUserIceNode(contentAddNode)) {
				if (range) {
					if (moveLeft) {
						range.setStartBefore(contentNode);
					}
					else {
						range.setStartAfter(contentNode);
					}
					range.collapse(moveLeft);
				}
				contentNode.parentNode.removeChild(contentNode);
				if (! this._browser.msie) {
					this._normalizeNode(contentAddNode);	
				}
				var $can = $(contentAddNode),
					bmCount = $can.find(".iceBookmark").length,
					cleanNode;
				if (bmCount > 0) {
					cleanNode = $can.clone();
					cleanNode.find('.iceBookmark').remove();
					cleanNode = cleanNode[0];
				}
				else {
					cleanNode = contentAddNode;
				}
					
				// Remove a potential empty tracking container
				if (ice.dom.hasNoTextOrStubContent(cleanNode)) {
					if (range) {
						range.setStartBefore(contentAddNode);
						range.collapse(true);
					}
					ice.dom.replaceWith(contentAddNode, ice.dom.contents(contentAddNode));
				}
			}
			else { // other user insert
				var cInd = rangy.dom.getNodeIndex(contentNode),
					parent = contentNode.parentNode,
					nChildren = parent.childNodes.length,
					ctNode;
				parent.removeChild(contentNode);
				ctNode = this._createIceNode(DELETE_TYPE);
				if (options.deleteNodesCollection) {
					options.deleteNodesCollection.push(ctNode);
				}
				ctNode.appendChild(contentNode);
				if (cInd > 0 && cInd >= (nChildren - 1)) {
					ice.dom.insertAfter(contentAddNode, ctNode);
				}
				else {
					if (cInd > 0) {
						var splitNode = this._splitNode(contentAddNode, parent, cInd);
						this._deleteEmptyNode(splitNode);
					}
					contentAddNode.parentNode.insertBefore(ctNode, contentAddNode);
				}
				this._deleteEmptyNode(contentAddNode);


				if (range && moveLeft) {
					range.setStartBefore(ctNode);
					range.collapse(true);
					this.selection.addRange(range);
				}
				if (options && options.merge) {
					this._mergeDeleteNode(ctNode);
				}
				if (range) {
					range.refresh();
				}

			}
			return true;
		},
		

		/**
		 * @private
		 * Deletes a node if it does not contain anything 
		 */
		_deleteEmptyNode: function(node) {
			var parent = node && node.parentNode;
			if (parent && ice.dom.hasNoTextOrStubContent(node)) {
				parent.removeChild(node);
			}
		},
	
		/**
		 * Merges a delete node with its siblings if they belong to the same user
		 * @private
		 */
		_mergeDeleteNode: function(delNode) {
			var siblingDel,
				content;
	
			if (this._isCurrentUserIceNode(siblingDel = this._getIceNode(delNode.previousSibling, DELETE_TYPE))) {
				content = ice.dom.extractContent(delNode);
				delNode.parentNode.removeChild(delNode);
				siblingDel.appendChild(content);
				this._mergeDeleteNode(siblingDel);
			}
			else if (this._isCurrentUserIceNode(siblingDel = this._getIceNode(delNode.nextSibling, DELETE_TYPE))) {
					content = ice.dom.extractContent(siblingDel);
					delNode.parentNode.removeChild(siblingDel);
					delNode.appendChild(content);
					this._mergeDeleteNode(delNode);
			} 
		},
	
	
		/**
		 * If tracking is on, handles event e when it is one of the following types:
		 * keypress, keydown. Prevents default handling if the event
		 * was fully handled.
		 */
		handleEvent: function (e) {
			if (!this._isTracking) {
				return true;
			}
			var preventEvent = false;
			if ('keypress' == e.type) {
				preventEvent = this.keyPress(e);
			} 
			else if ('keydown' == e.type) {
				preventEvent = ! this.handleKeyDown(e);
			}
			if (preventEvent) {
				e.stopImmediatePropagation();
				e.preventDefault();
			}
			return ! preventEvent;
		},

		/**
		 * @private
		 * Handles arrow, delete key events, and others.
		 * @param {Event} e Event object.
		 * @return {void|boolean} Returns true if default event needs to be blocked.
		 */
		_handleAncillaryKey: function (key) {
			var browser = this._browser,
				preventDefault = false,
				self = this,
				range = self.getCurrentRange();
			switch (key) {
				case ice.dom.DOM_VK_DELETE:
					preventDefault = this._deleteContents();
					break;
		
				case 46:
					// Key 46 is the DELETE key.
					preventDefault = this._deleteContents(true);
					break;
		
		/* ***********************************************************************************/
		/* BEGIN: Handling of caret movements inside hidden .ins/.del elements on Firefox **/
		/*  *Fix for carets getting stuck in .del elements when track changes are hidden  **/
				case ice.dom.DOM_VK_DOWN:
				case ice.dom.DOM_VK_UP:
				case ice.dom.DOM_VK_LEFT:
					if(browser["type"] === "mozilla"){
						if(!this.visible(range.startContainer)){
							// if Previous sibling exists in the paragraph, jump to the previous sibling
							if(range.startContainer.parentNode.previousSibling){
								// When moving left and moving into a hidden element, skip it and go to the previousSibling
								range.setEnd(range.startContainer.parentNode.previousSibling, 0);
								range.moveEnd(ice.dom.CHARACTER_UNIT, ice.dom.getNodeCharacterLength(range.endContainer));
								range.collapse(false);
							}
							// if Previous sibling doesn't exist, get out of the hidden zone by moving to the right
							else {
								range.setEnd(range.startContainer.parentNode.nextSibling, 0);
								range.collapse(false);
							}
						}
					  }	
			          preventDefault = false;
			          break;
				case ice.dom.DOM_VK_RIGHT:
					if(browser["type"] === "mozilla"){
						if(!this.visible(range.startContainer)){
							if(range.startContainer.parentNode.nextSibling){
								// When moving right and moving into a hidden element, skip it and go to the nextSibling
								range.setStart(range.startContainer.parentNode.nextSibling,0);
								range.collapse(true);
							}
						}
					}
					break;
		/* END: Handling of caret movements inside hidden .ins/.del elements ***************/

				default:
					// Ignore key.
					break;
			} //end switch
	
			return preventDefault;
	
		},
		
		/**
		 * Returns false if the event should be cancelled
		 */
		handleKeyDown: function (e) {
			if (this._handleSpecialKey(e)) {
				return true;
			} 
	
			return ! this.keyPress(e);
		},


		/**
		 * @private
		 * @param e event
		 * returns true if the event needs to be prevented
		 */
		onKeyDown: function (e) {
			if (this._handleSpecialKey(e)) {
				return false;
			} 
	
			return this._handleAncillaryKey(e);
		},

		/**
		 * Returns true if the event should be cancelled
		 */
		keyPress: function (e) {
			var c = null;
			if (e.ctrlKey || e.metaKey) {
				return false;
			}
	
			// Inside a br - most likely in a placeholder of a new block - delete before handling.
			var range = this.getCurrentRange(), text,
				br = range && ice.dom.parents(range.startContainer, 'br')[0] || null;
			if (br) {
				range.moveToNextEl(br);
			}
	
//			if (c !== null) {
				var key = e.keyCode ? e.keyCode : e.which;
    		    switch (key) {
    		    	case 32: //ckeditor does funny stuff with spaces, so insert it ourselves
    		    		return this.insert({ text: ' ' });
					case ice.dom.DOM_VK_DELETE:
					case 46:
					case ice.dom.DOM_VK_DOWN:
					case ice.dom.DOM_VK_UP:
					case ice.dom.DOM_VK_LEFT:
					case ice.dom.DOM_VK_RIGHT:
						return this._handleAncillaryKey(key);
					case ice.dom.DOM_VK_ENTER:
						this._handleEnter();
						return false;
					default:
						if (isIgnoredKeyCode(key))  {
							return false;
						}
						c = e["char"] || String.fromCharCode(key);

						if (c) { // covers null and empty string
							var text = this._browser.msie ? {text: c} : null;
							return this.insert(text);
						}
						return false;
				}
	//		}
	
//			return false; //this._handleAncillaryKey(e);
		},
	

		_handleEnter: function () {
			var range = this.getCurrentRange();
			if (range && !range.collapsed) {
				this._deleteContents();
			}
/*
 			this._domObserver.observe(this.element, this._domObserverConfig);
			this._setDomObserverTimeout();
*/
		},

		/**
		 * @private
		 * returns true if the keytcombination was handled. This does not mean that the event should
		 * be preventDefault()ed, just that we don't need further processing
		 */
		_handleSpecialKey: function (e) {
			var keyCode = e.which;
			if (keyCode === null) {
			// IE.
				keyCode = e.keyCode;
			}
	
			switch (keyCode) {
				case 120:
				case 88:
					if (true === e.ctrlKey || true === e.metaKey) {
						this.prepareToCut();
						return true;
					}
					break;
				case 67:
				case 99:
					if (true === e.ctrlKey || true === e.metaKey) {
						this.prepareToCopy();
						return true;
					}
					break;		
				default:
					// Not a special key.
					break;
			} //end switch
			return false;
		},
	
		/**
		 * Returns the first ice node in the hierarchy of the given node, or the current collapsed range.
		 * @param node if null, check the current selection
		 * @param onlyNode if true, check only the node, not its parents
		 * @param cleanup if false, don't clean up empty nodes around selection
		 * null if not in a track changes hierarchy
		 */
		currentChangeNode: function (node, onlyNode, cleanup) {
			var selector = this._iceSelector,
				range = null;
			if (!node) {
				range = this.getCurrentRange();
				if (! range) {
					return false;
				}
				if (cleanup !== false && range.collapsed) {
					this._cleanupSelection(range, false, false);
					node = range.startContainer;
				}
				else {
					node = range.commonAncestorContainer;
				}
			}
			
			var ret = onlyNode ? $(node).is(selector) && node : ice.dom.getNode(node, selector);
			if ((! ret) && range && range.collapsed) {
				var end = range.endContainer,
					endOffset = range.endOffset,
					nextNode = null;
				if (end.nodeType === ice.dom.TEXT_NODE) {
					if (endOffset === end.length) {
						nextNode = ice.dom.getNextNode(end);
					}
					else if (endOffset === 0) {
						nextNode = ice.dom.getPrevNode(end, this.element);
					}
				}
				else if (end.nodeType === ice.dom.ELEMENT_NODE) {
					if (endOffset === 0) {
						nextNode = ice.dom.getPrevNode(end, this.element);
					}
					else if (end.childNodes.length > endOffset) {
						end = end.childNodes[endOffset - 1];
						if ($(end).is(selector)) {
							return end;
						}
						nextNode = ice.dom.getNextNode(end);
					}
				}
				if (nextNode) {
					ret = $(nextNode).is(selector);
				}
			}
			return ret;
		},
		
		setShowChanges: function(bShow) {
			var $body = $(this.element);
			bShow = Boolean(bShow);
			this._isVisible = bShow;
			$body.toggleClass("ICE-Tracking", bShow);
			this._showTitles(bShow);
			this._updateTooltipsState();
		},
	
		reload: function() {
			this._loadFromDom();
		},
		
		hasChanges: function() {
			for (var key in this._changes) {
				var change = this._changes[key];
				if (change && change.type) {
					return true;
				}
			}
			return false;
		},
		
		countChanges: function(options) {
			var changes = this._filterChanges(options);
			return changes.count;
		},
		
		setChangeData: function(data) {
			if (null == data || (typeof data == "undefined")) {
				data = "";
			}
			this._changeData = String(data);
		},
		
		getDeleteClass: function() {
			return this._getIceNodeClass(DELETE_TYPE);
		},
		
		/**
		 * called before a copy operation. 
		 * This function processes the current selection to remove the tracking style.
		 * The tracking is restored immediately after the copy operation 
		 */
		prepareToCopy: function() {
			var range = this.getCurrentRange();
			if (range && ! range.collapsed) {
				this._removeTrackingInRange(range);
			}
		},
		
		/**
		 * Preprocesses the document selection so that a deleted span is left after the browser cut
		 * @return true if there's a selection 
		 */
		prepareToCut: function() {
			var range = this.getCurrentRange(),
				hostRange = this.hostMethods.getHostRange();
			
			if (range && hostRange && range.collapsed && ! hostRange.collapsed) {
				// special case of IE showing collapsed selection when ckeditor thinks otherwise
				try {
					var data = this.hostMethods.getHostRangeData(hostRange);
					range.setStart(data.startContainer, data.startOffset);
					range.setEnd(data.endContainer, data.endOffset);
				}
				catch (e) {
					return;
				}
			}
			if (! range || range.collapsed) {
				return false;
			}
			fixSelection(range, this.element);
			var frag = range.cloneContents(),
				origRange = range.cloneRange(),
				head = frag.firstChild,tail = frag.lastChild;
//			printRange(range, "before cut");
			this.hostMethods.beforeEdit();
			
			range.collapse(false);
			range.insertNode(frag);
			range.setStartBefore(head);
//			printRange(range, "after set start before the head");
			range.setEndAfter(tail);
//			printRange(range, "after set end after the tail");
			var cid = this._startBatchChange();
			try {
				this._deleteSelection(range);
			}
			catch (e) {
				logError(e, "While trying to delete selection");
			}
			finally {
				this._endBatchChange(cid);
				this.selection.addRange(origRange);
				this._removeTrackingInRange(origRange, false);
//				printRange(this.selection.getRangeAt(0), "range after deletion");
			}
			return true;
		},

		toString: function() {
			return "ICE " + ((this.element && this.element.id) || "(no element id)");
		},
		
		_splitNode: function(node, atNode, atOffset) {
			var parent = node.parentNode,
			  	parentOffset = rangy.dom.getNodeIndex(node),
			  	doc = atNode.ownerDocument, 
			  	leftRange = doc.createRange(),
			  	left;
			  leftRange.setStart(parent, parentOffset);
			  leftRange.setEnd(atNode, atOffset);
			  left = leftRange.extractContents();
			  parent.insertBefore(left, node);
			  if (this.isInsideChange(node, true)) {
				  this._updateNodeTooltip(node.previousSibling);
			  }
			  return node.previousSibling;
		},
		
		/**
		 * Notify that the DOM has changed
		 * if options.isText === true, also notify that text has changed
		 */
		_triggerChange: function(options) {
			if (this._isTracking) {
				this.$this.trigger("change");
				if (options && options.isText) {
					this.$this.trigger("textChange");
				}
			}
		},
	
		_updateNodeTooltip: function(node) {
			if (this.tooltips && this._isVisible) {
				this._addTooltip(node);
			}
		},
	
		_acceptRejectSome: function(options, isAccept) {
			var f = (function(index, node) {
				this.acceptRejectChange(node, { isAccept: isAccept, notify: false });
			}).bind(this);
			var changes = this._filterChanges(options);
			for (var id in changes.changes) {
				var nodes = $(this.element).find('[' + this.attributes.changeId + '=' + id + ']');
				nodes.each(f);
			}
			if (changes.count) {
				this._triggerChange({ isText: true });
			}
		},
		
		/**
		 * Filters the current change set based on options
		 * @param _options may contain one of:<ul>
		 * <li>exclude: an array of user ids to exclude
		 * <li>include: an array of user ids to include
		 * <li>filter: a filter function of the form function({userid, time, data}):boolean
		 * <li>verify: a boolean indicating whether or not to verify that there are matching dom nodes for each matching change
		 * </ul>
		 *	@return {Object} an object with two members: count, changes (map of id:changeObject)
		 * @private
		 */
		_filterChanges: function(_options) {
			var count = 0, changes = {},
				change,
				options = _options || {},
				filter = options.filter,
				exclude = options.exclude ? $.map(options.exclude, function(e) { return String(e); }) : null,
				include = options.include ? $.map(options.include, function(e) { return String(e); }) : null,
				verify = options.verify,
				elements = null;
			for (var key in this._changes) {
				change = this._changes[key];
				if (change && change.type) {	
					var skip = (filter && ! filter({userid: change.userid, time: change.time, data:change.data})) || 
						(exclude && exclude.indexOf(change.userid) >= 0) ||
						(include && include.indexOf(change.userid) < 0);
					if (! skip) {
						if (verify) {
							elements = $(this.element).find("[" + this.attributes.changeId + "]");
							skip = ! elements.length;
						}
						if (! skip) {
							++count;
							changes[key] = change;
						}
					}
				}
			}
			
			return { count : count, changes : changes };
		},
		
		_loadFromDom : function() {
			this._changes = {};
			this._uniqueStyleIndex = 0;
			var myUserId = this.currentUser && this.currentUser.id,
				myUserName = (this.currentUser && this.currentUser.name) || "",
				now = (new Date()).getTime(),
				styleMatch,
				styleRegex = new RegExp(this.stylePrefix + '-(\\d+)'),
			// Grab class for each changeType
				changeTypeClasses = [];
			for (var changeType in this.changeTypes) {
				changeTypeClasses.push(this._getIceNodeClass(changeType));
			}
	
			var nodes = this.getIceNodes();
			var f = function(i, el) {
				var styleIndex = 0,
					styleName,
					ctnType = '', i,
					classList = el.className.split(' ');
				//TODO optimize this - create a map of regexp
				for (i = 0; i < classList.length; i++) {
					styleMatch = styleRegex.exec(classList[i]);
					if (styleMatch) {
						styleName = styleMatch[0];
						styleIndex = styleMatch[1];
					}
					var ctnReg = new RegExp('(' + changeTypeClasses.join('|') + ')').exec(classList[i]);
					if (ctnReg) {
						ctnType = this._getChangeTypeFromAlias(ctnReg[1]);
					}
				}
				var userid = el.getAttribute(this.attributes.userId);
				var userName;
				if (myUserId && (userid == myUserId)) {
					userName = myUserName;
					el.setAttribute(this.attributes.userName, myUserName);
				}
				else {
					userName = el.getAttribute(this.attributes.userName);
				}
				this._setUserStyle(userid, Number(styleIndex));
				var changeid = parseInt(el.getAttribute(this.attributes.changeId) || "");
				if (isNaN(changeid)) {
					changeid = this.getNewChangeId();
					el.setAttribute(this.attributes.changeId, changeid);
				}
				var timeStamp = parseInt(el.getAttribute(this.attributes.time) || "");
				if (isNaN(timeStamp)) {
					timeStamp = now;
				}
				var lastTimeStamp = parseInt(el.getAttribute(this.attributes.lastTime) || "");
				if (isNaN(lastTimeStamp)) {
					lastTimeStamp = timeStamp;
				}
				var sessionId = el.getAttribute(this.attributes.sessionId);
			
				var changeData = el.getAttribute(this.attributes.changeData) || "";
				this._changes[changeid] = {
					type: ctnType,
					style: styleName,
					userid: String(userid),// dfl: must stringify for consistency - when we read the props from dom attrs they are strings
					username: userName,
					time: timeStamp,
					lastTime: lastTimeStamp,
					sessionId: sessionId,
					data : changeData
				};
				this._updateNodeTooltip(el);
			}.bind(this);
			nodes.each(f);
			this._triggerChange();
		},
		
		_showTitles : function(bShow) {
			var nodes = this.getIceNodes();
			if (bShow) {
				$(nodes).each((function(i, node) {
					this._updateNodeTooltip(node);
				}).bind(this));
			}
			else {
				$(nodes).removeAttr("title");
			}
		},
		
		_updateTooltipsState: function() {
			var $nodes,
				self = this;
			// show tooltips if they are enabled and change tracking is on
			if (this.tooltips && this._isVisible) {
				if (! this._showingTips) {
					this._showingTips = true;
					$nodes = this.getIceNodes();
					$nodes.each(function(i, node) {
						self._addTooltip(node);
					});					
				}
			}
			else if (this._showingTips) {
				this._showingTips = false;
				$nodes = this.getIceNodes();
				$nodes.each(function(i, node) {
					$(node).unbind("mouseover").unbind("mouseout");
				});					
			}
		},
		
		_addTooltip: function(node) {
			$(node).unbind("mouseover").unbind("mouseout").mouseover(this._tooltipMouseOver).mouseout(this._tooltipMouseOut);
		},
		
		_tooltipMouseOver: function(event) {
			var node = event.currentTarget,
				$node = $(node), to,
				self = this;
			if (event.buttons || $node.data("_tooltip_t")) {
				return;
			}
			to = setTimeout(function() {
				var iceNode = self.currentChangeNode(node),
					cid = iceNode && iceNode.getAttribute(self.attributes.changeId),
					change = cid && self.getChange(cid);
				if (change) {
					var type = ice.dom.hasClass(iceNode, self._getIceNodeClass(INSERT_TYPE)) ? "insert" : "delete";
					$node.removeData("_tooltip_t");
					self.hostMethods.showTooltip(node, {
						userName: change.username,
						changeId: cid,
						userId: change.userid,
						time: change.time,
						lastTime: change.lastTime,
						type: type
					});
				}
			}, this.tooltipsDelay);
			$node.data("_tooltip_t", to);
		},
		
		_tooltipMouseOut: function(event) {
			var node = event.currentTarget,
				$node = $(node),
				to = $node.data("_tooltip_t");
			$node.removeData("_tooltip_t");
			if (to) {
				clearTimeout(to);
			}
			else {
				this.hostMethods.hideTooltip(node);
			}
		},
		
		/**
		 * Finds all the tracking nodes involved in the range and removes their tracking classes.
		 * A timeout is set to restore the tracking classes immediately.
		 * This allows the editor to copy tracked text without its style
		 * @private
		 */
		_removeTrackingInRangeOld: function (range) {
			var insClass = this._getIceNodeClass(INSERT_TYPE), 
				delClass = this._getIceNodeClass(DELETE_TYPE),
				clsSelector = '.' + insClass+",."+delClass,
				clsAttr = "data-ice-class",
				filter = function(node) {
					var iceNode,
						$iceNode = null;
					if (node.nodeType == ice.dom.TEXT_NODE) {
						$iceNode = $(node).parents(clsSelector);
					}
					else {
						var $node = $(node);
						if ($node.is(clsSelector)) {
							$iceNode = $node; 
						}
						else {
							$iceNode = $node.parents(clsSelector);
						}
					}
					iceNode = ($iceNode && $iceNode[0]);
					if (iceNode) {
						var cls = iceNode.className;
						iceNode.setAttribute(clsAttr, cls);
						iceNode.setAttribute("class", "ice-no-decoration");
						return true;
					}
					return false;
				};
			range.getNodes(null, filter);
			var el = this.element;
			setTimeout(function() {
				var nodes = $(el).find('['+ clsAttr + ']');
				nodes.each(function(i, node) {
					var cls = node.getAttribute(clsAttr);
					if (cls) {
						node.setAttribute("class", cls);
						node.removeAttribute(clsAttr);
					}
				});
				
			}, 10);
		},
		/**
		 * Finds all the tracking nodes involved in the range and removes their tracking classes.
		 * A timeout is set to restore the tracking classes immediately.
		 * This allows the editor to copy tracked text without its style
		 * @private
		 */
		_removeTrackingInRange: function (range) {
			var insClass = this._getIceNodeClass(INSERT_TYPE), 
				delClass = this._getIceNodeClass(DELETE_TYPE),
				clsSelector = '.' + insClass+",."+delClass,
				saveMap = this._savedNodesMap,
				clsAttr = "data-ice-class",
				base = Date.now() % 1000000,
				filter = function(node) {
					var $node,iceNode,
						$iceNode = null;
					if (node.nodeType == ice.dom.TEXT_NODE) {
						$iceNode = $(node).parents(clsSelector);
					}
					else {
						$node = $(node);
						if ($node.is(clsSelector)) {
							$iceNode = $node; 
						}
						else {
							$iceNode = $node.parents(clsSelector);
						}
					}
					if (iceNode = ($iceNode && $iceNode[0])) {
						var attrs = getNodeAttributes(iceNode),
							cls = iceNode.className,
							dataId = String(base++);
						
						saveMap[dataId] = {
							attributes: attrs,
							className: cls
						};
						removeAllAttributes(iceNode);
						iceNode.setAttribute(clsAttr, dataId);
						iceNode.setAttribute("class", "ice-no-decoration");
						return true;
					}
					return false;
				};
			range.getNodes(null, filter);
			var el = this.element;
			setTimeout(function() {
				var nodes = $(el).find('['+ clsAttr + ']');
				nodes.each(function(i, node) {
					var dataId = node.getAttribute(clsAttr),
						nodeData = saveMap[dataId];
					if (dataId) {
						delete saveMap[dataId];
						Object.keys(nodeData.attributes).forEach(function(key) {
							node.setAttribute(key, nodeData.attributes[key]);
						});
						node.setAttribute("class", nodeData.className);
						node.removeAttribute(clsAttr);
					}
					else {
						logError("missing save data for node");
					}
				});
				
			}, 10);
		},
		
		_onDomMutation: function(mutations) {
			var i, len = mutations.length, m,
				nodeIndex, lst,
				node;
			for (i = 0; i < len; ++i) {
				m = mutations[i];
				switch (m.type) {
					case "childList":
						lst  = m.addedNodes;
						for (nodeIndex = lst.length - 1; nodeIndex >= 0; --nodeIndex) {
							node = lst[nodeIndex];
							console.log("mutation: added node", node.tagName);
						}
						break;
				}
			}
		},
		
		_setDomObserverTimeout: function() {
			var self = this;
			if (this._domObserverTimeout) {
				window.clearTimeout(this._domObserverTimeout);
			}
			this._domObserverTimeout = window.setTimeout(function() {
				self._domObserverTimeout = null;
				self._domObserver.disconnect();
			}, 1);
		},
		
		getAdjacentChangeId: function(node, left) {
			var next = left ? ice.dom.getNextNode(node) : ice.dom.getPrevNode(node),
				nextChange,
				changeId = null;
			
			nextChange = this._getIceNode(next, INSERT_TYPE) || this._getIceNode(next, DELETE_TYPE);
			if (! nextChange) {
				if (this._isInsertNode(next) || this._isDeleteNode(next)) {
					nextChange = next;
				}
			}
			if (nextChange && this._isCurrentUserIceNode(nextChange)) {
				changeId = nextChange.getAttribute(this.attributes.changeId);
			}
			return changeId;
		}
		

	};
	
	var console = (window && window.console) || {
		log: function(){},
		error: function(){},
		info: function(){},
		assert:function(){},
		count: function(){}
	} ;
	
	/** Utility functions **/
	
	function getNodeAttributes(node) {
		var attrs = node.attributes,
			attr,
			len = attrs && attrs.length,
			ret = {};
		for (var i = 0; i < len; ++i) {
			attr = attrs[i];
			ret[attr.name] = attr.value;
		}
		return ret;
	}
	
	function removeAllAttributes(node) {
		var last = null,
			next;
		try {
			while (node.attributes.length > 0) {
				next = node.attributes[0];
				if (next === last) {
					return;
				}
				last = next;
				node.removeAttribute(next.name);
			}
		}
		catch(ignore){}
	}

	function nativeElement(e) {
		return e;
	}
	
	/**
	 * Strip all attributes and classes from a node
	 * @param node
	 */
	function stripNode(node) {
		// remove all attrs and classes from the node
		var	attributes = $.map(node.attributes, function(attr) {
			return attr.name;
		});
		$(node).removeClass(); // remove all classes
		$.each(attributes, function(i, item) {
			node.removeAttribute(item);
		});
	}
	
	function isBRNode(node) {
		return BREAK_ELEMENT == ice.dom.getTagName(node);
	}

	function isNewlineNode(node) {
		var tag = ice.dom.getTagName(node);
		return BREAK_ELEMENT === tag || PARAGRAPH_ELEMENT === tag;
	}

	function isOnRightEdge(el, offset) {
		if (! el) {
			return false;
		}
		var type = el.nodeType;
		if (ice.dom.TEXT_NODE == type) {
			return offset && el.nodeValue && (offset >= el.nodeValue.length - 1);
		}
		if (ice.dom.ELEMENT_NODE == type) {
			return el.childNodes && el.childNodes.length && (offset >= el.childNodes.length);
		}
		return false;
	}
	
	var logError = null;
	
	function fixSelection(range, top) {
		if (! range || ! top || range.collapsed) {
			return range;
		}
		var current;
		// fix end
		try {
			while ((current = range.endContainer) && (current !== top) && (range.endOffset == 0) && ! range.collapsed) {
				if (current.previousSibling) {
					range.setEndBefore(current);
				}
				else if (current.parentNode && current.parentNode !== top) {
					range.setEndBefore(current.parentNode);
				}
				if (range.endContainer == current) {
					break;
				}
			}
		}
		catch (e) {
			logError(e, "fixSelection, while trying to set end");
		}

		
		try {
			while ((current = range.startContainer) && (current !== top) && ! range.collapsed) {
				current = range.startContainer;

				if (current.nodeType == ice.dom.TEXT_NODE) {
					if (range.startOffset >= current.nodeValue.length) {
						range.setStartAfter(current);
					}
				}
				else { // element
					if (range.startOffset >= current.childNodes.length) {
						range.setStartAfter(current);
					}
				}
				if (range.startContainer == current) {
					break;
				}
			}
		}
		catch (e) {
			logError(e, "fixSelection, while trying to set start");
		}
	}
	
	function splitTextAt(textNode, at, count) {
		var textLength = textNode.length,
			splitText;
		if (at < 0 || at >= textLength) {
			return textNode;
		}
		if (at + count >= textLength) {
			count = textLength - at;
		}
		if (count === textLength) {
			return textNode;
		}
		splitText = at > 0 ? textNode.splitText(at) : textNode;
		if (splitText.length > count) {
			splitText.splitText(count);
		}
		return splitText;		
	}
	
	function prepareSelectionForInsert(node, range, doc, insertStub) {
		if (insertStub) {
			if (range.collapsed && range.startContainer && range.startContainer.nodeType === ice.dom.TEXT_NODE && range.startContainer.length) {
				return;
			}
		// create empty node and select it, to be replaced with the typed char
			var tn = doc.createTextNode('\uFEFF');
			if (node) {
				node.appendChild(tn);
			}
			else {
				range.insertNode(tn);
			}
			range.selectNode(tn);
		}
		else if (node) {
			range.selectNodeContents(node);
		}
	}
	
	function printRange(range, message) {
		if (! range || ! range.startContainer || ! range.endContainer) {
			return;
		}
		var parts = [];
		function printText(txt) {
			if (! txt) {
				return "";
			}
			txt = txt.replace('/\n/g', "\\n").replace('/\r/g', "").replace('\u200B', "{filler}").replace('\uFEFF', "{filler}");
			if (txt.length <= 15) {
				return txt;
			}
			return txt.substring(0, 5)+ "..." + txt.substring(txt.length - 5);
		}
		function addNode(node) {
			var str;
			if (node.nodeType === 3) {
				str = "Text:" + printText(node.nodeValue); 
			}
			else {
				var txt = node.innerText;
				str = node.nodeName + (txt ? "(" + printText(txt) + ")" :'');
			}
			parts.push("<" + str + " />");
		}
		function printNode(node, offset1, offset2) {
			if ("number" !== typeof offset2) {
				offset2 = -1;
			}
			if (3 == node.nodeType) { // text
				var txt = node.nodeValue;
				parts.push(printText(txt.substring(0, offset1)));
				parts.push("|");
				if (offset2 > offset1) {
					parts.push(printText(txt.substring(offset1, offset2)));
					parts.push("|");
					parts.push(printText(txt.substring(offset2)));
				}
				else {
					parts.push(printText(txt.substring(offset1)));
				}
			}
			else if (1 == node.nodeType) {
				var i = 0,
					children = node.childNodes,
					start = 0;
				addNode(node);
				for (i = start; i < offset1; ++i) {
					addNode(children[i]);
				}
				parts.push("|");
				if (offset2 > offset1) {
					for (i = offset1; i < offset2; ++i) {
						addNode(children[i]);
					}
					parts.push('|');
				}
				if (offset2 > 0 && offset2 < children.length){
					var child = children[offset2];
					while (child) {
						addNode(child);
						child = child.nextSibling;
					}
				}

			}
		}
		if (range.startContainer === range.endContainer) {
			printNode(range.startContainer, range.startOffset, range.endOffset);
		}
		else {
			printNode(range.startContainer, range.startOffset);
			printNode(range.endContainer, range.endOffset);
		}
		var ret = parts.join(' ');
		if (message) {
			console.log(message + ":" + ret);
		}
		return ret;
	}

	ice.printRange = printRange;
	ice.InlineChangeEditor = InlineChangeEditor;

}(this.ice || window.ice, window.jQuery));
(function (ice, $) {

	"use strict";

	var exports = ice,
		dom = {},
		_browser = null,
		wsrgx = /^\s*$/,
		numrgx = /^\d+$/;

	dom.DOM_VK_DELETE = 8;
	dom.DOM_VK_LEFT = 37;
	dom.DOM_VK_UP = 38;
	dom.DOM_VK_RIGHT = 39;
	dom.DOM_VK_DOWN = 40;
	dom.DOM_VK_ENTER = 13;
	dom.ELEMENT_NODE = 1;
	dom.ATTRIBUTE_NODE = 2;
	dom.TEXT_NODE = 3;
	dom.CDATA_SECTION_NODE = 4;
	dom.ENTITY_REFERENCE_NODE = 5;
	dom.ENTITY_NODE = 6;
	dom.PROCESSING_INSTRUCTION_NODE = 7;
	dom.COMMENT_NODE = 8;
	dom.DOCUMENT_NODE = 9;
	dom.DOCUMENT_TYPE_NODE = 10;
	dom.DOCUMENT_FRAGMENT_NODE = 11;
	dom.NOTATION_NODE = 12;
	dom.CHARACTER_UNIT = 'character';
	dom.WORD_UNIT = 'word';
	dom.BREAK_ELEMENT = 'br';
	dom.PARAGRAPH_ELEMENT = 'p';
	dom.CONTENT_STUB_ELEMENTS = ['img', 'hr', 'iframe', 'param', 'link', 'meta', 'input', 'frame', 'col', 'base', 'area'];
	dom.BLOCK_ELEMENTS = ['body', 'p', 'div', 'pre', 'ul', 'ol', 'li', 'table', 'tbody', 'td', 'th', 'fieldset', 'form', 'blockquote', 'dl', 'dt', 'dd', 'dir', 'center', 'address', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
	dom.TEXT_CONTAINER_ELEMENTS = ['body','p', 'div', 'pre', 'span', 'b', 'strong', 'i', 'li', 'td', 'th', 'blockquote', 'dt', 'dd', 'center', 'address', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ins', 'del'];

	dom.STUB_ELEMENTS = dom.CONTENT_STUB_ELEMENTS.slice();
	dom.STUB_ELEMENTS.push(dom.BREAK_ELEMENT);
	
	var stubElementsString = dom.CONTENT_STUB_ELEMENTS.join(', ');
	
	dom.isEmptyString = function(str) {
		if (! str) {
			return true;
		}
		var len = str.length - 1, ch;
		while (len >= 0) {
			ch = str[len--];
			if (ch !== '\u200B' && ch !== '\uFEFF') {
				return false;
			}
		}
		return true;
	}

	dom.getKeyChar = function (e) {
		return String.fromCharCode(e.which);
	};
	dom.getClass = function (className, startElement, tagName) {
		if (!startElement) {
			startElement = document.body;
		}
		className = '.' + className.split(' ').join('.');
		if (tagName) {
			className = tagName + className;
		}
		return $.makeArray($(startElement).find(className));
	};
	dom.getId = function (id, startElement) {
		if (!startElement) {
			startElement = document;
		}
		element = startElement.getElementById(id);
		return element;
	};
	dom.getTag = function (tagName, startElement) {
		if (!startElement) {
			startElement = document;
		}
		return $.makeArray($(startElement).find(tagName));
	};
	dom.getElementWidth = function (element) {
		return element.offsetWidth;
	};
	dom.getElementHeight = function (element) {
		return element.offsetHeight;
	};
	dom.getElementDimensions = function (element) {
		return {
			width: dom.getElementWidth(element),
			height: dom.getElementHeight(element)
		};
	};

	dom.insertBefore = function (before, elem) {
		$(before).before(elem);
	};
	
	dom.insertAfter = function (after, elem) {
		if (after && elem) {
			var sibling = after.nextSibling,
				parent = after.parentNode;
			return sibling ? parent.insertBefore(elem, sibling) : parent.appendChild(elem);
		}
	};
	// Remove whitespace/newlines between nested block elements
	// that are supported by ice.
	// For example the following element with innerHTML:
	//	 <div><p> para </p> <ul>	<li> hi </li>	</ul></div>
	// Will be converted to the following:
	//	 <div><p> para </p><ul><li> hi </li></ul></div>
	dom.removeWhitespace = function(element) {
		$(element).contents().filter(function() {
			// Ice supports UL and OL, so recurse in these blocks to
			// make sure that spaces don't exist between inner LI.
			if (this.nodeType != ice.dom.TEXT_NODE && this.nodeName == 'UL' || this.nodeName == 'OL') {
				dom.removeWhitespace(this);
				return false;
			} else if (this.nodeType != ice.dom.TEXT_NODE) {
				return false;
			} else {
				return !/\S/.test(this.nodeValue);
			}
		}).remove();
	};
	dom.contents = function (el) {
		return $.makeArray($(el).contents());
	};
	/**
	 * Returns the inner contents of `el` as a DocumentFragment.
	 */
	dom.extractContent = function (el) {
		var frag = document.createDocumentFragment(),
			child;
		while ((child = el.firstChild)) {
			frag.appendChild(child);
		}
		return frag;
  };

	/**
	 * Returns this `node` or the first parent tracking node that matches the given `selector`.
	 */
	dom.getNode = function (node, selector) {
		if (! node) return null;
// dfl switch to DOM node from dom.js node
		node = node.$ || node;
// dfl don't test text nodes
		return (node.nodeType != dom.TEXT_NODE && $(node).is(selector)) ? 
				node 
				: dom.parents(node, selector)[0] || null;
	};

	dom.getParents = function (elements, filter, stopEl) {
		var res = $(elements).parents(filter);
		var ln = res.length;
		var ar = [];
		for (var i = 0; i < ln; i++) {
			if (res[i] === stopEl) {
				break;
			}
			ar.push(res[i]);
		}
		return ar;
	};
	dom.hasBlockChildren = function (parent) {
		var c = parent.childNodes.length;
		for (var i = 0; i < c; i++) {
			if (parent.childNodes[i].nodeType === dom.ELEMENT_NODE) {
				if (dom.isBlockElement(parent.childNodes[i]) === true) {
					return true;
				}
			}
		}
		return false;
	};
	dom.removeTag = function (element, selector) {
		$(element).find(selector).replaceWith(function () {
			return $(this).contents();
		});
		return element;
	};
	dom.stripEnclosingTags = function (content, allowedTags) {
		var c = $(content);
		c.find('*').not(allowedTags).replaceWith(function () {
			var ret = $();
			var $this;
			try{
				$this = $(this);
				ret = $this.contents();
			} catch(e){}

			// Handling jQuery bug (which may be fixed in the official release later)
			// http://bugs.jquery.com/ticket/13401 
			if(ret.length === 0){
				$this.remove();
			}
			return ret;
		});
		return c[0];
	};
	dom.getSiblings = function (element, dir, elementNodesOnly, stopElem) {
		if (elementNodesOnly === true) {
			if (dir === 'prev') {
				return $(element).prevAll();
			} else {
				return $(element).nextAll();
			}
		} else {
			var elems = [];
			if (dir === 'prev') {
				while (element.previousSibling) {
					element = element.previousSibling;
					if (element === stopElem) {
						break;
					}
					elems.push(element);
				}
			} else {
				while (element.nextSibling) {
					element = element.nextSibling;
					if (element === stopElem) {
						break;
					}
					elems.push(element);
				}
			}
			return elems;
		}
	};
	dom.getNodeTextContent = function (node) {
		return $(node).text();
	};
	dom.getNodeStubContent = function (node) {
		return $(node).find(stubElementsString);
	};
	dom.hasNoTextOrStubContent = function (node) {
		var str = dom.getNodeTextContent(node);
		if (! dom.isEmptyString(str)) {
			return false;
		}
		if (! node.firstChild) { // no children shortcut
			return true;
		}
		return $(node).find(stubElementsString).length === 0;
	};
	
	dom.isEmptyTextNode = function(node) {
		if (! node || (dom.TEXT_NODE !== node.nodeType)) {
			return false;
		}
		if (node.length === 0) {
			return true;
		}
		return dom.isEmptyString(node.nodeValue);
	};

	dom.getNodeCharacterLength = function (node) {
		return dom.getNodeTextContent(node).length + $(node).find(dom.STUB_ELEMENTS.join(', ')).length;
	};
	dom.setNodeTextContent = function (node, txt) {
		return $(node).text(txt);
	};
	dom.getTagName = function (node) {
		return node && node.tagName && node.tagName.toLowerCase() || null;
	};
	dom.getIframeDocument = function (iframe) {
		var doc = null;
		if (iframe.contentDocument) {
			doc = iframe.contentDocument;
		} else if (iframe.contentWindow) {
			doc = iframe.contentWindow.document;
		} else if (iframe.document) {
			doc = iframe.document;
		}
		return doc;
	};
	dom.isBlockElement = function (element) {
		return dom.BLOCK_ELEMENTS.indexOf(element.nodeName.toLowerCase()) != -1;
	};
	dom.isStubElement = function (element) {
		return dom.STUB_ELEMENTS.indexOf(element.nodeName.toLowerCase()) != -1;
	};
	dom.removeBRFromChild = function (node) {
		if (node && node.hasChildNodes()) {
			for(var z=0; z < node.childNodes.length ; z++) {
				var child = node.childNodes[z];
				if (child && (ice.dom.BREAK_ELEMENT == ice.dom.getTagName(child))) {
					child.parentNode.removeChild(child);
				}
			}
		}
	};
	dom.isChildOf = function (el, parent) {
		try {
			while (el && el.parentNode) {
				if (el.parentNode === parent) {
					return true;
				}
				el = el.parentNode;
			}
		} catch (e) {}
		return false;
	};
	dom.isChildOfTagName = function (el, name) {
		try {
			while (el && el.parentNode) {
				if (el.parentNode && el.parentNode.tagName && el.parentNode.tagName.toLowerCase() === name) {
					return el.parentNode;
				}
				el = el.parentNode;
			}
		} catch (e) {}
		return false;
	};


	dom.isChildOfTagNames = function (el, names) {
		try {
			while (el && el.parentNode) {
				if (el.parentNode && el.parentNode.tagName) {
					tagName = el.parentNode.tagName.toLowerCase();
					for (var i = 0; i < names.length; i++) {
						if (tagName === names[i]) {
							return el.parentNode;
						}
					}
				}
				el = el.parentNode;
			}
		} catch (e) {}
		return null;
	};

	dom.isChildOfClassName = function (el, name) {
		try {
			while (el && el.parentNode) {
				if ($(el.parentNode).hasClass(name)) return el.parentNode;
				el = el.parentNode;
			}
		} catch (e) {}
		return null;
	};

	dom.replaceWith = function (node, replacement) {
		return $(node).replaceWith(replacement);
	};
	dom.getElementsBetween = function (fromElem, toElem) {
		var elements = [];
		if (fromElem === toElem) {
			return elements;
		}
		if (dom.isChildOf(toElem, fromElem) === true) {
			var fElemLen = fromElem.childNodes.length;
			for (var i = 0; i < fElemLen; i++) {
				if (fromElem.childNodes[i] === toElem) {
					break;
				} else if (dom.isChildOf(toElem, fromElem.childNodes[i]) === true) {
					return dom.arrayMerge(elements, dom.getElementsBetween(fromElem.childNodes[i], toElem));
				} else {
					elements.push(fromElem.childNodes[i]);
				}
			}
			return elements;
		}
		var startEl = fromElem.nextSibling;
		while (startEl) {
			if (dom.isChildOf(toElem, startEl) === true) {
				elements = dom.arrayMerge(elements, dom.getElementsBetween(startEl, toElem));
				return elements;
			} else if (startEl === toElem) {
				return elements;
			} else {
				elements.push(startEl);
				startEl = startEl.nextSibling;
			}
		}
		var fromParents = dom.getParents(fromElem);
		var toParents = dom.getParents(toElem);
		var parentElems = dom.arrayDiff(fromParents, toParents, true);
		var pElemLen = parentElems.length;
		for (var j = 0; j < (pElemLen - 1); j++) {
			elements = dom.arrayMerge(elements, dom.getSiblings(parentElems[j], 'next'));
		}
		var lastParent = parentElems[(parentElems.length - 1)];
		elements = dom.arrayMerge(elements, dom.getElementsBetween(lastParent, toElem));
		return elements;
	};
	dom.getCommonAncestor = function (a, b) {
		var node = a;
		while (node) {
			if (dom.isChildOf(b, node) === true) {
				return node;
			}
			node = node.parentNode;
		}
		return null;
	};

	dom.getNextNode = function (node, container) {
		if (node) {
			while (node.parentNode) {
				if (node === container) {
					return null;
				}

				if (node.nextSibling) {
					// if next sibling is an empty text node, look further
					if (node.nextSibling.nodeType === dom.TEXT_NODE && node.nextSibling.length === 0) {
						node = node.nextSibling;
						continue;
					}

					return dom.getFirstChild(node.nextSibling);
				}
				node = node.parentNode;
			}
		}
		return null;
	};

	dom.getNextContentNode = function (node, container) {
		if (node) {
			while (node.parentNode) {
				if (node === container) {
					return null;
				}

				if (node.nextSibling && dom.canContainTextElement(dom.getBlockParent(node))) {
					// if next sibling is an empty text node, look further
					if (node.nextSibling.nodeType === dom.TEXT_NODE && node.nextSibling.length === 0) {
						node = node.nextSibling;
						continue;
					}

					return node.nextSibling;
				} else if (node.nextElementSibling) {
					return node.nextElementSibling;
				}

				node = node.parentNode;
			}
		}
		return null;
	};


	dom.getPrevNode = function (node, container) {
		if (node) {
			while (node.parentNode) {
				if (node === container) {
					return null;
				}

				if (node.previousSibling) {
					// if previous sibling is an empty text node, look further
					if (node.previousSibling.nodeType === dom.TEXT_NODE && node.previousSibling.length === 0) {
						node = node.previousSibling;
						continue;
					}

					return dom.getLastChild(node.previousSibling);
				}
				node = node.parentNode;
			}
		}
		return null;
	};
	dom.getPrevContentNode = function (node, container) {
		if (node) {
			while (node.parentNode) {
				if (node === container) {
					return null;
				}
				if (node.previousSibling && dom.canContainTextElement(dom.getBlockParent(node))) {

					// if previous sibling is an empty text node, look further
					if (node.previousSibling.nodeType === dom.TEXT_NODE && node.previousSibling.length === 0) {
						node = node.previousSibling;

						continue;
					}
					return node.previousSibling;
				} else if (node.previousElementSibling) {
					return node.previousElementSibling;
				}

				node = node.parentNode;
			}
		}
		return null;
	};
	
	
	function _findNextTextContainer(node, container){
		while (node) {
			if (dom.TEXT_NODE == node.nodeType) {
				return node;
			}
			for (var child = node.firstChild; child; child = child.nextSibling) {
				var ret = _findNextTextContainer(child, container);
				if (ret) {
					return ret;
				}
			}
			if (dom.isTextContainer(node)) {
				return node;
			}
			node = node.nextSibling;
		}
		return null;
	}
	
	function _findPrevTextContainer(node, container){
		while (node) {
			if (dom.TEXT_NODE == node.nodeType) {
				return node;
			}
			for (var child = node.lastChild; child; child = child.previousSibling) {
				var ret = _findPrevTextContainer(child, container);
				if (ret) {
					return ret;
				}
			}
			if (dom.isTextContainer(node)) {
				return node;
			}
			node = node.previousSibling;
		}
		return null;
	}
	
	dom.findPrevTextContainer = function(node, container) {
		if (! node || node == container) {
			return {
				node: container,
				offset: 0
			};
		}

		if (node.parentNode && dom.isTextContainer(node.parentNode)) {
			return {
				node: node.parentNode,
				offset: dom.getNodeIndex(node)
			};
		}
		while (node.previousSibling) {
			var ret = _findPrevTextContainer(node.previousSibling);
			if (ret) {
				return {
					node: ret,
					offset: dom.getNodeLength(ret)
				};
			}
			node = node.previousSibling;
		}
		
		return dom.findPrevTextContainer(node.parentNode && node.parentNode.previousSibling, container);
	};
	
	dom.findNextTextContainer = function(node, container) {
		if (! node || node == container) {
			return {
				node: container,
				offset: dom.getNodeLength(container)
			};
		}
		if (node.parentNode && dom.isTextContainer(node.parentNode)) {
			return {
				node: node.parentNode,
				offset: dom.getNodeIndex(node) + 1
			};
		}
		while (node.nextSibling) {
			var ret = _findNextTextContainer(node.nextSibling);
			if (ret) {
				return {
					node: ret,
					offset: 0
				};
			}
			node = node.previousSibling;
		}
		
		return dom.findNextTextContainer(node.parentNode && node.parentNode.nextSibling, container);
	};
	
	dom.getNodeLength = function(node) {
		return node ? 
				(dom.TEXT_NODE == node.nodeType ? 
						node.length : ((node.childNodes && node.childNodes.length) || 0)) :
				0;
	};
	
	dom.isTextContainer = function(node) {
		return (node && (dom.TEXT_NODE == node.nodeType) || dom.TEXT_CONTAINER_ELEMENTS.indexOf((node.nodeName || "").toLowerCase()) >= 0);
	};
	
	dom.getNodeIndex = function(node) {
		var i = 0;
		while( (node = node.previousSibling) ) {
			++i;
		}
		return i;
	};
	
	/* end dfl */

	dom.canContainTextElement = function (element) {
		if (element && element.nodeName) {
			return dom.TEXT_CONTAINER_ELEMENTS.lastIndexOf(element.nodeName.toLowerCase()) != -1;
		} else {
			return false;
		}
	};

	dom.getFirstChild = function (node) {
		if (node.firstChild) {
			if (node.firstChild.nodeType === dom.ELEMENT_NODE) {
				return dom.getFirstChild(node.firstChild);
			} else {
				return node.firstChild;
			}
		}
		return node;
	};
	dom.getLastChild = function (node) {
		if (node.lastChild) {
			if (node.lastChild.nodeType === dom.ELEMENT_NODE) {
				return dom.getLastChild(node.lastChild);
			} else {
				return node.lastChild;
			}
		}
		return node;
	};
	dom.removeEmptyNodes = function (parent, callback) {
		var elems = $(parent).find(':empty');
		var i = elems.length;
		while (i > 0) {
			i--;
			if (dom.isStubElement(elems[i]) === false) {
				if (!callback || callback.call(this, elems[i]) !== false) {
					dom.remove(elems[i]);
				}
			}
		}
	};
	dom.create = function (html) {
		return $(html)[0];
	};
	dom.children = function (parent, exp) {
		return $(parent).children(exp);
	};
	dom.parent = function (child, exp) {
		return $(child).parent(exp)[0];
	};
	dom.parents = function (child, exp) {
		return $(child).parents(exp);
	};
	dom.walk = function (elem, callback, lvl) {
		if (!elem) {
			return;
		}
		if (!lvl) {
			lvl = 0;
		}
		var retVal = callback.call(this, elem, lvl);
		if (retVal === false) {
			return;
		}
		if (elem.childNodes && elem.childNodes.length > 0) {
			dom.walk(elem.firstChild, callback, (lvl + 1));
		} else if (elem.nextSibling) {
			dom.walk(elem.nextSibling, callback, lvl);
		} else if (elem.parentNode && elem.parentNode.nextSibling) {
			dom.walk(elem.parentNode.nextSibling, callback, (lvl - 1));
		}
	};
	dom.revWalk = function (elem, callback) {
		if (!elem) {
			return;
		}
		var retVal = callback.call(this, elem);
		if (retVal === false) {
			return;
		}
		if (elem.childNodes && elem.childNodes.length > 0) {
			dom.walk(elem.lastChild, callback);
		} else if (elem.previousSibling) {
			dom.walk(elem.previousSibling, callback);
		} else if (elem.parentNode && elem.parentNode.previousSibling) {
			dom.walk(elem.parentNode.previousSibling, callback);
		}
	};
	dom.setStyle = function (element, property, value) {
		if (element) {
			$(element).css(property, value);
		}
	};
	dom.getStyle = function (element, property) {
		return $(element).css(property);
	};
	dom.hasClass = function (element, className) {
		return $(element).hasClass(className);
	};
	dom.addClass = function (element, classNames) {
		$(element).addClass(classNames);
	};
	dom.removeClass = function (element, classNames) {
		$(element).removeClass(classNames);
	};
	dom.preventDefault = function (e) {
		e.preventDefault();
		dom.stopPropagation(e);
	};
	dom.stopPropagation = function (e) {
		e.stopPropagation();
	};

	dom.isBlank = function (value) {
		return (!value || wsrgx.test(value));
	};
	dom.isFn = function (f) {
		return (typeof f === 'function') ;
	};
	dom.isObj = function (v) {
		return (v !== null && typeof v === 'object');
	};
	dom.isset = function (v) {
		return (typeof v !== 'undefined' && v !== null);
	};
	dom.isArray = function (v) {
		return $.isArray(v);
	};

	dom.isNumeric = function (str) {
		return str.match(numrgx) !== null;
	};

	dom.getUniqueId = function () {
		var timestamp = (new Date()).getTime();
		var random = Math.ceil(Math.random() * 1000000);
		var id = timestamp + '' + random;
		return id.substr(5, 18).replace(/,/, '');
	};
	dom.inArray = function (needle, haystack) {
		var hln = haystack.length;
		for (var i = 0; i < hln; i++) {
			if (needle === haystack[i]) {
				return true;
			}
		}
		return false;
	};
	dom.arrayDiff = function (array1, array2, firstOnly) {
		var al = array1.length,
			i, res = [];
		for (i = 0; i < al; i++) {
			if (dom.inArray(array1[i], array2) === false) {
				res.push(array1[i]);
			}
		}
		if (firstOnly !== true) {
			al = array2.length;
			for (i = 0; i < al; i++) {
				if (dom.inArray(array2[i], array1) === false) {
					res.push(array2[i]);
				}
			}
		}
		return res;
	};
	dom.arrayMerge = function (array1, array2) {
		var c = array2.length, i;
		for (i = 0; i < c; i++) {
			array1.push(array2[i]);
		}
		return array1;
	};
	/**
	 * Removes allowedTags from the given content html string. If allowedTags is a string, then it
	 * is expected to be a selector; otherwise, it is expected to be array of string tag names.
	 */
	dom.stripTags = function (content, allowedTags) {
		if (typeof allowedTags === "string") {
			var c = $('<div>' + content + '</div>');
			c.find('*').not(allowedTags).remove();
			return c.html();
		} else {
			var match;
			var re = new RegExp(/<\/?(\w+)((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
			var resCont = content;
			while ((match = re.exec(content)) != null) {
				if (dom.isset(allowedTags) === false || dom.inArray(match[1], allowedTags) !== true) {
					resCont = resCont.replace(match[0], '');
				}
			}
			return resCont;
		}
	};
	
	dom.browser = function () {
		if (_browser) {
			return $.extend({}, _browser);
		}
		
		_browser = (function() {
			function uaMatch( ua ) {
				ua = ua.toLowerCase();
	
				var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
					/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
					/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
					/(msie) ([\w.]+)/.exec( ua ) ||
					ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
					[];
	
				return {
					browser: match[ 1 ] || "",
					version: match[ 2 ] || "0"
				};
			}
	
			var ua = navigator.userAgent.toLowerCase(),
				matched = uaMatch(ua),
				browser = {
					type: "unknown",
					version : 0,
					msie: false
				};
	
			if ( matched.browser ) {
				browser[ matched.browser ] = true;
				browser.version = matched.version || 0;
				browser.type = matched.browser;
			}
	
			// Chrome is Webkit, but Webkit is also Safari.
			if ( browser.chrome ) {
				browser.webkit = true;
			} else if ( browser.webkit ) {
				browser.safari = true;
			}
			if (browser.webkit) {
				browser.type = "webkit";
			}
			browser.firefox = (/firefox/.test(ua) == true);
			if (! browser.msie) {
				browser.msie = Boolean( /trident/.test(ua)); 
			}

			return browser;
		})();
		
		return $.extend({}, _browser);
	};

	dom.getBrowserType = function () {
		if (this._browserType === null) {
			var tests = ['msie', 'firefox', 'chrome', 'safari'];
			var tln = tests.length;
			for (var i = 0; i < tln; i++) {
				var r = new RegExp(tests[i], 'i');
				if (r.test(navigator.userAgent) === true) {
					this._browserType = tests[i];
					return this._browserType;
				}
			}

			this._browserType = 'other';
		}
		return this._browserType;
	};
	dom.getWebkitType = function(){
		if(dom.browser().type !== "webkit") {
			console.log("Not a webkit!");
			return false;
		}
		var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
		if (isSafari) {
			return "safari";
		}
		return "chrome";
	};

	dom.isBrowser = function (browser) {
		return (dom.browser().type === browser);
	};

	dom.getBlockParent = function (node, container) {
		if (dom.isBlockElement(node) === true) {
			return node;
		}
		if (node) {
			while (node.parentNode) {
				node = node.parentNode;

				if (dom.isBlockElement(node) === true) {
					return node;
				}
				if (node === container) {
					return null;
				}
			}
		}
		return null;
	};

	dom.findNodeParent = function (node, selector, container) {
		if (node) {
			while (node.parentNode) {
				if (node === container) {
					return null;
				}

				if ($(node).is(selector) === true) {
					return node;
				}
				node = node.parentNode;
			}
		}
		return null;
	};

	dom.onBlockBoundary = function (leftContainer, rightContainer, blockEls) {
		if (!leftContainer || !rightContainer) {
			return false;
		}
		var sBlocks = blockEls.join(', '),
			bleft = dom.isChildOfTagNames(leftContainer, blockEls) || $(leftContainer).is(sBlocks) && leftContainer || null,
			bright = dom.isChildOfTagNames(rightContainer, blockEls) || $(rightContainer).is(sBlocks) && rightContainer || null;
		return (bleft !== bright);
	};

	dom.isOnBlockBoundary = function (leftContainer, rightContainer, container) {
		if (!leftContainer || !rightContainer) {
			return false;
		}
		var bleft = dom.getBlockParent(leftContainer, container) || dom.isBlockElement(leftContainer, container) && leftContainer || null,
			bright = dom.getBlockParent(rightContainer, container) || dom.isBlockElement(rightContainer, container) && rightContainer || null;
		return (bleft !== bright);
	};

	dom.mergeContainers = function (node, mergeToNode) {
		if (!node || !mergeToNode) {
			return false;
		}

		if (node.nodeType === dom.TEXT_NODE || dom.isStubElement(node)) {
			// Move only this node.
			mergeToNode.appendChild(node);
		} else if (node.nodeType === dom.ELEMENT_NODE) {
			// Move all the child nodes to the new parent.
			while (node.firstChild) {
				mergeToNode.appendChild(node.firstChild);
			}

			$(node).remove();
		}
		return true;
	};

	dom.mergeBlockWithSibling = function (range, block, next) {
		var siblingBlock = next ? $(block).next().get(0) : $(block).prev().get(0); // block['nextSibling'] : block['previousSibling'];
		if (next) dom.mergeContainers(siblingBlock, block);
		else dom.mergeContainers(block, siblingBlock);
		range.collapse(true);
		return true;
	};

	dom.date = function (format, timestamp, tsIso8601) {
		if (timestamp === null && tsIso8601) {
			timestamp = dom.tsIso8601ToTimestamp(tsIso8601);
			if (!timestamp) {
				return;
			}
		}
		var date = new Date(timestamp);
		var formats = format.split('');
		var fc = formats.length;
		var dateStr = '';
		for (var i = 0; i < fc; i++) {
			var r = '';
			var f = formats[i];
			switch (f) {
				case 'D':
				case 'l':
					var names = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
					r = names[date.getDay()];
					if (f === 'D') {
						r = r.substring(0, 3);
					}
					break;
				case 'F':
				case 'm':
					r = date.getMonth() + 1;
					if (r < 10) r = '0' + r;
					break;
				case 'M':
					months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
					r = months[date.getMonth()];
					if (f === 'M') {
						r = r.substring(0, 3);
					}
					break;
				case 'd':
					r = date.getDate();
					break;
				case 'S':
					r = dom.getOrdinalSuffix(date.getDate());
					break;
				case 'Y':
					r = date.getFullYear();
					break;
				case 'y':
					r = date.getFullYear();
					r = r.toString().substring(2);
					break;
				case 'H':
					r = date.getHours();
					break;
				case 'h':
					r = date.getHours();
					if (r === 0) {
						r = 12;
					} else if (r > 12) {
						r -= 12;
					}
					break;
				case 'i':
					r = dom.addNumberPadding(date.getMinutes());
					break;
				case 'a':
					r = 'am';
					if (date.getHours() >= 12) {
						r = 'pm';
					}
					break;
				default:
					r = f;
					break;
			}
			dateStr += r;
		}
		return dateStr;
	};
	dom.getOrdinalSuffix = function (number) {
		var suffix = '';
		var tmp = (number % 100);
		if (tmp >= 4 && tmp <= 20) {
			suffix = 'th';
		} else {
			switch (number % 10) {
				case 1:
					suffix = 'st';
					break;
				case 2:
					suffix = 'nd';
					break;
				case 3:
					suffix = 'rd';
					break;
				default:
					suffix = 'th';
					break;
			}
		}
		return suffix;
	};
	dom.addNumberPadding = function (number) {
		if (number < 10) {
			number = '0' + number;
		}
		return number;
	};
	dom.tsIso8601ToTimestamp = function (tsIso8601) {
		var regexp = /(\d\d\d\d)(?:-?(\d\d)(?:-?(\d\d)(?:[T ](\d\d)(?::?(\d\d)(?::?(\d\d)(?:\.(\d+))?)?)?(?:Z|(?:([-+])(\d\d)(?::?(\d\d))?)?)?)?)?)?/;
		var d = tsIso8601.match(new RegExp(regexp));
		if (d) {
			var date = new Date();
			date.setDate(d[3]);
			date.setFullYear(d[1]);
			date.setMonth(d[2] - 1);
			date.setHours(d[4]);
			date.setMinutes(d[5]);
			date.setSeconds(d[6]);
			var offset = (d[9] * 60);
			if (d[8] === '+') {
				offset *= -1;
			}
			offset -= date.getTimezoneOffset();
			return (date.getTime() + (offset * 60 * 1000));
		}
		return null;
	};
	
	dom.normalizeNode = function(node, ignoreNative) {
		if (! node) {
			return;
		}
		if (! dom.browser().msie && (ignoreNative !== true && "function" == typeof node.normalize)) {
			return node.normalize();
		}
		return _myNormalizeNode(node);
	};

	function _myNormalizeNode(node) {
		if (! node) {
			return;
		}
		var ELEMENT_NODE = 1;
		var TEXT_NODE = 3;
		var child = node.firstChild;
		while (child) {
			if (child.nodeType == ELEMENT_NODE) {
				_myNormalizeNode(child);
			}
			else if (child.nodeType == TEXT_NODE) { 
				var next;
				while ((next = child.nextSibling) && next.nodeType == TEXT_NODE) { 
					var value = next.nodeValue;
					if (value != null && value.length) {
						child.nodeValue = child.nodeValue + value;
					}
					node.removeChild(next);
				}
			}
			child = child.nextSibling;
		}
	};	


	exports.dom = dom;

}(this.ice || window.ice, window.jQuery));
(function (global) {

	"use strict";

	var exports = global,
		rangy = global.rangy,
		Selection;

	Selection = function (env) {
		this._selection = null;
		this.env = env;
	
		this._initializeRangeLibrary();
		this._getSelection();
	};

	Selection.prototype = {

	/**
	 * Returns the selection object for the current browser.
	 */
	_getSelection: function () {
		if (this._selection) {
			this._selection.refresh();
		}
		else if (this.env.frame) {
			this._selection = rangy.getSelection(this.env.frame);
		}
		else {
			this._selection = rangy.getSelection();
		}
		return this._selection;
	},

	/**
	 * Creates a range object.
	 */
	createRange: function () {
		return rangy.createRange(this.env.document);
	},

	/**
	 * Returns the range object at the specified position. The current range object
	 * is at position 0. Note - currently only setting single range in `addRange` so
	 * position 0 will be the only allocation filled.
	 */
	getRangeAt: function (pos) {
		try {
			this._selection.refresh();
			return this._selection.getRangeAt(pos);
		} 
		catch (e) {
			this._selection = null;
			try {
				return this._getSelection().getRangeAt(0);
			}
			catch(ignore) {
				// ignore
			}
		}

		return null;
	},

	/**
	 * Adds the specified range to the current selection. Note - only supporting setting
	 * a single range, so the previous range gets evicted.
	 */
	addRange: function (range) {
		this._selection || (this._selection = this._getSelection());
		this._selection.setSingleRange(range);
		this._selection.ranges = [range];
	},

	/**
	 * Initialize and extend the `rangy` library with some custom functionality.
	 */
	_initializeRangeLibrary: function () {
		var self = this;

		rangy.init();
		rangy.config.checkSelectionRanges = false;

		var move = function (range, unitType, units, isStart) {
			if (units === 0) {
			  return;
			}
			var collapsed = range.collapsed;
	
			switch (unitType) {
				case ice.dom.CHARACTER_UNIT:
					if (units > 0) {
						range.moveCharRight(isStart, units);
					} else {
						range.moveCharLeft(isStart, units * -1);
					}
					break;
	
				case ice.dom.WORD_UNIT:
				default:
				// Removed. TODO: possibly refactor or re-implement.
					break;
			} 
			// restore collapsed flag
			if (collapsed) {
				range.collapse(isStart);
			}
		};

		/**
		 * Moves the start of the range using the specified `unitType`, by the specified
		 * number of `units`. Defaults to `CHARACTER_UNIT` and units of 1.
		 */
		rangy.rangePrototype.moveStart = function (unitType, units) {
			move(this, unitType, units, true);
		};

		/**
		 * Moves the end of the range using the specified `unitType`, by the specified
		 * number of `units`.
		 */
		rangy.rangePrototype.moveEnd = function (unitType, units) {
			move(this, unitType, units, false);
		};

		/**
		 * Depending on the given `start` boolean, sets the start or end containers
		 * to the given `container` with `offset` units.
		 */
		rangy.rangePrototype.setRange = function (start, container, offset) {
			if (start) {
				this.setStart(container, offset);
			} 
			else {
				this.setEnd(container, offset);
			}
		};

		/**
		 * Depending on the given `moveStart` boolean, moves the start or end containers
		 * to the left by the given number of character `units`. Use the following
		 * example as a demonstration for where the range will fall as it moves in and
		 * out of tag boundaries (where "|" is the marked range):
		 *
		 * test <em>it</em> o|ut
		 * test <em>it</em> |out
		 * test <em>it</em>| out
		 * test <em>i|t</em> out
		 * test <em>|it</em> out
		 * test| <em>it</em> out
		 * tes|t <em>it</em> out
		 * 
		 * A range could be mapped in one of two ways:
		 * 
		 * (1) If a startContainer is a Node of type Text, Comment, or CDATASection, then startOffset
		 * is the number of characters from the start of startNode. For example, the following
		 * are the range properties for `<p>te|st</p>` (where "|" is the collapsed range):
		 * 
		 * startContainer: <TEXT>test<TEXT>
		 * startOffset: 2
		 * endContainer: <TEXT>test<TEXT>
		 * endOffset: 2
		 * 
		 * (2) For other Node types, startOffset is the number of child nodes between the start of
		 * the startNode. Take the following html fragment:
		 * 
		 * `<p>some <span>test</span> text</p>`
		 * 
		 * If we were working with the following range properties:
		 * 
		 * startContainer: <p>
		 * startOffset: 2
		 * endContainer: <p>
		 * endOffset: 2
		 * 
		 * Since <p> is an Element node, the offsets are based on the offset in child nodes of <p> and
		 * the range is selecting the second child - the <span> tag.
		 * 
		 * <p><TEXT>some </TEXT><SPAN>test</SPAN><TEXT> text</TEXT></p>
		 */
		rangy.rangePrototype.moveCharLeft = function (moveStart, units) {
			var container, offset;
	
			if (moveStart) {
				container = this.startContainer;
				offset = this.startOffset;
			} 
			else {
				container = this.endContainer;
				offset = this.endOffset;
			}
	
			// Handle the case where the range conforms to (2) (noted in the comment above).
			if (container.nodeType === ice.dom.ELEMENT_NODE) {
				if (container.hasChildNodes() && offset > 0) {
					var lastChild = container.childNodes[offset - 1],
						nextContainer = this.getLastSelectableChild(lastChild);
					if (nextContainer) {
						container = nextContainer;
					}
					else {
						container = this.getPreviousTextNode(lastChild); 
					}
					if (! container) {
						return;
					}
					offset = container.data.length - units;
				}
				else {// no child nodes
					offset = units * -1;
				}
			}
			else {
				offset -= units;
			}
	
			if (offset < 0) {
				// We need to move to a previous selectable container.
				while (offset < 0) {
					container = this.getPreviousTextNode(container);
		
					// We are at the beginning/out of the editable - break.
					if (!container) {
						return;
					}
		
					if (container.nodeType === ice.dom.ELEMENT_NODE) {
						continue;
					}
		
					offset += container.data.length;
				}
			}
	
			this.setRange(moveStart, container, offset);
		};

		/**
		 * Depending on the given `moveStart` boolean, moves the start or end containers
		 * to the right by the given number of character `units`. Use the following
		 * example as a demonstration for where the range will fall as it moves in and
		 * out of tag boundaries (where "|" is the marked range):
		 *
		 * tes|t <em>it</em> out
		 * test| <em>it</em> out
		 * test |<em>it</em> out
		 * test <em>i|t</em> out
		 * test <em>it|</em> out
		 * test <em>it</em> |out
		 * 
		 * A range could be mapped in one of two ways:
		 * 
		 * (1) If a startContainer is a Node of type Text, Comment, or CDATASection, then startOffset
		 * is the number of characters from the start of startNode. For example, the following
		 * are the range properties for `<p>te|st</p>` (where "|" is the collapsed range):
		 * 
		 * startContainer: <TEXT>test<TEXT>
		 * startOffset: 2
		 * endContainer: <TEXT>test<TEXT>
		 * endOffset: 2
		 * 
		 * (2) For other Node types, startOffset is the number of child nodes between the start of
		 * the startNode. Take the following html fragment:
		 * 
		 * `<p>some <span>test</span> text</p>`
		 * 
		 * If we were working with the following range properties:
		 * 
		 * startContainer: <p>
		 * startOffset: 2
		 * endContainer: <p>
		 * endOffset: 2
		 * 
		 * Since <p> is an Element node, the offsets are based on the offset in child nodes of <p> and
		 * the range is selecting the second child - the <span> tag.
		 * 
		 * <p><TEXT>some </TEXT><SPAN>test</SPAN><TEXT> text</TEXT></p>
		 * Fixed by dfl to handle cases when there's no next container
		 */
		rangy.rangePrototype.moveCharRight = function (moveStart, units) {
			var container, offset;
	
			if (moveStart) {
				container = this.startContainer;
				offset = this.startOffset;
			} 
			else {
				container = this.endContainer;
				offset = this.endOffset;
			}
	
			if (container.nodeType === ice.dom.ELEMENT_NODE) {
				var next = this.getNextTextNode(container.childNodes[Math.min(offset, container.childNodes.length -1)]);
				if (next) {
					container = next;
				}
				else {
					container = this.getNextTextNode(container);
				}
	
				offset = units;
			} 
			else { // text node
				offset += units;
			}
			if (! container) {
				return;
			}
	
			var diff = (offset - container.data.length);
			if (diff > 0) {
				// We need to move to the next selectable container.
				while (diff > 0) {
					container = this.getNextContainer(container);
					if (! container) {
						return;
					}
		
					if (container.nodeType === ice.dom.ELEMENT_NODE) {
						continue;
					}
		
					if (container.data.length >= diff) {
						// We found a container with enough content to select.
						break;
					} 
					else if (container.data.length > 0) {
						// Container does not have enough content,
						// find the next one.
						diff -= container.data.length;
					}
				}
	
				offset = diff;
			}
			this.setRange(moveStart, container, offset);
		};

		/**
		 * Returns the deepest next container that the range can be extended to.
		 * For example, if the next container is an element that contains text nodes,
		 * the the container's firstChild is returned.
		 */
		rangy.rangePrototype.getNextContainer = function (container, skippedBlockEl) {
			if (!container) {
				return null;
			}
			skippedBlockEl = skippedBlockEl || [];
	
			while (container.nextSibling) {
				container = container.nextSibling;
				if (container.nodeType !== ice.dom.TEXT_NODE) {
					var child = this.getFirstSelectableChild(container);
					if (child !== null) {
						return child;
					}
				} 
				else if (this.isSelectable(container) === true) {
					return container;
				}
			}
	
			// Look at parents next sibling.
			while (container && !container.nextSibling) {
				container = container.parentNode;
			}
	
			if (!container) {
				return null;
			}
	
			container = container.nextSibling;
			if (this.isSelectable(container) === true) {
				return container;
			} 
			else if (ice.dom.isBlockElement(container) === true) {
				skippedBlockEl.push(container);
			}
	
			var selChild = this.getFirstSelectableChild(container);
			if (selChild !== null) {
				return selChild;
			}
	
			return this.getNextContainer(container, skippedBlockEl);
		};

		/**
		 * Returns the deepest previous container that the range can be extended to.
		 * For example, if the previous container is an element that contains text nodes,
		 * then the container's lastChild is returned.
		 */
		rangy.rangePrototype.getPreviousContainer = function (container, skippedBlockEl) {
			if (!container) {
				return null;
			}
			skippedBlockEl = skippedBlockEl || [];
	
			while (container.previousSibling) {
				container = container.previousSibling;
				if (container.nodeType !== ice.dom.TEXT_NODE) {
					if (ice.dom.isStubElement(container) === true) {
						return container;
					} 
					else {
						var child = this.getLastSelectableChild(container);
						if (child !== null) {
							return child;
						}
					}
				} 
				else if (this.isSelectable(container) === true) {
					return container;
				}
			}
	
			// Look at parents next sibling.
			while (container && !container.previousSibling) {
				container = container.parentNode;
			}
	
			if (!container) {
				return null;
			}
	
			container = container.previousSibling;
			if (this.isSelectable(container) === true) {
				return container;
			} 
			else if (ice.dom.isBlockElement(container) === true) {
				skippedBlockEl.push(container);
			}
	
			var selChild = this.getLastSelectableChild(container);
			if (selChild !== null) {
				return selChild;
			}
			return this.getPreviousContainer(container, skippedBlockEl);
		};
	
		rangy.rangePrototype.getNextTextNode = function (container) {
			if (container.nodeType === ice.dom.ELEMENT_NODE) {
				if (container.firstChild) {
					var ret = this.getFirstSelectableChild(container);
					if (ret) {
						return ret;
					}
				}
			}
	
			container = this.getNextContainer(container);
			if (! container) {
				return null;
			}
			if (container.nodeType === ice.dom.TEXT_NODE) {
				return container;
			}
	
			return this.getNextTextNode(container);
		};
	
		rangy.rangePrototype.getPreviousTextNode = function (container, skippedBlockEl) {
			container = this.getPreviousContainer(container, skippedBlockEl);
			if (! container) {
				return null;
			}
			if (container.nodeType === ice.dom.TEXT_NODE) {
				return container;
			}
	
			return this.getPreviousTextNode(container, skippedBlockEl);
		};
	
		rangy.rangePrototype.getFirstSelectableChild = function (element) {
			if (!element) {
				return null;
			}
			if (element.nodeType === ice.dom.TEXT_NODE) {
				return element;
			}
			var child = element.firstChild;
			while (child) {
				if (this.isSelectable(child) === true) {
					return child;
				} 
				else if (child.firstChild) {
				// This node does have child nodes.
					var res = this.getFirstSelectableChild(child);
					if (res !== null) {
						return res;
					} else {
						child = child.nextSibling;
					}
				} 
				else {
					child = child.nextSibling;
				}
			}
			return null;
		};

		rangy.rangePrototype.getLastSelectableChild = function (element) {
			if (! element) {
				return null;
			}
			if (element.nodeType == ice.dom.TEXT_NODE) {
				return element;
			}
			var child = element.lastChild;
			while (child) {
				if (this.isSelectable(child) === true) {
					return child;
				} 
				else if (child.lastChild) {
					// This node does have child nodes.
					var res = this.getLastSelectableChild(child);
					if (res !== null) {
						return res;
					}
					else {
						child = child.previousSibling;
					}
				} 
				else {
					child = child.previousSibling;
				}
			}
			return null;
		};

		rangy.rangePrototype.isSelectable = function (container) {
			return Boolean(container && container.nodeType === ice.dom.TEXT_NODE && container.data.length !== 0);
		};

		rangy.rangePrototype.getHTMLContents = function (clonedSelection) {
			if (!clonedSelection) {
				clonedSelection = this.cloneContents();
			}
			var div = self.env.document.createElement('div');
				div.appendChild(clonedSelection.cloneNode(true));
			return div.innerHTML;
			};
	
			rangy.rangePrototype.getHTMLContentsObj = function () {
				return this.cloneContents();
			};
		}
	};

	exports.Selection = Selection;

}(this.ice || window.ice));
(function(ice, $) {

	var exports = ice,
		Bookmark;

	Bookmark = function(env, range, keepOldBookmarks) {

		this.env = env;
		this.element = env.element;
		this.selection = this.env.selection;

		// Remove all bookmarks?
		if (!keepOldBookmarks) {
			this.removeBookmarks(this.element);
		}

		var currRange = range || this.selection.getRangeAt(0),
			range = currRange.cloneRange(),
			startContainer = range.startContainer,
			startOffset = range.startOffset,
			tmp;

		// Collapse to the end of range.
		range.collapse(false);

		var endBookmark = this.env.document.createElement('span');
		endBookmark.style.display = 'none';
		$(endBookmark).html('&nbsp;')
			.addClass('iceBookmark iceBookmark_end')
			.attr('iceBookmark', 'end');
		range.insertNode(endBookmark);
		if (!ice.dom.isChildOf(endBookmark, this.element)) {
			this.element.appendChild(endBookmark);
		}

		// Move the range to where it was before.
		range.setStart(startContainer, startOffset);
		range.collapse(true);

		// Create the start bookmark.
		var startBookmark = this.env.document.createElement('span');
		startBookmark.style.display = 'none';
		$(startBookmark).addClass('iceBookmark iceBookmark_start')
			.html('&nbsp;')
			.attr('iceBookmark', 'start');
		try {
			range.insertNode(startBookmark);

			// Make sure start and end are in correct position.
			if (startBookmark.previousSibling === endBookmark) {
				// Reverse..
				tmp = startBookmark;
				startBookmark = endBookmark;
				endBookmark = tmp;
			}
		} catch (e) {
			// NS_ERROR_UNEXPECTED: I believe this is a Firefox bug.
			// It seems like if the range is collapsed and the text node is empty
			// (i.e. length = 0) then Firefox tries to split the node for no reason and fails...
			ice.dom.insertBefore(endBookmark, startBookmark);
		}

		if (ice.dom.isChildOf(startBookmark, this.element) === false) {
			if (this.element.firstChild) {
				ice.dom.insertBefore(this.element.firstChild, startBookmark);
			} else {
				// Should not happen...
				this.element.appendChild(startBookmark);
			}
		}

		if (!endBookmark.previousSibling) {
			tmp = this.env.document.createTextNode('');
			ice.dom.insertBefore(endBookmark, tmp);
		}

		// The original range object must be changed.
		if (!startBookmark.nextSibling) {
			tmp = this.env.document.createTextNode('');
			ice.dom.insertAfter(startBookmark, tmp);
		}

		currRange.setStart(startBookmark.nextSibling, 0);
		currRange.setEnd(endBookmark.previousSibling, (endBookmark.previousSibling.length || 0));

		this.start = startBookmark;
		this.end = endBookmark;
	};

	Bookmark.prototype = {

		selectStartAndCollapse: function() {
			if (this.start) {
				var range = this.selection.getRangeAt(0);
				range.setStartBefore(this.start);
				range.collapse(true);
				$([this.start, this.end]).remove()
				try {
					this.selection.addRange(range);
				} 
				catch (e) {
					// IE may throw exception for hidden elements..
				}
			}
		},
		
		remove: function() {
			if (this.start) { 
				$([this.start, this.end]).remove();
				this.start = this.end = null;
			}
		},
		
		selectBookmark: function() {
			var range = this.selection.getRangeAt(0),
				startPos = null,
				endPos = null,
				startOffset = 0,
				endOffset = null,
				parent = this.start && this.start.parentNode;

			if (this.start.nextSibling === this.end || ice.dom.getElementsBetween(this.start, this.end).length === 0) {
				// Bookmark is collapsed.
				if (this.end.nextSibling) {
					startPos = ice.dom.getFirstChild(this.end.nextSibling);
				} else if (this.start.previousSibling) {
					startPos = ice.dom.getFirstChild(this.start.previousSibling);
					if (startPos.nodeType === ice.dom.TEXT_NODE) {
						startOffset = startPos.length;
					}
				} else {
					// Create a text node in parent.
					this.end.parentNode.appendChild(this.env.document.createTextNode(''));
					startPos = ice.dom.getFirstChild(this.end.nextSibling);
				}
			} else {
				if (this.start.nextSibling) {
					startPos = ice.dom.getFirstChild(this.start.nextSibling);
				} else {
					if (!this.start.previousSibling) {
						var tmp = this.env.document.createTextNode('');
						ice.dom.insertBefore(this.start, tmp);
					}

					startPos = ice.dom.getLastChild(this.start.previousSibling);
					startOffset = startPos.length;
				}

				if (this.end.previousSibling) {
					endPos = ice.dom.getLastChild(this.end.previousSibling);
				} else {
					endPos = ice.dom.getFirstChild(this.end.nextSibling || this.end);
					endOffset = 0;
				}
			}

			$([this.start, this.end]).remove();
			try {
				ice.dom.normalize(parent);
			} 
			catch (e) {}

			if (endPos === null) {
				if (range) {
					range.setEnd(startPos, startOffset);
					range.collapse(false);
				}
			} 
			else {
				range.setStart(startPos, startOffset);
				if (endOffset === null) {
					endOffset = (endPos.length || 0);
				}
				range.setEnd(endPos, endOffset);
			}

			try {
				this.selection.addRange(range);
			} 
			catch (e) {
				// IE may throw exception for hidden elements..
			}
		},

		getBookmark: function(parent, type) {
			var elem = ice.dom.getClass('iceBookmark_' + type, parent)[0];
			return elem;
		},

		removeBookmarks: function(elem) {
			$(elem).find('span.iceBookmark').remove();
		}
	};

	exports.Bookmark = Bookmark;

}(this.ice || window.ice, window.jQuery));/**
Copyright 2015 LoopIndex, This file is part of the Track Changes plugin for CKEditor.

The track changes plugin is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License, version 2, as published by the Free Software Foundation.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU Lesser General Public License along with this program as the file lgpl.txt. If not, see http://www.gnu.org/licenses/lgpl.html.

Written by (David *)Frenkiel - https://github.com/imdfl

** Source Version **
**/
var LITE = {
	Events : {
		INIT : "lite:init",
		ACCEPT : "lite:accept",
		REJECT : "lite:reject",
		SHOW_HIDE : "lite:showHide",
		TRACKING : "lite:tracking",
		CHANGE: "lite:change",
		HOVER_IN: "lite:hover-in",
		HOVER_OUT: "lite:hover-out"
	},
	
	Commands : {
		TOGGLE_TRACKING : "lite-toggletracking",
		TOGGLE_SHOW : "lite-toggleshow",
		ACCEPT_ALL : "lite-acceptall",
		REJECT_ALL : "lite-rejectall",
		ACCEPT_ONE : "lite-acceptone",
		REJECT_ONE : "lite-rejectone",
		TOGGLE_TOOLTIPS: "lite-toggletooltips"
	}
};/*
#
# Opentip v2.4.6
#
# More info at [www.opentip.org](http://www.opentip.org)
# 
# Copyright (c) 2012, Matias Meno  
# Graphics by Tjandra Mayerhold
# 
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
# 
# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.
# 
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
# THE SOFTWARE.
#
*/
(function (scope) {
	"use strict";
	var Opentip, firstAdapter, i, mouseMoved, mousePosition, mousePositionObservers, position, vendors, _i, _len, _ref,
		__slice = [].slice,
		__indexOf = [].indexOf || function (item) {
			for (var i = 0, l = this.length; i < l; i++) {
				if (i in this && this[i] === item) return i;
			}
			return -1;
		},
		__hasProp = {}.hasOwnProperty;

	Opentip = (function () {

		Opentip.prototype.STICKS_OUT_TOP = 1;

		Opentip.prototype.STICKS_OUT_BOTTOM = 2;

		Opentip.prototype.STICKS_OUT_LEFT = 1;

		Opentip.prototype.STICKS_OUT_RIGHT = 2;

		Opentip.prototype["class"] = {
			container: "opentip-container",
			opentip: "opentip",
			header: "ot-header",
			content: "ot-content",
			loadingIndicator: "ot-loading-indicator",
			close: "ot-close",
			goingToHide: "ot-going-to-hide",
			hidden: "ot-hidden",
			hiding: "ot-hiding",
			goingToShow: "ot-going-to-show",
			showing: "ot-showing",
			visible: "ot-visible",
			loading: "ot-loading",
			ajaxError: "ot-ajax-error",
			fixed: "ot-fixed",
			showEffectPrefix: "ot-show-effect-",
			hideEffectPrefix: "ot-hide-effect-",
			stylePrefix: "style-"
		};

		function Opentip(element, content, title, options) {
			var elTips, hideTrigger, methodToBind, optionSources, prop, styleName, _i, _j, _len, _len1, _ref, _ref1, _ref2, _tmpStyle,
				_this = this;
			this.id = ++Opentip.lastId;

			/* begin dfl */
			this.adapter = Opentip.adapter;
			options = this.adapter.clone(options);
			if (typeof content === "object") {
				options = content;
				content = title = void 0;
			} else if (typeof title === "object") {
				options = title;
				title = void 0;
			}
			this._element = element;
			this._boundingElement = options.boundingElement;
			this._document = element.ownerDocument;
			this._window = this._document.defaultView;
			this._body = this._document.body;
			Opentip.addTip(this);
			elTips = Opentip.getTips(element);
			elTips.push(this);
			Opentip.setTips(element, elTips);
			this.triggerElement = this.adapter.wrap(element);
			if (this.triggerElement.length > 1) {
				throw new Error("You can't call Opentip on multiple elements.");
			}
			if (this.triggerElement.length < 1) {
				throw new Error("Invalid element.");
			}
			this.loaded = false;
			this.loading = false;
			this.visible = false;
			this.currentPosition = {
				left: 0,
				top: 0
			};
			this.dimensions = {
				width: 100,
				height: 50
			};
			this.content = "";
			this.redraw = true;
			this.currentObservers = {
				showing: false,
				visible: false,
				hiding: false,
				hidden: false
			};
			if (title != null) {
				options.title = title;
			}
			if (content != null) {
				this.setContent(content);
			}
			if (options["extends"] == null) {
				if (options.style != null) {
					options["extends"] = options.style;
				} else {
					options["extends"] = Opentip.defaultStyle;
				}
			}
			optionSources = [options];
			_tmpStyle = options;
			while (_tmpStyle["extends"]) {
				styleName = _tmpStyle["extends"];
				_tmpStyle = Opentip.styles[styleName];
				if (_tmpStyle == null) {
					throw new Error("Invalid style: " + styleName);
				}
				optionSources.unshift(_tmpStyle);
				if (!((_tmpStyle["extends"] != null) || styleName === "standard")) {
					_tmpStyle["extends"] = "standard";
				}
			}
			options = (_ref = this.adapter).extend.apply(_ref, [{}].concat(__slice.call(optionSources)));
			options.hideTriggers = (function () {
				var _i, _len, _ref1, _results;
				_ref1 = options.hideTriggers;
				_results = [];
				for (_i = 0, _len = _ref1.length; _i < _len; _i++) {
					hideTrigger = _ref1[_i];
					_results.push(hideTrigger);
				}
				return _results;
			})();
			if (options.hideTrigger && options.hideTriggers.length === 0) {
				options.hideTriggers.push(options.hideTrigger);
			}
			_ref1 = ["tipJoint", "targetJoint", "stem"];
			for (_i = 0, _len = _ref1.length; _i < _len; _i++) {
				prop = _ref1[_i];
				if (options[prop] && typeof options[prop] === "string") {
					options[prop] = new Opentip.Joint(options[prop]);
				}
			}
			if (options.ajax && (options.ajax === true || !options.ajax)) {
				if (this.adapter.tagName(this.triggerElement) === "A") {
					options.ajax = this.adapter.attr(this.triggerElement, "href");
				} else {
					options.ajax = false;
				}
			}
			if (options.showOn === "click" && this.adapter.tagName(this.triggerElement) === "A") {
				this.adapter.observe(this.triggerElement, "click", function (e) {
					e.preventDefault();
					e.stopPropagation();
					return e.stopped = true;
				});
			}
			if (options.target) {
				options.fixed = true;
			}
			if (options.stem === true) {
				options.stem = new Opentip.Joint(options.tipJoint);
			}
			if (options.target === true) {
				options.target = this.triggerElement;
			} else if (options.target) {
				options.target = this.adapter.wrap(options.target);
			}
			this.currentStem = options.stem;
			if (options.delay == null) {
				options.delay = options.showOn === "mouseover" ? 0.2 : 0;
			}
			if (options.targetJoint == null) {
				options.targetJoint = new Opentip.Joint(options.tipJoint).flip();
			}
			this.showTriggers = [];
			this.showTriggersWhenVisible = [];
			this.hideTriggers = [];
			if (options.showOn && options.showOn !== "creation") {
				this.showTriggers.push({
					element: this.triggerElement,
					event: options.showOn
				});
			}
			if (options.ajaxCache != null) {
				options.cache = options.ajaxCache;
				delete options.ajaxCache;
			}
			this.options = options;
			this.bound = {};
			_ref2 = ["prepareToShow", "prepareToHide", "show", "hide", "reposition"];
			for (_j = 0, _len1 = _ref2.length; _j < _len1; _j++) {
				methodToBind = _ref2[_j];
				this.bound[methodToBind] = (function (methodToBind) {
					return function () {
						return _this[methodToBind].apply(_this, arguments);
					};
				})(methodToBind);
			}
			this.adapter.domReady(function () {
				_this.activate();
				if (_this.options.showOn === "creation") {
					return _this.prepareToShow();
				}
			});
		}

		Opentip.prototype._setup = function () {
			var hideOn, hideTrigger, hideTriggerElement, i, _i, _j, _len, _len1, _ref, _ref1, _results;
			this.debug("Setting up the tooltip.");
			this._buildContainer(this.triggerElement);
			this.hideTriggers = [];
			_ref = this.options.hideTriggers;
			for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
				hideTrigger = _ref[i];
				hideTriggerElement = null;
				hideOn = this.options.hideOn instanceof Array ? this.options.hideOn[i] : this.options.hideOn;
				if (typeof hideTrigger === "string") {
					switch (hideTrigger) {
						case "trigger":
							hideOn = hideOn || "mouseout";
							hideTriggerElement = this.triggerElement;
							break;
						case "tip":
							hideOn = hideOn || "mouseover";
							hideTriggerElement = this.container;
							break;
						case "target":
							hideOn = hideOn || "mouseover";
							hideTriggerElement = this.options.target;
							break;
						case "closeButton":
							break;
						default:
							throw new Error("Unknown hide trigger: " + hideTrigger + ".");
					}
				} else {
					hideOn = hideOn || "mouseover";
					hideTriggerElement = this.adapter.wrap(hideTrigger);
				}
				if (hideTriggerElement) {
					this.hideTriggers.push({
						element: hideTriggerElement,
						event: hideOn,
						original: hideTrigger
					});
				}
			}
			_ref1 = this.hideTriggers;
			_results = [];
			for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
				hideTrigger = _ref1[_j];
				_results.push(this.showTriggersWhenVisible.push({
					element: hideTrigger.element,
					event: "mouseover"
				}));
			}
			return _results;
		};

		Opentip.prototype._buildContainer = function (element) {
			this.container = this.adapter.create("<div id=\"opentip-" + this.id + "\" class=\"" + this["class"].container + " " + this["class"].hidden + " " + this["class"].stylePrefix + this.options.className + "\"></div>", this._document);
			this.adapter.css(this.container, {
				position: "absolute"
			});
			if (this.options.ajax) {
				this.adapter.addClass(this.container, this["class"].loading);
			}
			if (this.options.fixed) {
				this.adapter.addClass(this.container, this["class"].fixed);
			}
			if (this.options.showEffect) {
				this.adapter.addClass(this.container, "" + this["class"].showEffectPrefix + this.options.showEffect);
			}
			if (this.options.hideEffect) {
				return this.adapter.addClass(this.container, "" + this["class"].hideEffectPrefix + this.options.hideEffect);
			}
		};

		Opentip.prototype._buildElements = function () {
			var headerElement, titleElement,
				doc = this._document;
			this.tooltipElement = this.adapter.create("<div class=\"" + this["class"].opentip + "\"><div class=\"" + this["class"].header + "\"></div><div class=\"" + this["class"].content + "\"></div></div>", doc);
			this.backgroundCanvas = this.adapter.wrap(doc.createElement("canvas"));
			this.adapter.css(this.backgroundCanvas, {
				position: "absolute"
			});
			if (typeof G_vmlCanvasManager !== "undefined" && G_vmlCanvasManager !== null) {
				G_vmlCanvasManager.initElement(this.adapter.unwrap(this.backgroundCanvas));
			}
			headerElement = this.adapter.find(this.tooltipElement, "." + this["class"].header);
			if (this.options.title) {
				titleElement = this.adapter.create("<h1></h1>", doc);
				this.adapter.update(titleElement, this.options.title, this.options.escapeTitle);
				this.adapter.append(headerElement, titleElement);
			}
			if (this.options.ajax && !this.loaded) {
				this.adapter.append(this.tooltipElement, this.adapter.create("<div class=\"" + this["class"].loadingIndicator + "\"><span>???</span></div>", doc));
			}
			if (__indexOf.call(this.options.hideTriggers, "closeButton") >= 0) {
				this.closeButtonElement = this.adapter.create("<a href=\"javascript:undefined;\" class=\"" + this["class"].close + "\"><span>Close</span></a>", doc);
				this.adapter.append(headerElement, this.closeButtonElement);
			}
			this.adapter.append(this.container, this.backgroundCanvas);
			this.adapter.append(this.container, this.tooltipElement);
			this.adapter.append(this._body, this.container);
			this._newContent = true;
			return this.redraw = true;
		};

		Opentip.prototype.setContent = function (content) {
			this.content = content;
			this._newContent = true;
			if (typeof this.content === "function") {
				this._contentFunction = this.content;
				this.content = "";
			} else {
				this._contentFunction = null;
			}
			if (this.visible) {
				return this._updateElementContent();
			}
		};

		Opentip.prototype._updateElementContent = function () {
			var contentDiv;
			if (this._newContent || (!this.options.cache && this._contentFunction)) {
				contentDiv = this.adapter.find(this.container, "." + this["class"].content);
				if (contentDiv != null) {
					if (this._contentFunction) {
						this.debug("Executing content function.");
						this.content = this._contentFunction(this);
					}
					this.adapter.update(contentDiv, this.content, this.options.escapeContent);
				}
				this._newContent = false;
			}
			this._storeAndLockDimensions();
			return this.reposition();
		};

		Opentip.prototype._storeAndLockDimensions = function () {
			var prevDimension;
			if (!this.container) {
				return;
			}
			prevDimension = this.dimensions;
			this.adapter.css(this.container, {
				width: "auto",
				left: "0px",
				top: "0px"
			});
			this.dimensions = this.adapter.dimensions(this.container);
			this.dimensions.width += 1;
			this.adapter.css(this.container, {
				width: "" + this.dimensions.width + "px",
				top: "" + this.currentPosition.top + "px",
				left: "" + this.currentPosition.left + "px"
			});
			if (!this._dimensionsEqual(this.dimensions, prevDimension)) {
				this.redraw = true;
				return this._draw();
			}
		};

		Opentip.prototype.activate = function () {
			return this._setupObservers("hidden", "hiding");
		};

		Opentip.prototype.deactivate = function (options) {
			if (options) {
				this.adapter.extend(this.options, options);
			}
			this.hide();
			Opentip.removeTip(this);
			return this._setupObservers("-showing", "-visible", "-hidden", "-hiding");
		};

		Opentip.prototype._setupObservers = function () {
			var observeOrStop, removeObserver, state, states, trigger, _i, _j, _k, _l, _len, _len1, _len2, _len3, _ref, _ref1, _ref2,
				_this = this;
			states = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
			for (_i = 0, _len = states.length; _i < _len; _i++) {
				state = states[_i];
				removeObserver = false;
				if (state.charAt(0) === "-") {
					removeObserver = true;
					state = state.substr(1);
				}
				if (this.currentObservers[state] === !removeObserver) {
					continue;
				}
				this.currentObservers[state] = !removeObserver;
				observeOrStop = function () {
					var args, _ref, _ref1;
					args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
					if (removeObserver) {
						return (_ref = _this.adapter).stopObserving.apply(_ref, args);
					} else {
						return (_ref1 = _this.adapter).observe.apply(_ref1, args);
					}
				};
				switch (state) {
					case "showing":
						_ref = this.hideTriggers;
						for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
							trigger = _ref[_j];
							observeOrStop(trigger.element, trigger.event, this.bound.prepareToHide);
						}
						observeOrStop((document.onresize != null ? document : window), "resize", this.bound.reposition);
						observeOrStop(window, "scroll", this.bound.reposition);
						break;
					case "visible":
						_ref1 = this.showTriggersWhenVisible;
						for (_k = 0, _len2 = _ref1.length; _k < _len2; _k++) {
							trigger = _ref1[_k];
							observeOrStop(trigger.element, trigger.event, this.bound.prepareToShow);
						}
						break;
					case "hiding":
						_ref2 = this.showTriggers;
						for (_l = 0, _len3 = _ref2.length; _l < _len3; _l++) {
							trigger = _ref2[_l];
							observeOrStop(trigger.element, trigger.event, this.bound.prepareToShow);
						}
						break;
					case "hidden":
						break;
					default:
						throw new Error("Unknown state: " + state);
				}
			}
			return null;
		};

		Opentip.prototype.prepareToShow = function () {
			this._abortHiding();
			this._abortShowing();
			if (this.visible) {
				return;
			}
			this.debug("Showing in " + this.options.delay + "s.");
			if (this.container == null) {
				this._setup();
			}
			if (this.options.group) {
				Opentip._abortShowingGroup(this.options.group, this);
			}
			this.preparingToShow = true;
			this._setupObservers("-hidden", "-hiding", "showing");
			this._followMousePosition();
			if (this.options.fixed && !this.options.target) {
				this.initialMousePosition = mousePosition;
			}
			this.reposition();
			return this._showTimeoutId = this.setTimeout(this.bound.show, this.options.delay || 0);
		};

		Opentip.prototype.show = function () {
			var _this = this;
			this._abortHiding();
			if (this.visible) {
				return;
			}
			this._clearTimeouts();
			if (!this._triggerElementExists()) {
				return this.deactivate();
			}
			this.debug("Showing now.");
			if (this.container == null) {
				this._setup();
			}
			if (this.options.group) {
				Opentip._hideGroup(this.options.group, this);
			}
			this.visible = true;
			this.preparingToShow = false;
			if (this.tooltipElement == null) {
				this._buildElements();
			}
			this._updateElementContent();
			if (this.options.ajax && (!this.loaded || !this.options.cache)) {
				this._loadAjax();
			}
			this._searchAndActivateCloseButtons();
			this._startEnsureTriggerElement();
			this.adapter.css(this.container, {
				zIndex: Opentip.lastZIndex++
			});
			this._setupObservers("-hidden", "-hiding", "-showing", "-visible", "showing", "visible");
			if (this.options.fixed && !this.options.target) {
				this.initialMousePosition = mousePosition;
			}
			this.reposition();
			this.adapter.removeClass(this.container, this["class"].hiding);
			this.adapter.removeClass(this.container, this["class"].hidden);
			this.adapter.addClass(this.container, this["class"].goingToShow);
			this.setCss3Style(this.container, {
				transitionDuration: "0s"
			});
			this.defer(function () {
				var delay;
				if (!_this.visible || _this.preparingToHide) {
					return;
				}
				_this.adapter.removeClass(_this.container, _this["class"].goingToShow);
				_this.adapter.addClass(_this.container, _this["class"].showing);
				delay = 0;
				if (_this.options.showEffect && _this.options.showEffectDuration) {
					delay = _this.options.showEffectDuration;
				}
				_this.setCss3Style(_this.container, {
					transitionDuration: "" + delay + "s"
				});
				_this._visibilityStateTimeoutId = _this.setTimeout(function () {
					_this.adapter.removeClass(_this.container, _this["class"].showing);
					return _this.adapter.addClass(_this.container, _this["class"].visible);
				}, delay);
				return _this._activateFirstInput();
			});
			return this._draw();
		};

		Opentip.prototype._abortShowing = function () {
			if (this.preparingToShow) {
				this.debug("Aborting showing.");
				this._clearTimeouts();
				this._stopFollowingMousePosition();
				this.preparingToShow = false;
				return this._setupObservers("-showing", "-visible", "hiding", "hidden");
			}
		};

		Opentip.prototype.prepareToHide = function () {
			this._abortShowing();
			this._abortHiding();
			if (!this.visible) {
				return;
			}
			this.preparingToHide = true;
			this._setupObservers("-showing", "visible", "-hidden", "hiding");
			return this._hideTimeoutId = this.setTimeout(this.bound.hide, this.options.hideDelay);
		};

		Opentip.prototype.hide = function () {
			var _this = this,
				cls;
			this._abortShowing();
			if (!this.visible) {
				return;
			}
			this._clearTimeouts();
			this.visible = false;
			this.preparingToHide = false;
			this._stopEnsureTriggerElement();
			this._setupObservers("-showing", "-visible", "-hiding", "-hidden", "hiding", "hidden");
			if (this._element) {
				var tips = this.adapter.data(this._element, "__opentips") || [];
				for (var i = tips.length; i--;) {
					if (tips[i] == this) {
						tips.slice(i, 1);
					}
				}
				Opentip.setTips(this._element, tips);
			}
			if (!this.options.fixed) {
				this._stopFollowingMousePosition();
			}
			if (!this.container) {
				return;
			}
			cls = this["class"];
			this.adapter.removeClass(this.container, cls.visible);
			this.adapter.removeClass(this.container, cls.showing);
			this.adapter.addClass(this.container, cls.goingToHide);
			this.setCss3Style(this.container, {
				transitionDuration: "0s"
			});
			return this.defer(function () {
				var hideDelay;
				_this.adapter.removeClass(_this.container, _this["class"].goingToHide);
				_this.adapter.addClass(_this.container, _this["class"].hiding);
				hideDelay = 0;
				if (_this.options.hideEffect && _this.options.hideEffectDuration) {
					hideDelay = _this.options.hideEffectDuration;
				}
				_this.setCss3Style(_this.container, {
					transitionDuration: "" + hideDelay + "s"
				});
				return _this._visibilityStateTimeoutId = _this.setTimeout(function () {
					_this.adapter.removeClass(_this.container, _this["class"].hiding);
					_this.adapter.addClass(_this.container, _this["class"].hidden);
					_this.setCss3Style(_this.container, {
						transitionDuration: "0s"
					});
					if (_this.options.removeElementsOnHide) {
						_this.adapter.remove(_this.container);
						delete _this.container;
						return delete _this.tooltipElement;
					}
				}, hideDelay);
			});
		};

		Opentip.prototype._abortHiding = function () {
			if (this.preparingToHide) {
				this.debug("Aborting hiding.");
				this._clearTimeouts();
				this.preparingToHide = false;
				return this._setupObservers("-hiding", "showing", "visible");
			}
		};

		Opentip.prototype.reposition = function () {
			var position, stem, _ref,
				_this = this;
			position = this.getPosition();
			if (position == null) {
				return;
			}
			stem = this.options.stem;
			if (this.options.containInViewport) {
				_ref = this._ensureViewportContainment(position), position = _ref.position, stem = _ref.stem;
			}
			if (this._positionsEqual(position, this.currentPosition)) {
				return;
			}
			if (!(!this.options.stem || stem.eql(this.currentStem))) {
				this.redraw = true;
			}
			this.currentPosition = position;
			this.currentStem = stem;
			this._draw();
			this.adapter.css(this.container, {
				left: "" + position.left + "px",
				top: "" + position.top + "px"
			});
			return this.defer(function () {
				var rawContainer, redrawFix;
				rawContainer = _this.adapter.unwrap(_this.container);
				rawContainer.style.visibility = "hidden";
				redrawFix = rawContainer.offsetHeight;
				return rawContainer.style.visibility = "visible";
			});
		};

		Opentip.prototype.getPosition = function (tipJoint, targetJoint, stem) {
			var additionalHorizontal, additionalVertical, offsetDistance, position, stemLength, targetDimensions, targetPosition, unwrappedTarget, _ref,
				body = this._body,
				win = this._window;
			if (!this.container) {
				return;
			}
			if (tipJoint == null) {
				tipJoint = this.options.tipJoint;
			}
			if (targetJoint == null) {
				targetJoint = this.options.targetJoint;
			}
			position = {};

			if (this.options.target) {
				targetPosition = this.adapter.offset(this.options.target);
				targetDimensions = this.adapter.dimensions(this.options.target);
				position = targetPosition;
				if (targetJoint.right) {
					unwrappedTarget = this.adapter.unwrap(this.options.target);
					if (unwrappedTarget.getBoundingClientRect != null) {
						position.left = unwrappedTarget.getBoundingClientRect().right + ((_ref = win.pageXOffset) != null ? _ref : body.scrollLeft);
					} else {
						position.left += targetDimensions.width;
					}
				} else if (targetJoint.center) {
					position.left += Math.round(targetDimensions.width / 2);
				}
				if (targetJoint.bottom) {
					position.top += targetDimensions.height;
				} else if (targetJoint.middle) {
					position.top += Math.round(targetDimensions.height / 2);
				}
				if (this.options.borderWidth) {
					if (this.options.tipJoint.left) {
						position.left += this.options.borderWidth;
					}
					if (this.options.tipJoint.right) {
						position.left -= this.options.borderWidth;
					}
					if (this.options.tipJoint.top) {
						position.top += this.options.borderWidth;
					} else if (this.options.tipJoint.bottom) {
						position.top -= this.options.borderWidth;
					}
				}
			} else {
				if (this.initialMousePosition) {
					position = {
						top: this.initialMousePosition.y,
						left: this.initialMousePosition.x
					};
				} else {
					position = {
						top: mousePosition.y,
						left: mousePosition.x
					};
				}
			}
			if (this.options.autoOffset) {
				stemLength = this.options.stem ? this.options.stemLength : 0;
				offsetDistance = stemLength && this.options.fixed ? 2 : 10;
				additionalHorizontal = tipJoint.middle && !this.options.fixed ? 15 : 0;
				additionalVertical = tipJoint.center && !this.options.fixed ? 15 : 0;
				if (tipJoint.right) {
					position.left -= offsetDistance + additionalHorizontal;
				} else if (tipJoint.left) {
					position.left += offsetDistance + additionalHorizontal;
				}
				if (tipJoint.bottom) {
					position.top -= offsetDistance + additionalVertical;
				} else if (tipJoint.top) {
					position.top += offsetDistance + additionalVertical;
				}
				if (stemLength) {
					if (stem == null) {
						stem = this.options.stem;
					}
					if (stem.right) {
						position.left -= stemLength;
					} else if (stem.left) {
						position.left += stemLength;
					}
					if (stem.bottom) {
						position.top -= stemLength;
					} else if (stem.top) {
						position.top += stemLength;
					}
				}
			}
			position.left += this.options.offset[0];
			position.top += this.options.offset[1];
			if (tipJoint.right) {
				position.left -= this.dimensions.width;
			} else if (tipJoint.center) {
				position.left -= Math.round(this.dimensions.width / 2);
			}
			if (tipJoint.bottom) {
				position.top -= this.dimensions.height;
			} else if (tipJoint.middle) {
				position.top -= Math.round(this.dimensions.height / 2);
			}
			return position;
		};

		Opentip.prototype._ensureViewportContainment = function (position) {
			var needsRepositioning, newSticksOut, originals, revertedX, revertedY, scrollOffset, stem, sticksOut, targetJoint, tipJoint, viewportDimensions, viewportPosition,
				doc = this._document;
			stem = this.options.stem;
			originals = {
				position: position,
				stem: stem
			};
			if (!(this.visible && position)) {
				return originals;
			}
			sticksOut = this._sticksOut(position);
			if (!(sticksOut[0] || sticksOut[1])) {
				return originals;
			}
			tipJoint = new Opentip.Joint(this.options.tipJoint);
			if (this.options.targetJoint) {
				targetJoint = new Opentip.Joint(this.options.targetJoint);
			}
			scrollOffset = this.adapter.scrollOffset(this._window, this._document);
			viewportDimensions = this._boundingElement ? this.adapter.dimensions(this._boundingElement) : this.adapter.viewportDimensions(doc);
			viewportPosition = [position.left - scrollOffset[0], position.top - scrollOffset[1]];
			needsRepositioning = false;
			if (viewportDimensions.width >= this.dimensions.width) {
				if (sticksOut[0]) {
					needsRepositioning = true;
					switch (sticksOut[0]) {
						case this.STICKS_OUT_LEFT:
							//dfl - was left
							tipJoint.setHorizontal("right");
							if (this.options.targetJoint) {
								targetJoint.setHorizontal("right");
							}
							break;
						case this.STICKS_OUT_RIGHT:
							//dfl - was right
							tipJoint.setHorizontal("left");
							if (this.options.targetJoint) {
								targetJoint.setHorizontal("left");
							}
					}
				}
			}
			if (viewportDimensions.height >= this.dimensions.height) {
				if (sticksOut[1]) {
					needsRepositioning = true;
					switch (sticksOut[1]) {
						case this.STICKS_OUT_TOP:
							tipJoint.setVertical("top");
							if (this.options.targetJoint) {
								targetJoint.setVertical("bottom");
							}
							break;
						case this.STICKS_OUT_BOTTOM:
							tipJoint.setVertical("bottom");
							if (this.options.targetJoint) {
								targetJoint.setVertical("top");
							}
					}
				}
			}
			if (!needsRepositioning) {
				return originals;
			}
			if (this.options.stem) {
				stem = tipJoint;
			}
			position = this.getPosition(tipJoint, targetJoint, stem);
			newSticksOut = this._sticksOut(position);
			revertedX = false;
			revertedY = false;
			if (newSticksOut[0] && (newSticksOut[0] !== sticksOut[0])) {
				revertedX = true;
				tipJoint.setHorizontal(this.options.tipJoint.horizontal);
				if (this.options.targetJoint) {
					targetJoint.setHorizontal(this.options.targetJoint.horizontal);
				}
			}
			if (newSticksOut[1] && (newSticksOut[1] !== sticksOut[1])) {
				revertedY = true;
				tipJoint.setVertical(this.options.tipJoint.vertical);
				if (this.options.targetJoint) {
					targetJoint.setVertical(this.options.targetJoint.vertical);
				}
			}
			if (revertedX && revertedY) {
				return originals;
			}
			if (revertedX || revertedY) {
				if (this.options.stem) {
					stem = tipJoint;
				}
				position = this.getPosition(tipJoint, targetJoint, stem);
			}
			return {
				position: position,
				stem: stem
			};
		};

		Opentip.prototype._sticksOut = function (position) {
			var positionOffset, scrollOffset, sticksOut, viewportDimensions;
			scrollOffset = this.adapter.scrollOffset(this._window, this._document);
			viewportDimensions = this._boundingElement ? this.adapter.dimensions(this._boundingElement) : this.adapter.viewportDimensions(doc);

			positionOffset = [position.left - scrollOffset[0], position.top - scrollOffset[1]];
			sticksOut = [false, false];
			if (positionOffset[0] < 0) {
				sticksOut[0] = this.STICKS_OUT_LEFT;
			} else if (positionOffset[0] + this.dimensions.width > viewportDimensions.width) {
				sticksOut[0] = this.STICKS_OUT_RIGHT;
			}
			if (positionOffset[1] < 0) {
				sticksOut[1] = this.STICKS_OUT_TOP;
			} else if (positionOffset[1] + this.dimensions.height > viewportDimensions.height) {
				sticksOut[1] = this.STICKS_OUT_BOTTOM;
			}
			return sticksOut;
		};

		Opentip.prototype._draw = function () {
			var backgroundCanvas, bulge, canvasDimensions, canvasPosition, closeButton, closeButtonInner, closeButtonOuter, ctx, drawCorner, drawLine, hb, position, stemBase, stemLength, _i, _len, _ref, _ref1, _ref2,
				_this = this;
			if (!(this.backgroundCanvas && this.redraw)) {
				return;
			}
			this.debug("Drawing background.");
			this.redraw = false;
			if (this.currentStem) {
				_ref = ["top", "right", "bottom", "left"];
				for (_i = 0, _len = _ref.length; _i < _len; _i++) {
					position = _ref[_i];
					this.adapter.removeClass(this.container, "stem-" + position);
				}
				this.adapter.addClass(this.container, "stem-" + this.currentStem.horizontal);
				this.adapter.addClass(this.container, "stem-" + this.currentStem.vertical);
			}
			closeButtonInner = [0, 0];
			closeButtonOuter = [0, 0];
			if (__indexOf.call(this.options.hideTriggers, "closeButton") >= 0) {
				closeButton = new Opentip.Joint(((_ref1 = this.currentStem) != null ? _ref1.toString() : void 0) === "top right" ? "top left" : "top right");
				closeButtonInner = [this.options.closeButtonRadius + this.options.closeButtonOffset[0], this.options.closeButtonRadius + this.options.closeButtonOffset[1]];
				closeButtonOuter = [this.options.closeButtonRadius - this.options.closeButtonOffset[0], this.options.closeButtonRadius - this.options.closeButtonOffset[1]];
			}
			canvasDimensions = this.adapter.clone(this.dimensions);
			canvasPosition = [0, 0];
			if (this.options.borderWidth) {
				canvasDimensions.width += this.options.borderWidth * 2;
				canvasDimensions.height += this.options.borderWidth * 2;
				canvasPosition[0] -= this.options.borderWidth;
				canvasPosition[1] -= this.options.borderWidth;
			}
			if (this.options.shadow) {
				canvasDimensions.width += this.options.shadowBlur * 2;
				canvasDimensions.width += Math.max(0, this.options.shadowOffset[0] - this.options.shadowBlur * 2);
				canvasDimensions.height += this.options.shadowBlur * 2;
				canvasDimensions.height += Math.max(0, this.options.shadowOffset[1] - this.options.shadowBlur * 2);
				canvasPosition[0] -= Math.max(0, this.options.shadowBlur - this.options.shadowOffset[0]);
				canvasPosition[1] -= Math.max(0, this.options.shadowBlur - this.options.shadowOffset[1]);
			}
			bulge = {
				left: 0,
				right: 0,
				top: 0,
				bottom: 0
			};
			if (this.currentStem) {
				if (this.currentStem.left) {
					bulge.left = this.options.stemLength;
				} else if (this.currentStem.right) {
					bulge.right = this.options.stemLength;
				}
				if (this.currentStem.top) {
					bulge.top = this.options.stemLength;
				} else if (this.currentStem.bottom) {
					bulge.bottom = this.options.stemLength;
				}
			}
			if (closeButton) {
				if (closeButton.left) {
					bulge.left = Math.max(bulge.left, closeButtonOuter[0]);
				} else if (closeButton.right) {
					bulge.right = Math.max(bulge.right, closeButtonOuter[0]);
				}
				if (closeButton.top) {
					bulge.top = Math.max(bulge.top, closeButtonOuter[1]);
				} else if (closeButton.bottom) {
					bulge.bottom = Math.max(bulge.bottom, closeButtonOuter[1]);
				}
			}
			canvasDimensions.width += bulge.left + bulge.right;
			canvasDimensions.height += bulge.top + bulge.bottom;
			canvasPosition[0] -= bulge.left;
			canvasPosition[1] -= bulge.top;
			if (this.currentStem && this.options.borderWidth) {
				_ref2 = this._getPathStemMeasures(this.options.stemBase, this.options.stemLength, this.options.borderWidth), stemLength = _ref2.stemLength, stemBase = _ref2.stemBase;
			}
			backgroundCanvas = this.adapter.unwrap(this.backgroundCanvas);
			backgroundCanvas.width = canvasDimensions.width;
			backgroundCanvas.height = canvasDimensions.height;
			this.adapter.css(this.backgroundCanvas, {
				width: "" + backgroundCanvas.width + "px",
				height: "" + backgroundCanvas.height + "px",
				left: "" + canvasPosition[0] + "px",
				top: "" + canvasPosition[1] + "px"
			});
			ctx = backgroundCanvas.getContext("2d");
			ctx.setTransform(1, 0, 0, 1, 0, 0);
			ctx.clearRect(0, 0, backgroundCanvas.width, backgroundCanvas.height);
			ctx.beginPath();
			ctx.fillStyle = this._getColor(ctx, this.dimensions, this.options.background, this.options.backgroundGradientHorizontal);
			ctx.lineJoin = "miter";
			ctx.miterLimit = 500;
			hb = this.options.borderWidth / 2;
			if (this.options.borderWidth) {
				ctx.strokeStyle = this.options.borderColor;
				ctx.lineWidth = this.options.borderWidth;
			} else {
				stemLength = this.options.stemLength;
				stemBase = this.options.stemBase;
			}
			if (stemBase == null) {
				stemBase = 0;
			}
			drawLine = function (length, stem, first) {
				if (first) {
					ctx.moveTo(Math.max(stemBase, _this.options.borderRadius, closeButtonInner[0]) + 1 - hb, -hb);
				}
				if (stem) {
					ctx.lineTo(length / 2 - stemBase / 2, -hb);
					ctx.lineTo(length / 2, -stemLength - hb);
					return ctx.lineTo(length / 2 + stemBase / 2, -hb);
				}
			};
			drawCorner = function (stem, closeButton, i) {
				var angle1, angle2, innerWidth, offset;
				if (stem) {
					ctx.lineTo(-stemBase + hb, 0 - hb);
					ctx.lineTo(stemLength + hb, -stemLength - hb);
					return ctx.lineTo(hb, stemBase - hb);
				} else if (closeButton) {
					offset = _this.options.closeButtonOffset;
					innerWidth = closeButtonInner[0];
					if (i % 2 !== 0) {
						offset = [offset[1], offset[0]];
						innerWidth = closeButtonInner[1];
					}
					angle1 = Math.acos(offset[1] / _this.options.closeButtonRadius);
					angle2 = Math.acos(offset[0] / _this.options.closeButtonRadius);
					ctx.lineTo(-innerWidth + hb, -hb);
					return ctx.arc(hb - offset[0], -hb + offset[1], _this.options.closeButtonRadius, -(Math.PI / 2 + angle1), angle2, false);
				} else {
					ctx.lineTo(-_this.options.borderRadius + hb, -hb);
					return ctx.quadraticCurveTo(hb, -hb, hb, _this.options.borderRadius - hb);
				}
			};
			ctx.translate(-canvasPosition[0], -canvasPosition[1]);
			ctx.save();
			(function () {
				var cornerStem, i, lineLength, lineStem, positionIdx, positionX, positionY, rotation, _j, _ref3, _results;
				_results = [];
				for (i = _j = 0, _ref3 = Opentip.positions.length / 2; 0 <= _ref3 ? _j < _ref3 : _j > _ref3; i = 0 <= _ref3 ? ++_j : --_j) {
					positionIdx = i * 2;
					positionX = i === 0 || i === 3 ? 0 : _this.dimensions.width;
					positionY = i < 2 ? 0 : _this.dimensions.height;
					rotation = (Math.PI / 2) * i;
					lineLength = i % 2 === 0 ? _this.dimensions.width : _this.dimensions.height;
					lineStem = new Opentip.Joint(Opentip.positions[positionIdx]);
					cornerStem = new Opentip.Joint(Opentip.positions[positionIdx + 1]);
					ctx.save();
					ctx.translate(positionX, positionY);
					ctx.rotate(rotation);
					drawLine(lineLength, lineStem.eql(_this.currentStem), i === 0);
					ctx.translate(lineLength, 0);
					drawCorner(cornerStem.eql(_this.currentStem), cornerStem.eql(closeButton), i);
					_results.push(ctx.restore());
				}
				return _results;
			})();
			ctx.closePath();
			ctx.save();
			if (this.options.shadow) {
				ctx.shadowColor = this.options.shadowColor;
				ctx.shadowBlur = this.options.shadowBlur;
				ctx.shadowOffsetX = this.options.shadowOffset[0];
				ctx.shadowOffsetY = this.options.shadowOffset[1];
			}
			ctx.fill();
			ctx.restore();
			if (this.options.borderWidth) {
				ctx.stroke();
			}
			ctx.restore();
			if (closeButton) {
				return (function () {
					var crossCenter, crossHeight, crossWidth, hcs, linkCenter;
					crossWidth = crossHeight = _this.options.closeButtonRadius * 2;
					if (closeButton.toString() === "top right") {
						linkCenter = [_this.dimensions.width - _this.options.closeButtonOffset[0], _this.options.closeButtonOffset[1]];
						crossCenter = [linkCenter[0] + hb, linkCenter[1] - hb];
					} else {
						linkCenter = [_this.options.closeButtonOffset[0], _this.options.closeButtonOffset[1]];
						crossCenter = [linkCenter[0] - hb, linkCenter[1] - hb];
					}
					ctx.translate(crossCenter[0], crossCenter[1]);
					hcs = _this.options.closeButtonCrossSize / 2;
					ctx.save();
					ctx.beginPath();
					ctx.strokeStyle = _this.options.closeButtonCrossColor;
					ctx.lineWidth = _this.options.closeButtonCrossLineWidth;
					ctx.lineCap = "round";
					ctx.moveTo(-hcs, -hcs);
					ctx.lineTo(hcs, hcs);
					ctx.stroke();
					ctx.beginPath();
					ctx.moveTo(hcs, -hcs);
					ctx.lineTo(-hcs, hcs);
					ctx.stroke();
					ctx.restore();
					return _this.adapter.css(_this.closeButtonElement, {
						left: "" + (linkCenter[0] - hcs - _this.options.closeButtonLinkOverscan) + "px",
						top: "" + (linkCenter[1] - hcs - _this.options.closeButtonLinkOverscan) + "px",
						width: "" + (_this.options.closeButtonCrossSize + _this.options.closeButtonLinkOverscan * 2) + "px",
						height: "" + (_this.options.closeButtonCrossSize + _this.options.closeButtonLinkOverscan * 2) + "px"
					});
				})();
			}
		};

		Opentip.prototype._getPathStemMeasures = function (outerStemBase, outerStemLength, borderWidth) {
			var angle, distanceBetweenTips, halfAngle, hb, rhombusSide, stemBase, stemLength;
			hb = borderWidth / 2;
			halfAngle = Math.atan((outerStemBase / 2) / outerStemLength);
			angle = halfAngle * 2;
			rhombusSide = hb / Math.sin(angle);
			distanceBetweenTips = 2 * rhombusSide * Math.cos(halfAngle);
			stemLength = hb + outerStemLength - distanceBetweenTips;
			if (stemLength < 0) {
				throw new Error("Sorry but your stemLength / stemBase ratio is strange.");
			}
			stemBase = (Math.tan(halfAngle) * stemLength) * 2;
			return {
				stemLength: stemLength,
				stemBase: stemBase
			};
		};

		Opentip.prototype._getColor = function (ctx, dimensions, color, horizontal) {
			var colorStop, gradient, i, _i, _len;
			if (horizontal == null) {
				horizontal = false;
			}
			if (typeof color === "string") {
				return color;
			}
			if (horizontal) {
				gradient = ctx.createLinearGradient(0, 0, dimensions.width, 0);
			} else {
				gradient = ctx.createLinearGradient(0, 0, 0, dimensions.height);
			}
			for (i = _i = 0, _len = color.length; _i < _len; i = ++_i) {
				colorStop = color[i];
				gradient.addColorStop(colorStop[0], colorStop[1]);
			}
			return gradient;
		};

		Opentip.prototype._searchAndActivateCloseButtons = function () {
			var element, _i, _len, _ref;
			_ref = this.adapter.findAll(this.container, "." + this["class"].close);
			for (_i = 0, _len = _ref.length; _i < _len; _i++) {
				element = _ref[_i];
				this.hideTriggers.push({
					element: this.adapter.wrap(element),
					event: "click"
				});
			}
			if (this.currentObservers.showing) {
				this._setupObservers("-showing", "showing");
			}
			if (this.currentObservers.visible) {
				return this._setupObservers("-visible", "visible");
			}
		};

		Opentip.prototype._activateFirstInput = function () {
			var input;
			input = this.adapter.unwrap(this.adapter.find(this.container, "input, textarea"));
			return input != null ? typeof input.focus === "function" ? input.focus() : void 0 : void 0;
		};

		Opentip.prototype._followMousePosition = function () {
			if (!this.options.fixed) {
				return Opentip._observeMousePosition(this.bound.reposition);
			}
		};

		Opentip.prototype._stopFollowingMousePosition = function () {
			if (!this.options.fixed) {
				return Opentip._stopObservingMousePosition(this.bound.reposition);
			}
		};

		Opentip.prototype._clearShowTimeout = function () {
			return clearTimeout(this._showTimeoutId);
		};

		Opentip.prototype._clearHideTimeout = function () {
			return clearTimeout(this._hideTimeoutId);
		};

		Opentip.prototype._clearTimeouts = function () {
			clearTimeout(this._visibilityStateTimeoutId);
			this._clearShowTimeout();
			return this._clearHideTimeout();
		};

		Opentip.prototype._triggerElementExists = function () {
			var el;
			el = this.adapter.unwrap(this.triggerElement);
			while (el.parentNode) {
				if (el.parentNode.tagName === "BODY") {
					return true;
				}
				el = el.parentNode;
			}
			return false;
		};

		Opentip.prototype._loadAjax = function () {
			var _this = this;
			if (this.loading) {
				return;
			}
			this.loaded = false;
			this.loading = true;
			this.adapter.addClass(this.container, this["class"].loading);
			this.setContent("");
			this.debug("Loading content from " + this.options.ajax);
			return this.adapter.ajax({
				url: this.options.ajax,
				method: this.options.ajaxMethod,
				onSuccess: function (responseText) {
					_this.debug("Loading successful.");
					_this.adapter.removeClass(_this.container, _this["class"].loading);
					return _this.setContent(responseText);
				},
				onError: function (error) {
					var message;
					message = _this.options.ajaxErrorMessage;
					_this.debug(message, error);
					_this.setContent(message);
					return _this.adapter.addClass(_this.container, _this["class"].ajaxError);
				},
				onComplete: function () {
					_this.adapter.removeClass(_this.container, _this["class"].loading);
					_this.loading = false;
					_this.loaded = true;
					_this._searchAndActivateCloseButtons();
					_this._activateFirstInput();
					return _this.reposition();
				}
			});
		};

		Opentip.prototype._ensureTriggerElement = function () {
			if (!this._triggerElementExists()) {
				this.deactivate();
				return this._stopEnsureTriggerElement();
			}
		};

		Opentip.prototype._ensureTriggerElementInterval = 1000;

		Opentip.prototype._startEnsureTriggerElement = function () {
			var _this = this;
			return this._ensureTriggerElementTimeoutId = setInterval((function () {
				return _this._ensureTriggerElement();
			}), this._ensureTriggerElementInterval);
		};

		Opentip.prototype._stopEnsureTriggerElement = function () {
			return clearInterval(this._ensureTriggerElementTimeoutId);
		};

		return Opentip;

	})();

	vendors = ["khtml", "ms", "o", "moz", "webkit"];

	Opentip.prototype.setCss3Style = function (element, styles) {
		var prop, value, vendor, vendorProp, _results;
		element = this.adapter.unwrap(element);
		_results = [];
		for (prop in styles) {
			if (!__hasProp.call(styles, prop)) continue;
			value = styles[prop];
			if (element.style[prop] != null) {
				_results.push(element.style[prop] = value);
			} else {
				_results.push((function () {
					var _i, _len, _results1;
					_results1 = [];
					for (_i = 0, _len = vendors.length; _i < _len; _i++) {
						vendor = vendors[_i];
						vendorProp = "" + (this.ucfirst(vendor)) + (this.ucfirst(prop));
						if (element.style[vendorProp] != null) {
							_results1.push(element.style[vendorProp] = value);
						} else {
							_results1.push(void 0);
						}
					}
					return _results1;
				}).call(this));
			}
		}
		return _results;
	};

	Opentip.prototype.defer = function (func) {
		return setTimeout(func, 0);
	};

	Opentip.prototype.setTimeout = function (func, seconds) {
		return setTimeout(func, seconds ? seconds * 1000 : 0);
	};

	Opentip.prototype.ucfirst = function (string) {
		if (string == null) {
			return "";
		}
		return string.charAt(0).toUpperCase() + string.slice(1);
	};

	Opentip.prototype.dasherize = function (string) {
		return string.replace(/([A-Z])/g, function (_, character) {
			return "-" + (character.toLowerCase());
		});
	};

	mousePositionObservers = [];

	mousePosition = {
		x: 0,
		y: 0
	};

	mouseMoved = function (e) {
		var observer, _i, _len, _results;
		mousePosition = Opentip.adapter.mousePosition(e);
		_results = [];
		for (_i = 0, _len = mousePositionObservers.length; _i < _len; _i++) {
			observer = mousePositionObservers[_i];
			_results.push(observer());
		}
		return _results;
	};

	Opentip._observeMousePosition = function (observer) {
		return mousePositionObservers.push(observer);
	};

	Opentip._stopObservingMousePosition = function (removeObserver) {
		var observer;
		return mousePositionObservers = (function () {
			var _i, _len, _results;
			_results = [];
			for (_i = 0, _len = mousePositionObservers.length; _i < _len; _i++) {
				observer = mousePositionObservers[_i];
				if (observer !== removeObserver) {
					_results.push(observer);
				}
			}
			return _results;
		})();
	};

	Opentip.Joint = (function () {

		function Joint(pointerString) {
			if (pointerString == null) {
				return;
			}
			if (pointerString instanceof Opentip.Joint) {
				pointerString = pointerString.toString();
			}
			this.set(pointerString);
		}

		Joint.prototype.set = function (string) {
			string = string.toLowerCase();
			this.setHorizontal(string);
			this.setVertical(string);
			return this;
		};

		Joint.prototype.setHorizontal = function (string) {
			var i, valid, _i, _j, _len, _len1, _results;
			valid = ["left", "center", "right"];
			for (_i = 0, _len = valid.length; _i < _len; _i++) {
				i = valid[_i];
				if (~string.indexOf(i)) {
					this.horizontal = i.toLowerCase();
				}
			}
			if (this.horizontal == null) {
				this.horizontal = "center";
			}
			_results = [];
			for (_j = 0, _len1 = valid.length; _j < _len1; _j++) {
				i = valid[_j];
				_results.push(this[i] = this.horizontal === i ? i : void 0);
			}
			return _results;
		};

		Joint.prototype.setVertical = function (string) {
			var i, valid, _i, _j, _len, _len1, _results;
			valid = ["top", "middle", "bottom"];
			for (_i = 0, _len = valid.length; _i < _len; _i++) {
				i = valid[_i];
				if (~string.indexOf(i)) {
					this.vertical = i.toLowerCase();
				}
			}
			if (this.vertical == null) {
				this.vertical = "middle";
			}
			_results = [];
			for (_j = 0, _len1 = valid.length; _j < _len1; _j++) {
				i = valid[_j];
				_results.push(this[i] = this.vertical === i ? i : void 0);
			}
			return _results;
		};

		Joint.prototype.eql = function (pointer) {
			return (pointer != null) && this.horizontal === pointer.horizontal && this.vertical === pointer.vertical;
		};

		Joint.prototype.flip = function () {
			var flippedIndex, positionIdx;
			positionIdx = Opentip.position[this.toString(true)];
			flippedIndex = (positionIdx + 4) % 8;
			this.set(Opentip.positions[flippedIndex]);
			return this;
		};

		Joint.prototype.toString = function (camelized) {
			var horizontal, vertical;
			if (camelized == null) {
				camelized = false;
			}
			vertical = this.vertical === "middle" ? "" : this.vertical;
			horizontal = this.horizontal === "center" ? "" : this.horizontal;
			if (vertical && horizontal) {
				if (camelized) {
					horizontal = Opentip.prototype.ucfirst(horizontal);
				} else {
					horizontal = " " + horizontal;
				}
			}
			return "" + vertical + horizontal;
		};

		return Joint;

	})();

	Opentip.prototype._positionsEqual = function (posA, posB) {
		return (posA != null) && (posB != null) && posA.left === posB.left && posA.top === posB.top;
	};

	Opentip.prototype._dimensionsEqual = function (dimA, dimB) {
		return (dimA != null) && (dimB != null) && dimA.width === dimB.width && dimA.height === dimB.height;
	};

	Opentip.prototype.debug = function () {
		var args;
		args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
		if (Opentip.debug && ((typeof console !== "undefined" && console !== null ? console.debug : void 0) != null)) {
			args.unshift("#" + this.id + " |");
			return console.debug.apply(console, args);
		}
	};

	Opentip.version = "2.4.6";

	Opentip.debug = false;

	Opentip.lastId = 0;

	Opentip.lastZIndex = 100;

	Opentip.tips = [];

	Opentip._abortShowingGroup = function (group, originatingOpentip) {
		var opentip, _i, _len, _ref, _results;
		_ref = Opentip.tips;
		_results = [];
		for (_i = 0, _len = _ref.length; _i < _len; _i++) {
			opentip = _ref[_i];
			if (opentip !== originatingOpentip && opentip.options.group === group) {
				_results.push(opentip._abortShowing());
			} else {
				_results.push(void 0);
			}
		}
		return _results;
	};

	Opentip._hideGroup = function (group, originatingOpentip) {
		var opentip, _i, _len, _ref, _results;
		_ref = Opentip.tips;
		_results = [];
		for (_i = 0, _len = _ref.length; _i < _len; _i++) {
			opentip = _ref[_i];
			if (opentip !== originatingOpentip && opentip.options.group === group) {
				_results.push(opentip.hide());
			} else {
				_results.push(void 0);
			}
		}
		return _results;
	};

	Opentip.adapters = {};

	Opentip.adapter = null;

	firstAdapter = true;

	Opentip.addAdapter = function (adapter) {
		Opentip.adapters[adapter.name] = adapter;
		if (firstAdapter) {
			Opentip.adapter = adapter;
			return firstAdapter = false;
		}
	};

	Opentip.positions = ["top", "topRight", "right", "bottomRight", "bottom", "bottomLeft", "left", "topLeft"];

	Opentip.position = {};

	_ref = Opentip.positions;
	for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
		position = _ref[i];
		Opentip.position[position] = i;
	}

	Opentip.styles = {
		standard: {
			"extends": null,
			title: void 0,
			escapeTitle: true,
			escapeContent: false,
			className: "standard",
			stem: true,
			delay: null,
			hideDelay: 0.1,
			fixed: false,
			showOn: "mouseover",
			hideTrigger: "trigger",
			hideTriggers: [],
			hideOn: null,
			removeElementsOnHide: false,
			offset: [0, 0],
			containInViewport: true,
			autoOffset: true,
			showEffect: "appear",
			hideEffect: "fade",
			showEffectDuration: 0.3,
			hideEffectDuration: 0.2,
			stemLength: 5,
			stemBase: 8,
			tipJoint: "top left",
			target: null,
			targetJoint: null,
			cache: true,
			ajax: false,
			ajaxMethod: "GET",
			ajaxErrorMessage: "There was a problem downloading the content.",
			group: null,
			style: null,
			background: "#fff18f",
			backgroundGradientHorizontal: false,
			closeButtonOffset: [5, 5],
			closeButtonRadius: 7,
			closeButtonCrossSize: 4,
			closeButtonCrossColor: "#d2c35b",
			closeButtonCrossLineWidth: 1.5,
			closeButtonLinkOverscan: 6,
			borderRadius: 5,
			borderWidth: 1,
			borderColor: "#f2e37b",
			shadow: true,
			shadowBlur: 10,
			shadowOffset: [3, 3],
			shadowColor: "rgba(0, 0, 0, 0.1)"
		},
		glass: {
			"extends": "standard",
			className: "glass",
			background: [[0, "rgba(252, 252, 252, 0.8)"], [0.5, "rgba(255, 255, 255, 0.8)"], [0.5, "rgba(250, 250, 250, 0.9)"], [1, "rgba(245, 245, 245, 0.9)"]],
			borderColor: "#eee",
			closeButtonCrossColor: "rgba(0, 0, 0, 0.2)",
			borderRadius: 15,
			closeButtonRadius: 10,
			closeButtonOffset: [8, 8]
		},
		dark: {
			"extends": "standard",
			className: "dark",
			borderRadius: 13,
			borderColor: "#444",
			closeButtonCrossColor: "rgba(240, 240, 240, 1)",
			shadowColor: "rgba(0, 0, 0, 0.3)",
			shadowOffset: [2, 2],
			background: [[0, "rgba(30, 30, 30, 0.7)"], [0.5, "rgba(30, 30, 30, 0.8)"], [0.5, "rgba(10, 10, 10, 0.8)"], [1, "rgba(10, 10, 10, 0.9)"]]
		},
		alert: {
			"extends": "standard",
			className: "alert",
			borderRadius: 1,
			borderColor: "#AE0D11",
			closeButtonCrossColor: "rgba(255, 255, 255, 1)",
			shadowColor: "rgba(0, 0, 0, 0.3)",
			shadowOffset: [2, 2],
			background: [[0, "rgba(203, 15, 19, 0.7)"], [0.5, "rgba(203, 15, 19, 0.8)"], [0.5, "rgba(189, 14, 18, 0.8)"], [1, "rgba(179, 14, 17, 0.9)"]]
		}
	};

	Opentip.defaultStyle = "standard";
	Opentip.getTips = function (element) {
		return Opentip.adapter.data(element, "__opentips") || [];
	}
	Opentip.setTips = function (element, tips) {
		return Opentip.adapter.data(element, "__opentips", tips || []);
	}
	Opentip.removeTip = function (tip) {
		for (var i = Opentip.tips.length; i--;) {
			var tip = Opentip.tips[i];
			if (tip) {
				return Opentip.tips.splice(i, 1);
			}
		}
	}
	Opentip.addTip = function (tip) {
		Opentip.tips.push(tip);
	}
	if (typeof module !== "undefined" && module !== null) {
		module.exports = Opentip;
	} else {
		window.Opentip = Opentip;
	}


	// Generated by CoffeeScript 1.4.0
	var __slice = [].slice;

	(function ($) {
		var Adapter;
		jQuery.fn.opentip = function (content, title, options) {
			return new Opentip(this, content, title, options);
		};
		Adapter = (function () {

			function Adapter() {}

			Adapter.prototype.name = "jquery";

			Adapter.prototype.domReady = function (callback) {
				return jQuery(callback);
			};

			Adapter.prototype.create = function (html, doc) {
				if (doc) {
					var d = doc.createElement("div");
					d.innerHTML = html;
					d = d.firstChild;
					d.parentNode.removeChild(d);
					return jQuery(d);
				} else {
					return jQuery(html);
				}
			};

			Adapter.prototype.wrap = function (element) {
				element = $(element);
				if (element.length > 1) {
					throw new Error("Multiple elements provided.");
				}
				return element;
			};

			Adapter.prototype.unwrap = function (element) {
				return $(element)[0];
			};

			Adapter.prototype.tagName = function (element) {
				return this.unwrap(element).tagName;
			};

			Adapter.prototype.attr = function () {
				var args, element, _ref;
				element = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
				return (_ref = $(element)).attr.apply(_ref, args);
			};

			Adapter.prototype.data = function () {
				var args, element, _ref;
				element = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
				return (_ref = jQuery(element)).data.apply(_ref, args);
			};

			Adapter.prototype.find = function (element, selector) {
				return jQuery(element).find(selector).get(0);
			};

			Adapter.prototype.findAll = function (element, selector) {
				return jQuery(element).find(selector);
			};

			Adapter.prototype.update = function (element, content, escape) {
				element = jQuery(element);
				if (escape) {
					return element.text(content);
				} else {
					return element.html(content);
				}
			};

			Adapter.prototype.append = function (element, child) {
				return $(element).append(child);
			};

			Adapter.prototype.remove = function (element) {
				return $(element).remove();
			};

			Adapter.prototype.addClass = function (element, className) {
				return $(element).addClass(className);
			};

			Adapter.prototype.removeClass = function (element, className) {
				return $(element).removeClass(className);
			};

			Adapter.prototype.css = function (element, properties) {
				return $(element).css(properties);
			};

			Adapter.prototype.dimensions = function (element) {
				return {
					width: $(element).outerWidth(),
					height: $(element).outerHeight()
				};
			};

			Adapter.prototype.scrollOffset = function (win, doc) {
				return [win.pageXOffset || doc.documentElement.scrollLeft || doc.body.scrollLeft, win.pageYOffset || doc.documentElement.scrollTop || doc.body.scrollTop];
			};

			Adapter.prototype.viewportDimensions = function (doc) {
				return {
					width: doc.documentElement.clientWidth,
					height: doc.documentElement.clientHeight
				};
			};

			Adapter.prototype.mousePosition = function (e) {
				if (e == null) {
					return null;
				}
				return {
					x: e.pageX,
					y: e.pageY
				};
			};

			Adapter.prototype.offset = function (element) {
				var offset;
				offset = jQuery(element).offset();
				return {
					left: offset.left,
					top: offset.top
				};
			};

			Adapter.prototype.observe = function (element, eventName, observer) {
				return $(element).bind(eventName, observer);
			};

			Adapter.prototype.stopObserving = function (element, eventName, observer) {
				return $(element).unbind(eventName, observer);
			};

			Adapter.prototype.ajax = function (options) {
				var _ref, _ref1;
				if (options.url == null) {
					throw new Error("No url provided");
				}
				return jQuery.ajax({
					url: options.url,
					type: (_ref = (_ref1 = options.method) != null ? _ref1.toUpperCase() : void 0) != null ? _ref : "GET"
				}).done(function (content) {
					return typeof options.onSuccess === "function" ? options.onSuccess(content) : void 0;
				}).fail(function (request) {
					return typeof options.onError === "function" ? options.onError("Server responded with status " + request.status) : void 0;
				}).always(function () {
					return typeof options.onComplete === "function" ? options.onComplete() : void 0;
				});
			};

			Adapter.prototype.clone = function (object) {
				return jQuery.extend({}, object);
			};

			Adapter.prototype.extend = function () {
				var sources, target;
				target = arguments[0], sources = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
				return jQuery.extend.apply($, [target].concat(__slice.call(sources)));
			};

			return Adapter;

		})();
		return Opentip.addAdapter(new Adapter);
	})(jQuery);

	// fomatting for the tooltips
	var ttOptions = {
		removeElementsOnHide: true,
		fixed: true,
		showOn: "creation"
	};

	var defaults = {
		titleTemplate: "Changed by %u %t",
		delay: 1000
	};

	scope.OpentipAdapter = function () {

	}

	scope.OpentipAdapter.prototype = {
		init: function (options) {
			this._options = jQuery.extend(defaults, options || {});
			this._tips = [];
		},

		showTooltip: function (node, title, boundingElement) {
			var options = jQuery.extend({
					target: node,
					boundingElement: boundingElement
				}, ttOptions),
				tip = new Opentip(node, title, options);
			tip.show();
			jQuery(node).data("_lite_tip_", tip);

		},

		hideAll: function (body) {
			try {
				for (var i = Opentip.tips.length; i--;) {
					Opentip.tips[i].deactivate();
				}
			} catch (ignore) {}
			// clean up any leftover tooltip elements
			if (body && body.ownerDocument) {
				try {
					Opentip.adapter.wrap(body.ownerDocument.body).find("div." + Opentip.prototype["class"]["container"]).remove();
				} catch (ignore) {}
			}
		},

		hideTooltip: function (node, immediate) {
			var tips = Opentip.getTips(node);
			if (tips) {
				var options = {};
				if (immediate) {
					options.hideDelay = 0;
					options.hideEffectDuration = 0;
				}
				for (var i = tips.length; i--;) {
					tips[i].deactivate(options);
				}
			}
		}

	}
})(window);
