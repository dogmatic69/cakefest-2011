<?php

/**
 * HtmlHelper::link() 1.2.x
 *
 * @param <type> $title
 * @param <type> $url
 * @param <type> $htmlAttributes
 * @param <type> $confirmMessage
 * @param <type> $escapeTitle
 */
function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
	// ... code ...
}

HtmlHelper::link('/some/url', null, null, null, false);
	// <a href="/some/url">/some/url</a>
HtmlHelper::link('<b>foo</b>', '/some/url', array('style' => 'color:red;', 'title' => 'bar'), 'shoud it?', false);
	//<a href="/some/url" title="bar" style="color:red;"><b>foo</b></a>

/**
 * HtmlHelper::link() 1.3.x / 2.x
 *
 * @param <type> $title
 * @param <type> $url
 * @param <type> $options
 * @param <type> $confirmMessage
 */
function link($title, $url = null, $options = array(), $confirmMessage = false) {
	// ... code ...
}

/**
 * more improved
 *
 * @param <type> $title
 * @param <type> $url
 * @param <type> $options
 */
function link($title, $url = null, $options = array()) {
	// ... code ...
}

HtmlHelper::link('/some/url');
	// <a href="/some/url">/some/url</a>

HtmlHelper::link(
	'<b>foo</b>',
	'/some/url',
	array(
		'attributes' => array(
			'style' => 'color:red;',
			'title' => 'bar'
		),
		'confirm' => 'should it?',
		'escape' => false
	)
);
	//<a href="/some/url" title="bar" style="color:red;"><b>foo</b></a>
