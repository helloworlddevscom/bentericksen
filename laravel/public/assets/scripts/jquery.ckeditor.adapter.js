<!DOCTYPE html>
<html><head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>The source code</title>
  <link href="jquery.ckeditor.adapter_files/prettify.css" type="text/css" rel="stylesheet">
  <script type="text/javascript" src="jquery.ckeditor.adapter_files/prettify.js"></script>
  <style type="text/css">
    .highlight { display: block; background-color: #ddd; }
  </style>
  <script type="text/javascript">
    function highlight() {
      document.getElementById(location.hash.replace(/#/, "")).className = "highlight";
    }
  </script>
</head>
<body onload="prettyPrint(); highlight();">
  <pre style="" class="prettyprint lang-js prettyprinted"><span id="global-property-"><span class="com">/**
</span></span><span class="com"> * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */</span><span class="pln">

</span><span id="global-property-"><span class="com">/**
</span></span><span class="com"> * @fileOverview Defines the {@link CKEDITOR_Adapters.jQuery jQuery Adapter}.
 */</span><span class="pln">

</span><span class="highlight" id="CKEDITOR_Adapters-jQuery"><span class="com">/**
</span></span><span class="com"> * @class CKEDITOR_Adapters.jQuery
 * @singleton
 *
 * The jQuery Adapter allows for easy use of basic CKEditor functions and access to the internal API.
 * To find more information about the jQuery Adapter, go to the [jQuery Adapter section](#!/guide/dev_jquery)
 * of the Developer's Guide or see the "Create Editors with jQuery" sample.
 *
 * @aside guide dev_jquery
 */</span><span class="pln">

</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">(</span><span class="pln"> $ </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
</span><span id="CKEDITOR-config-cfg-jqueryOverrideVal"><span class="pln">	</span><span class="com">/**
</span></span><span class="com">	 * Allows CKEditor to override `jQuery.fn.val()`. When set to `true`, the `val()` function
	 * used on textarea elements replaced with CKEditor uses the CKEditor API.
	 *
	 * This configuration option is global and is executed during the loading of the jQuery Adapter.
	 * It cannot be customized across editor instances.
	 *
	 *		&lt;script&gt;
	 *			CKEDITOR.config.jqueryOverrideVal = true;
	 *		&lt;/script&gt;
	 *
	 *		&lt;!-- Important: The jQuery Adapter is loaded *after* setting jqueryOverrideVal. --&gt;
	 *		&lt;script src="/ckeditor/adapters/jquery.js"&gt;&lt;/script&gt;
	 *
	 *		&lt;script&gt;
	 *			$( 'textarea' ).ckeditor();
	 *			// ...
	 *			$( 'textarea' ).val( 'New content' );
	 *		&lt;/script&gt;
	 *
	 * @cfg {Boolean} [jqueryOverrideVal=true]
	 * @member CKEDITOR.config
	 */</span><span class="pln">
	CKEDITOR</span><span class="pun">.</span><span class="pln">config</span><span class="pun">.</span><span class="pln">jqueryOverrideVal </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">typeof</span><span class="pln"> CKEDITOR</span><span class="pun">.</span><span class="pln">config</span><span class="pun">.</span><span class="pln">jqueryOverrideVal </span><span class="pun">==</span><span class="pln"> </span><span class="str">'undefined'</span><span class="pln">  </span><span class="pun">?</span><span class="pln">
				</span><span class="kwd">true</span><span class="pln">
			</span><span class="pun">:</span><span class="pln">
				CKEDITOR</span><span class="pun">.</span><span class="pln">config</span><span class="pun">.</span><span class="pln">jqueryOverrideVal</span><span class="pun">;</span><span class="pln">

	</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> </span><span class="kwd">typeof</span><span class="pln"> $ </span><span class="pun">==</span><span class="pln"> </span><span class="str">'undefined'</span><span class="pln"> </span><span class="pun">)</span><span class="pln">
		</span><span class="kwd">return</span><span class="pun">;</span><span class="pln">

	</span><span class="com">// jQuery object methods.</span><span class="pln">
	$</span><span class="pun">.</span><span class="pln">extend</span><span class="pun">(</span><span class="pln"> $</span><span class="pun">.</span><span class="pln">fn</span><span class="pun">,</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
</span><span id="CKEDITOR_Adapters-jQuery-method-ckeditorGet"><span class="pln">		</span><span class="com">/**
</span></span><span class="com">		 * Returns an existing CKEditor instance for the first matched element.
		 * Allows to easily use the internal API. Does not return a jQuery object.
		 *
		 * Raises an exception if the editor does not exist or is not ready yet.
		 *
		 * @returns CKEDITOR.editor
		 * @deprecated Use {@link #editor editor property} instead.
		 */</span><span class="pln">
		ckeditorGet</span><span class="pun">:</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
			</span><span class="kwd">var</span><span class="pln"> instance </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">this</span><span class="pun">.</span><span class="pln">eq</span><span class="pun">(</span><span class="pln"> </span><span class="lit">0</span><span class="pln"> </span><span class="pun">).</span><span class="pln">data</span><span class="pun">(</span><span class="pln"> </span><span class="str">'ckeditorInstance'</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

			</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> </span><span class="pun">!</span><span class="pln">instance </span><span class="pun">)</span><span class="pln">
				</span><span class="kwd">throw</span><span class="pln"> </span><span class="str">'CKEditor is not initialized yet, use ckeditor() with a callback.'</span><span class="pun">;</span><span class="pln">

			</span><span class="kwd">return</span><span class="pln"> instance</span><span class="pun">;</span><span class="pln">
		</span><span class="pun">},</span><span class="pln">

</span><span id="CKEDITOR_Adapters-jQuery-method-ckeditor"><span class="pln">		</span><span class="com">/**
</span></span><span class="com">		 * A jQuery function which triggers the creation of CKEditor with `&lt;textarea&gt;` and
		 * {@link CKEDITOR.dtd#$editable editable} elements.
		 * Every `&lt;textarea&gt;` element will be converted to a classic (`iframe`-based) editor,
		 * while any other supported element will be converted to an inline editor.
		 * This method binds the callback to the `instanceReady` event of all instances.
		 * If the editor has already been created, the callback is fired straightaway.
		 * You can also create multiple editors at once by using `$( '.className' ).ckeditor();`.
		 *
		 * **Note**: jQuery chaining and mixed parameter order is allowed.
		 *
		 * @param {Function} callback
		 * Function to be run on the editor instance. Callback takes the source element as a parameter.
		 *
		 *		$( 'textarea' ).ckeditor( function( textarea ) {
		 *			// Callback function code.
		 *		} );
		 *
		 * @param {Object} config
		 * Configuration options for new instance(s) if not already created.
		 *
		 *		$( 'textarea' ).ckeditor( {
		 *			uiColor: '#9AB8F3'
		 *		} );
		 *
		 * @returns jQuery.fn
		 */</span><span class="pln">
		ckeditor</span><span class="pun">:</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">(</span><span class="pln"> callback</span><span class="pun">,</span><span class="pln"> config </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
			</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> </span><span class="pun">!</span><span class="pln">CKEDITOR</span><span class="pun">.</span><span class="pln">env</span><span class="pun">.</span><span class="pln">isCompatible </span><span class="pun">)</span><span class="pln">
				</span><span class="kwd">throw</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Error</span><span class="pun">(</span><span class="pln"> </span><span class="str">'The environment is incompatible.'</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

			</span><span class="com">// Reverse the order of arguments if the first one isn't a function.</span><span class="pln">
			</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> </span><span class="pun">!</span><span class="pln">$</span><span class="pun">.</span><span class="pln">isFunction</span><span class="pun">(</span><span class="pln"> callback </span><span class="pun">)</span><span class="pln"> </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
				</span><span class="kwd">var</span><span class="pln"> tmp </span><span class="pun">=</span><span class="pln"> config</span><span class="pun">;</span><span class="pln">
				config </span><span class="pun">=</span><span class="pln"> callback</span><span class="pun">;</span><span class="pln">
				callback </span><span class="pun">=</span><span class="pln"> tmp</span><span class="pun">;</span><span class="pln">
			</span><span class="pun">}</span><span class="pln">

			</span><span class="com">// An array of instanceReady callback promises.</span><span class="pln">
			</span><span class="kwd">var</span><span class="pln"> promises </span><span class="pun">=</span><span class="pln"> </span><span class="pun">[];</span><span class="pln">

			config </span><span class="pun">=</span><span class="pln"> config </span><span class="pun">||</span><span class="pln"> </span><span class="pun">{};</span><span class="pln">

			</span><span class="com">// Iterate over the collection.</span><span class="pln">
			</span><span class="kwd">this</span><span class="pun">.</span><span class="pln">each</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
				</span><span class="kwd">var</span><span class="pln"> $element </span><span class="pun">=</span><span class="pln"> $</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">this</span><span class="pln"> </span><span class="pun">),</span><span class="pln">
					editor </span><span class="pun">=</span><span class="pln"> $element</span><span class="pun">.</span><span class="pln">data</span><span class="pun">(</span><span class="pln"> </span><span class="str">'ckeditorInstance'</span><span class="pln"> </span><span class="pun">),</span><span class="pln">
					instanceLock </span><span class="pun">=</span><span class="pln"> $element</span><span class="pun">.</span><span class="pln">data</span><span class="pun">(</span><span class="pln"> </span><span class="str">'_ckeditorInstanceLock'</span><span class="pln"> </span><span class="pun">),</span><span class="pln">
					element </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">this</span><span class="pun">,</span><span class="pln">
					dfd </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> $</span><span class="pun">.</span><span class="typ">Deferred</span><span class="pun">();</span><span class="pln">

					promises</span><span class="pun">.</span><span class="pln">push</span><span class="pun">(</span><span class="pln"> dfd</span><span class="pun">.</span><span class="pln">promise</span><span class="pun">()</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

				</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> editor </span><span class="pun">&amp;&amp;</span><span class="pln"> </span><span class="pun">!</span><span class="pln">instanceLock </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
					</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> callback </span><span class="pun">)</span><span class="pln">
						callback</span><span class="pun">.</span><span class="pln">apply</span><span class="pun">(</span><span class="pln"> editor</span><span class="pun">,</span><span class="pln"> </span><span class="pun">[</span><span class="pln"> </span><span class="kwd">this</span><span class="pln"> </span><span class="pun">]</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

					dfd</span><span class="pun">.</span><span class="pln">resolve</span><span class="pun">();</span><span class="pln">
				</span><span class="pun">}</span><span class="pln"> </span><span class="kwd">else</span><span class="pln"> </span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> </span><span class="pun">!</span><span class="pln">instanceLock </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
					</span><span class="com">// CREATE NEW INSTANCE</span><span class="pln">

					</span><span class="com">// Handle config.autoUpdateElement inside this plugin if desired.</span><span class="pln">
					</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> config</span><span class="pun">.</span><span class="pln">autoUpdateElement
						</span><span class="pun">||</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> </span><span class="kwd">typeof</span><span class="pln"> config</span><span class="pun">.</span><span class="pln">autoUpdateElement </span><span class="pun">==</span><span class="pln"> </span><span class="str">'undefined'</span><span class="pln"> </span><span class="pun">&amp;&amp;</span><span class="pln"> CKEDITOR</span><span class="pun">.</span><span class="pln">config</span><span class="pun">.</span><span class="pln">autoUpdateElement </span><span class="pun">)</span><span class="pln"> </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
						config</span><span class="pun">.</span><span class="pln">autoUpdateElementJquery </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">true</span><span class="pun">;</span><span class="pln">
					</span><span class="pun">}</span><span class="pln">

					</span><span class="com">// Always disable config.autoUpdateElement.</span><span class="pln">
					config</span><span class="pun">.</span><span class="pln">autoUpdateElement </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">false</span><span class="pun">;</span><span class="pln">
					$element</span><span class="pun">.</span><span class="pln">data</span><span class="pun">(</span><span class="pln"> </span><span class="str">'_ckeditorInstanceLock'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">true</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

					</span><span class="com">// Set instance reference in element's data.</span><span class="pln">
					</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> $</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">this</span><span class="pln"> </span><span class="pun">).</span><span class="pln">is</span><span class="pun">(</span><span class="pln"> </span><span class="str">'textarea'</span><span class="pln"> </span><span class="pun">)</span><span class="pln"> </span><span class="pun">)</span><span class="pln">
						editor </span><span class="pun">=</span><span class="pln"> CKEDITOR</span><span class="pun">.</span><span class="pln">replace</span><span class="pun">(</span><span class="pln"> element</span><span class="pun">,</span><span class="pln"> config </span><span class="pun">);</span><span class="pln">
					</span><span class="kwd">else</span><span class="pln">
						editor </span><span class="pun">=</span><span class="pln"> CKEDITOR</span><span class="pun">.</span><span class="kwd">inline</span><span class="pun">(</span><span class="pln"> element</span><span class="pun">,</span><span class="pln"> config </span><span class="pun">);</span><span class="pln">

					$element</span><span class="pun">.</span><span class="pln">data</span><span class="pun">(</span><span class="pln"> </span><span class="str">'ckeditorInstance'</span><span class="pun">,</span><span class="pln"> editor </span><span class="pun">);</span><span class="pln">

					</span><span class="com">// Register callback.</span><span class="pln">
					editor</span><span class="pun">.</span><span class="pln">on</span><span class="pun">(</span><span class="pln"> </span><span class="str">'instanceReady'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">(</span><span class="pln"> evt </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
						</span><span class="kwd">var</span><span class="pln"> editor </span><span class="pun">=</span><span class="pln"> evt</span><span class="pun">.</span><span class="pln">editor</span><span class="pun">;</span><span class="pln">

						setTimeout</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
							</span><span class="com">// Delay bit more if editor is still not ready.</span><span class="pln">
							</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> </span><span class="pun">!</span><span class="pln">editor</span><span class="pun">.</span><span class="pln">element </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								setTimeout</span><span class="pun">(</span><span class="pln"> arguments</span><span class="pun">.</span><span class="pln">callee</span><span class="pun">,</span><span class="pln"> </span><span class="lit">100</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
								</span><span class="kwd">return</span><span class="pun">;</span><span class="pln">
							</span><span class="pun">}</span><span class="pln">

							</span><span class="com">// Remove this listener. Triggered when new instance is ready.</span><span class="pln">
							evt</span><span class="pun">.</span><span class="pln">removeListener</span><span class="pun">();</span><span class="pln">

</span><span id="CKEDITOR_Adapters-jQuery-event-dataReady"><span class="pln">							</span><span class="com">/**
</span></span><span class="com">							 * Forwards the CKEditor {@link CKEDITOR.editor#event-dataReady dataReady event} as a jQuery event.
							 *
							 * @event dataReady
							 * @param {CKEDITOR.editor} editor Editor instance.
							 */</span><span class="pln">
							editor</span><span class="pun">.</span><span class="pln">on</span><span class="pun">(</span><span class="pln"> </span><span class="str">'dataReady'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								$element</span><span class="pun">.</span><span class="pln">trigger</span><span class="pun">(</span><span class="pln"> </span><span class="str">'dataReady.ckeditor'</span><span class="pun">,</span><span class="pln"> </span><span class="pun">[</span><span class="pln"> editor </span><span class="pun">]</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
							</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

</span><span id="CKEDITOR_Adapters-jQuery-event-setData"><span class="pln">							</span><span class="com">/**
</span></span><span class="com">							 * Forwards the CKEditor {@link CKEDITOR.editor#event-setData setData event} as a jQuery event.
							 *
							 * @event setData
							 * @param {CKEDITOR.editor} editor Editor instance.
							 * @param data
							 * @param {String} data.dataValue The data that will be used.
							 */</span><span class="pln">
							editor</span><span class="pun">.</span><span class="pln">on</span><span class="pun">(</span><span class="pln"> </span><span class="str">'setData'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">(</span><span class="pln"> evt </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								$element</span><span class="pun">.</span><span class="pln">trigger</span><span class="pun">(</span><span class="pln"> </span><span class="str">'setData.ckeditor'</span><span class="pun">,</span><span class="pln"> </span><span class="pun">[</span><span class="pln"> editor</span><span class="pun">,</span><span class="pln"> evt</span><span class="pun">.</span><span class="pln">data </span><span class="pun">]</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
							</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

</span><span id="CKEDITOR_Adapters-jQuery-event-getData"><span class="pln">							</span><span class="com">/**
</span></span><span class="com">							 * Forwards the CKEditor {@link CKEDITOR.editor#event-getData getData event} as a jQuery event.
							 *
							 * @event getData
							 * @param {CKEDITOR.editor} editor Editor instance.
							 * @param data
							 * @param {String} data.dataValue The data that will be returned.
							 */</span><span class="pln">
							editor</span><span class="pun">.</span><span class="pln">on</span><span class="pun">(</span><span class="pln"> </span><span class="str">'getData'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">(</span><span class="pln"> evt </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								$element</span><span class="pun">.</span><span class="pln">trigger</span><span class="pun">(</span><span class="pln"> </span><span class="str">'getData.ckeditor'</span><span class="pun">,</span><span class="pln"> </span><span class="pun">[</span><span class="pln"> editor</span><span class="pun">,</span><span class="pln"> evt</span><span class="pun">.</span><span class="pln">data </span><span class="pun">]</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
							</span><span class="pun">},</span><span class="pln"> </span><span class="lit">999</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

</span><span id="CKEDITOR_Adapters-jQuery-event-destroy"><span class="pln">							</span><span class="com">/**
</span></span><span class="com">							 * Forwards the CKEditor {@link CKEDITOR.editor#event-destroy destroy event} as a jQuery event.
							 *
							 * @event destroy
							 * @param {CKEDITOR.editor} editor Editor instance.
							 */</span><span class="pln">
							editor</span><span class="pun">.</span><span class="pln">on</span><span class="pun">(</span><span class="pln"> </span><span class="str">'destroy'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								$element</span><span class="pun">.</span><span class="pln">trigger</span><span class="pun">(</span><span class="pln"> </span><span class="str">'destroy.ckeditor'</span><span class="pun">,</span><span class="pln"> </span><span class="pun">[</span><span class="pln"> editor </span><span class="pun">]</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
							</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

							</span><span class="com">// Overwrite save button to call jQuery submit instead of javascript submit.</span><span class="pln">
							</span><span class="com">// Otherwise jQuery.forms does not work properly</span><span class="pln">
							editor</span><span class="pun">.</span><span class="pln">on</span><span class="pun">(</span><span class="pln"> </span><span class="str">'save'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								$</span><span class="pun">(</span><span class="pln"> element</span><span class="pun">.</span><span class="pln">form </span><span class="pun">).</span><span class="pln">submit</span><span class="pun">();</span><span class="pln">
								</span><span class="kwd">return</span><span class="pln"> </span><span class="kwd">false</span><span class="pun">;</span><span class="pln">
							</span><span class="pun">},</span><span class="pln"> </span><span class="kwd">null</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">null</span><span class="pun">,</span><span class="pln"> </span><span class="lit">20</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

							</span><span class="com">// Integrate with form submit.</span><span class="pln">
							</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> editor</span><span class="pun">.</span><span class="pln">config</span><span class="pun">.</span><span class="pln">autoUpdateElementJquery </span><span class="pun">&amp;&amp;</span><span class="pln"> $element</span><span class="pun">.</span><span class="pln">is</span><span class="pun">(</span><span class="pln"> </span><span class="str">'textarea'</span><span class="pln"> </span><span class="pun">)</span><span class="pln"> </span><span class="pun">&amp;&amp;</span><span class="pln"> $</span><span class="pun">(</span><span class="pln"> element</span><span class="pun">.</span><span class="pln">form </span><span class="pun">).</span><span class="pln">length </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								</span><span class="kwd">var</span><span class="pln"> onSubmit </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
									$element</span><span class="pun">.</span><span class="pln">ckeditor</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
										editor</span><span class="pun">.</span><span class="pln">updateElement</span><span class="pun">();</span><span class="pln">
									</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
								</span><span class="pun">};</span><span class="pln">

								</span><span class="com">// Bind to submit event.</span><span class="pln">
								$</span><span class="pun">(</span><span class="pln"> element</span><span class="pun">.</span><span class="pln">form </span><span class="pun">).</span><span class="pln">submit</span><span class="pun">(</span><span class="pln"> onSubmit </span><span class="pun">);</span><span class="pln">

								</span><span class="com">// Bind to form-pre-serialize from jQuery Forms plugin.</span><span class="pln">
								$</span><span class="pun">(</span><span class="pln"> element</span><span class="pun">.</span><span class="pln">form </span><span class="pun">).</span><span class="pln">bind</span><span class="pun">(</span><span class="pln"> </span><span class="str">'form-pre-serialize'</span><span class="pun">,</span><span class="pln"> onSubmit </span><span class="pun">);</span><span class="pln">

								</span><span class="com">// Unbind when editor destroyed.</span><span class="pln">
								$element</span><span class="pun">.</span><span class="pln">bind</span><span class="pun">(</span><span class="pln"> </span><span class="str">'destroy.ckeditor'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
									$</span><span class="pun">(</span><span class="pln"> element</span><span class="pun">.</span><span class="pln">form </span><span class="pun">).</span><span class="pln">unbind</span><span class="pun">(</span><span class="pln"> </span><span class="str">'submit'</span><span class="pun">,</span><span class="pln"> onSubmit </span><span class="pun">);</span><span class="pln">
									$</span><span class="pun">(</span><span class="pln"> element</span><span class="pun">.</span><span class="pln">form </span><span class="pun">).</span><span class="pln">unbind</span><span class="pun">(</span><span class="pln"> </span><span class="str">'form-pre-serialize'</span><span class="pun">,</span><span class="pln"> onSubmit </span><span class="pun">);</span><span class="pln">
								</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
							</span><span class="pun">}</span><span class="pln">

							</span><span class="com">// Garbage collect on destroy.</span><span class="pln">
							editor</span><span class="pun">.</span><span class="pln">on</span><span class="pun">(</span><span class="pln"> </span><span class="str">'destroy'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								$element</span><span class="pun">.</span><span class="pln">removeData</span><span class="pun">(</span><span class="pln"> </span><span class="str">'ckeditorInstance'</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
							</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

							</span><span class="com">// Remove lock.</span><span class="pln">
							$element</span><span class="pun">.</span><span class="pln">removeData</span><span class="pun">(</span><span class="pln"> </span><span class="str">'_ckeditorInstanceLock'</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

</span><span id="CKEDITOR_Adapters-jQuery-event-instanceReady"><span class="pln">							</span><span class="com">/**
</span></span><span class="com">							 * Forwards the CKEditor {@link CKEDITOR.editor#event-instanceReady instanceReady event} as a jQuery event.
							 *
							 * @event instanceReady
							 * @param {CKEDITOR.editor} editor Editor instance.
							 */</span><span class="pln">
							$element</span><span class="pun">.</span><span class="pln">trigger</span><span class="pun">(</span><span class="pln"> </span><span class="str">'instanceReady.ckeditor'</span><span class="pun">,</span><span class="pln"> </span><span class="pun">[</span><span class="pln"> editor </span><span class="pun">]</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

							</span><span class="com">// Run given (first) code.</span><span class="pln">
							</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> callback </span><span class="pun">)</span><span class="pln">
								callback</span><span class="pun">.</span><span class="pln">apply</span><span class="pun">(</span><span class="pln"> editor</span><span class="pun">,</span><span class="pln"> </span><span class="pun">[</span><span class="pln"> element </span><span class="pun">]</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

							dfd</span><span class="pun">.</span><span class="pln">resolve</span><span class="pun">();</span><span class="pln">
						</span><span class="pun">},</span><span class="pln"> </span><span class="lit">0</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
					</span><span class="pun">},</span><span class="pln"> </span><span class="kwd">null</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">null</span><span class="pun">,</span><span class="pln"> </span><span class="lit">9999</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
				</span><span class="pun">}</span><span class="pln"> </span><span class="kwd">else</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
					</span><span class="com">// Editor is already during creation process, bind our code to the event.</span><span class="pln">
					editor</span><span class="pun">.</span><span class="pln">once</span><span class="pun">(</span><span class="pln"> </span><span class="str">'instanceReady'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">(</span><span class="pln"> evt </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
						setTimeout</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
							</span><span class="com">// Delay bit more if editor is still not ready.</span><span class="pln">
							</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> </span><span class="pun">!</span><span class="pln">editor</span><span class="pun">.</span><span class="pln">element </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								setTimeout</span><span class="pun">(</span><span class="pln"> arguments</span><span class="pun">.</span><span class="pln">callee</span><span class="pun">,</span><span class="pln"> </span><span class="lit">100</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
								</span><span class="kwd">return</span><span class="pun">;</span><span class="pln">
							</span><span class="pun">}</span><span class="pln">

							</span><span class="com">// Run given code.</span><span class="pln">
							</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> editor</span><span class="pun">.</span><span class="pln">element</span><span class="pun">.</span><span class="pln">$ </span><span class="pun">==</span><span class="pln"> element </span><span class="pun">&amp;&amp;</span><span class="pln"> callback </span><span class="pun">)</span><span class="pln">
								callback</span><span class="pun">.</span><span class="pln">apply</span><span class="pun">(</span><span class="pln"> editor</span><span class="pun">,</span><span class="pln"> </span><span class="pun">[</span><span class="pln"> element </span><span class="pun">]</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

							dfd</span><span class="pun">.</span><span class="pln">resolve</span><span class="pun">();</span><span class="pln">
						</span><span class="pun">},</span><span class="pln"> </span><span class="lit">0</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
					</span><span class="pun">},</span><span class="pln"> </span><span class="kwd">null</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">null</span><span class="pun">,</span><span class="pln"> </span><span class="lit">9999</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
				</span><span class="pun">}</span><span class="pln">
			</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

</span><span id="CKEDITOR_Adapters-jQuery-property-promise"><span class="pln">			</span><span class="com">/**
</span></span><span class="com">			 * The [jQuery Promise object]((http://api.jquery.com/promise/)) that handles the asynchronous constructor.
			 * This promise will be resolved after **all** of the constructors.
			 *
			 * @property {Function} promise
			 */</span><span class="pln">
			</span><span class="kwd">var</span><span class="pln"> dfd </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> $</span><span class="pun">.</span><span class="typ">Deferred</span><span class="pun">();</span><span class="pln">

			</span><span class="kwd">this</span><span class="pun">.</span><span class="pln">promise </span><span class="pun">=</span><span class="pln"> dfd</span><span class="pun">.</span><span class="pln">promise</span><span class="pun">();</span><span class="pln">

			$</span><span class="pun">.</span><span class="pln">when</span><span class="pun">.</span><span class="pln">apply</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">this</span><span class="pun">,</span><span class="pln"> promises </span><span class="pun">).</span><span class="pln">then</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
				dfd</span><span class="pun">.</span><span class="pln">resolve</span><span class="pun">();</span><span class="pln">
			</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

</span><span id="CKEDITOR_Adapters-jQuery-property-editor"><span class="pln">			</span><span class="com">/**
</span></span><span class="com">			 * Existing CKEditor instance. Allows to easily use the internal API.
			 *
			 * **Note**: This is not a jQuery object.
			 *
			 *		var editor = $( 'textarea' ).ckeditor().editor;
			 *
			 * @property {CKEDITOR.editor} editor
			 */</span><span class="pln">
			</span><span class="kwd">this</span><span class="pun">.</span><span class="pln">editor </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">this</span><span class="pun">.</span><span class="pln">eq</span><span class="pun">(</span><span class="pln"> </span><span class="lit">0</span><span class="pln"> </span><span class="pun">).</span><span class="pln">data</span><span class="pun">(</span><span class="pln"> </span><span class="str">'ckeditorInstance'</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

			</span><span class="kwd">return</span><span class="pln"> </span><span class="kwd">this</span><span class="pun">;</span><span class="pln">
		</span><span class="pun">}</span><span class="pln">
	</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

</span><span id="CKEDITOR_Adapters-jQuery-method-val"><span class="pln">	</span><span class="com">/**
</span></span><span class="com">	 * Overwritten jQuery `val()` method for `&lt;textarea&gt;` elements that have bound CKEditor instances.
	 * This method gets or sets editor content by using the {@link CKEDITOR.editor#method-getData editor.getData()}
	 * or {@link CKEDITOR.editor#method-setData editor.setData()} methods. To handle
	 * the {@link CKEDITOR.editor#method-setData editor.setData()} callback (as `setData` is asynchronous),
	 * `val( 'some data' )` will return a [jQuery Promise object](http://api.jquery.com/promise/).
	 *
	 * @method val
	 * @returns String|Number|Array|jQuery.fn|function(jQuery Promise)
	 */</span><span class="pln">
	</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> CKEDITOR</span><span class="pun">.</span><span class="pln">config</span><span class="pun">.</span><span class="pln">jqueryOverrideVal </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
		$</span><span class="pun">.</span><span class="pln">fn</span><span class="pun">.</span><span class="pln">val </span><span class="pun">=</span><span class="pln"> CKEDITOR</span><span class="pun">.</span><span class="pln">tools</span><span class="pun">.</span><span class="pln">override</span><span class="pun">(</span><span class="pln"> $</span><span class="pun">.</span><span class="pln">fn</span><span class="pun">.</span><span class="pln">val</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">(</span><span class="pln"> oldValMethod </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
			</span><span class="kwd">return</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">(</span><span class="pln"> value </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
				</span><span class="com">// Setter, i.e. .val( "some data" );</span><span class="pln">
				</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> arguments</span><span class="pun">.</span><span class="pln">length </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
					</span><span class="kwd">var</span><span class="pln"> _this </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">this</span><span class="pun">,</span><span class="pln">
						promises </span><span class="pun">=</span><span class="pln"> </span><span class="pun">[],</span><span class="pln"> </span><span class="com">//use promise to handle setData callback</span><span class="pln">

						result </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">this</span><span class="pun">.</span><span class="pln">each</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
							</span><span class="kwd">var</span><span class="pln"> $elem </span><span class="pun">=</span><span class="pln"> $</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">this</span><span class="pln"> </span><span class="pun">),</span><span class="pln">
								editor </span><span class="pun">=</span><span class="pln"> $elem</span><span class="pun">.</span><span class="pln">data</span><span class="pun">(</span><span class="pln"> </span><span class="str">'ckeditorInstance'</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

							</span><span class="com">// Handle .val for CKEditor.</span><span class="pln">
							</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> $elem</span><span class="pun">.</span><span class="pln">is</span><span class="pun">(</span><span class="pln"> </span><span class="str">'textarea'</span><span class="pln"> </span><span class="pun">)</span><span class="pln"> </span><span class="pun">&amp;&amp;</span><span class="pln"> editor </span><span class="pun">)</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
								</span><span class="kwd">var</span><span class="pln"> dfd </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> $</span><span class="pun">.</span><span class="typ">Deferred</span><span class="pun">();</span><span class="pln">

								editor</span><span class="pun">.</span><span class="pln">setData</span><span class="pun">(</span><span class="pln"> value</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
									dfd</span><span class="pun">.</span><span class="pln">resolve</span><span class="pun">();</span><span class="pln">
								</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

								promises</span><span class="pun">.</span><span class="pln">push</span><span class="pun">(</span><span class="pln"> dfd</span><span class="pun">.</span><span class="pln">promise</span><span class="pun">()</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
								</span><span class="kwd">return</span><span class="pln"> </span><span class="kwd">true</span><span class="pun">;</span><span class="pln">
							</span><span class="pun">}</span><span class="pln">
							</span><span class="com">// Call default .val function for rest of elements</span><span class="pln">
							</span><span class="kwd">else</span><span class="pln">
								</span><span class="kwd">return</span><span class="pln"> oldValMethod</span><span class="pun">.</span><span class="pln">call</span><span class="pun">(</span><span class="pln"> $elem</span><span class="pun">,</span><span class="pln"> value </span><span class="pun">);</span><span class="pln">
						</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

					</span><span class="com">// If there is no promise return default result (jQuery object of chaining).</span><span class="pln">
					</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> </span><span class="pun">!</span><span class="pln">promises</span><span class="pun">.</span><span class="pln">length </span><span class="pun">)</span><span class="pln">
						</span><span class="kwd">return</span><span class="pln"> result</span><span class="pun">;</span><span class="pln">
					</span><span class="com">// Create one promise which will be resolved when all of promises will be done.</span><span class="pln">
					</span><span class="kwd">else</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
						</span><span class="kwd">var</span><span class="pln"> dfd </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> $</span><span class="pun">.</span><span class="typ">Deferred</span><span class="pun">();</span><span class="pln">

						$</span><span class="pun">.</span><span class="pln">when</span><span class="pun">.</span><span class="pln">apply</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">this</span><span class="pun">,</span><span class="pln"> promises </span><span class="pun">).</span><span class="pln">done</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
							dfd</span><span class="pun">.</span><span class="pln">resolveWith</span><span class="pun">(</span><span class="pln"> _this </span><span class="pun">);</span><span class="pln">
						</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

						</span><span class="kwd">return</span><span class="pln"> dfd</span><span class="pun">.</span><span class="pln">promise</span><span class="pun">();</span><span class="pln">
					</span><span class="pun">}</span><span class="pln">
				</span><span class="pun">}</span><span class="pln">
				</span><span class="com">// Getter .val();</span><span class="pln">
				</span><span class="kwd">else</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
					</span><span class="kwd">var</span><span class="pln"> $elem </span><span class="pun">=</span><span class="pln"> $</span><span class="pun">(</span><span class="pln"> </span><span class="kwd">this</span><span class="pln"> </span><span class="pun">).</span><span class="pln">eq</span><span class="pun">(</span><span class="pln"> </span><span class="lit">0</span><span class="pln"> </span><span class="pun">),</span><span class="pln">
						editor </span><span class="pun">=</span><span class="pln"> $elem</span><span class="pun">.</span><span class="pln">data</span><span class="pun">(</span><span class="pln"> </span><span class="str">'ckeditorInstance'</span><span class="pln"> </span><span class="pun">);</span><span class="pln">

					</span><span class="kwd">if</span><span class="pln"> </span><span class="pun">(</span><span class="pln"> $elem</span><span class="pun">.</span><span class="pln">is</span><span class="pun">(</span><span class="pln"> </span><span class="str">'textarea'</span><span class="pln"> </span><span class="pun">)</span><span class="pln"> </span><span class="pun">&amp;&amp;</span><span class="pln"> editor </span><span class="pun">)</span><span class="pln">
						</span><span class="kwd">return</span><span class="pln"> editor</span><span class="pun">.</span><span class="pln">getData</span><span class="pun">();</span><span class="pln">
					</span><span class="kwd">else</span><span class="pln">
						</span><span class="kwd">return</span><span class="pln"> oldValMethod</span><span class="pun">.</span><span class="pln">call</span><span class="pun">(</span><span class="pln"> $elem </span><span class="pun">);</span><span class="pln">
				</span><span class="pun">}</span><span class="pln">
			</span><span class="pun">};</span><span class="pln">
		</span><span class="pun">}</span><span class="pln"> </span><span class="pun">);</span><span class="pln">
	</span><span class="pun">}</span><span class="pln">
</span><span class="pun">}</span><span class="pln"> </span><span class="pun">)(</span><span class="pln"> window</span><span class="pun">.</span><span class="pln">jQuery </span><span class="pun">);</span></pre>


</body></html>