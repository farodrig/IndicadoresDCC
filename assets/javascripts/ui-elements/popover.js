/*
Name: 			UI Elements / Widgets - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	1.0.0
*/

(function( $ ) {

	$(function() {

		$('#popover').popover({
			html: true,
			title: function(){
				return $("#popover-head").html();
			},
			content: function() {
				return $("#popover-content").html();
			},
			container: 'body'
		});
})}).apply(this, [ jQuery ]);

(function( $ ) {

	$(function() {

		$('#popover2').popover({
			html: true,
			title: function(){
				return $("#popover-head").html();
			},
			content: function() {
				return $("#popover-content").html();
			},
			container: 'body'
		});
})}).apply(this, [ jQuery ]);

(function( $ ) {

	$(function() {

		$('#popover3').popover({
			html: true,
			title: function(){
				return $("#popover-head").html();
			},
			content: function() {
				return $("#popover-content").html();
			},
			container: 'body'
		});
})}).apply(this, [ jQuery ]);

(function( $ ) {

	$(function() {

		$('#popover4').popover({
			html: true,
			title: function(){
				return $("#popover-head").html();
			},
			content: function() {
				return $("#popover-content").html();
			},
			container: 'body'
		});
})}).apply(this, [ jQuery ]);