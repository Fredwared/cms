/************************************************ Begin XML Class ************************************************/
(function($) {
	$.XML = function(xmlDoc) {
		this.initialize = function(xmlDoc) {
			this.baseDom;
			this.error = false;
	
			//var xmlVersions = ['MSXML2.DOMDocument.5.0', 'MSXML2.DOMDocument.4.0', 'MSXML2.DOMDocument.3.0', 'MSXML2.DOMDocument', 'MSXML2.XmlDom'];
			switch (typeof xmlDoc) {
				case 'object':
					this.baseDom = xmlDoc;
					break;
				case 'string':
					if (window.DOMParser) {
					  	parser = new DOMParser();
					  	this.baseDom = parser.parseFromString(xmlDoc, 'text/xml');
						
						if (this.baseDom.documentElement.nodeName == 'parsererror') {
							this.error = true;
							this.reason = 'Không phân tích được nguồn XML này.';
							this.baseDom = null;
						}
					} else { // Internet Explorer
					  	this.baseDom = new ActiveXObject('Microsoft.XMLDOM');
					  	this.baseDom.async = 'false';
					  	this.baseDom.loadXML(xmlDoc);
						
						if (this.baseDom.parseError.errorCode != 0) {
							this.error = true;
							this.reason = 'Không phân tích được nguồn XML này.';
							this.baseDom = null;
						}
					}
					break;
				default: // Parsing an XML File - A Cross browser
					if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
					  	this.baseDom = new XMLHttpRequest();
					} else {// code for IE6, IE5
					  	this.baseDom = new ActiveXObject('Microsoft.XMLHTTP');
					}
					break;
			}
		};
	
		this.load = function(url) {
			this.baseDom.open('GET', url, false);
			this.baseDom.send();
			this.baseDom = this.baseDom.responseXML;

			if ($.browser.msie) {
				if (this.baseDom.parseError.errorCode != 0) {
					this.error = true;
					this.reason = 'Không phân tích được tập tin XML này.';
					this.baseDom = null;
				}
			} else {
				if (this.baseDom.documentElement.nodeName == 'parsererror') {
					this.error = true;
					this.reason = 'Không phân tích được tập tin XML này.';
					this.baseDom = null;
				}
			}
		};
	
		this.getDOM = function() {
			return this.baseDom;
		};
	
		this.getBaseName = function() {
			return this.baseDom.documentElement.nodeName;
		};
	
		this.getNode = function(xpath) {
			var result;
			var evaluator = this.baseDom;
	
			if ($.browser.msie)
				result = this.baseDom.selectSingleNode(xpath);
			else {
				if (typeof XPathEvaluator !== 'undefined')
					evaluator = new XPathEvaluator();
				result = evaluator.evaluate(xpath, this.baseDom, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null);
			}
			
			return result || null;
		};
		
		this.getNodeName = function(xpath) {
			var node, value;
			
			try {
				node = this.getNode(xpath);
				if ($.browser.msie && node)
					value = node.nodeName;
				else if (node)
					value = node.localName;
			}
			catch (e) {}
			
			return value || null;
		};
	
		this.getNodeValue = function(xpath) {
			var node, value;
			
			try {
				node = this.getNode(xpath);
				if ($.browser.msie && node)
					value = node.text;
				else if (node.singleNodeValue)
					value = node.singleNodeValue.textContent;
			}
			catch (e) {}
			
			return value || null;
		};
	
		this.getNodes = function(xpath) {
			var nodes = [];
			var result, aNode, i;
			var evaluator = this.baseDom;
	
			if ($.browser.msie) {
				result = this.baseDom.selectNodes(xpath);
				$.each(result, function(index, aNode) {
					nodes.push(aNode);
				});
			}
			else {
				if (typeof XPathEvaluator !== 'undefined') {
					evaluator = new XPathEvaluator();
				}
				result = evaluator.evaluate(xpath, this.baseDom, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
				while ((aNode = result.iterateNext()) !== null) {
					nodes.push(aNode);
				}
			}
			
			return nodes;
		};
		
		this.getNodesName = function(xpath) {
			var nodes, values = [], value;
			nodes = this.getNodes(xpath);
			
			$.each(nodes, function(index, node) {
				if ($.browser.msie && node)
					value = node.nodeName;
				else if (node)
					value = node.localName;
				values.push(value);
			});
			
			return values;
		};
	
		this.getNodesValue = function(xpath) {
			var nodes, values = [], value;
			nodes = this.getNodes(xpath);
			
			$.each(nodes, function(index, node) {
				if ($.browser.msie && node)
					value = node.text;
				else if (node)
					value = node.firstChild.nodeValue;
				values.push(value);
			});
			
			return values;
		};
	
		this.countNodes = function(xpath) {
			var nodes = this.getNodes(xpath);
			
			return nodes.length;
		};
	
		this.getNodeAsXML = function(xpath) {
			var str, serializer;
			var aNode = this.getNode(xpath);
			
			try {
				if ($.browser.msie) {
					str = aNode.xml;
				}
				else {
					serializer = new XMLSerializer();
					str = serializer.serializeToString(aNode.singleNodeValue);
				}
			}
			catch (e) {
				str = 'Không thể đưa về dạng XML được.';
			}
			
			return str || null;
		};
		
		this.initialize(xmlDoc);
	};
})(jQuery);
/************************************************ End XML Class ************************************************/