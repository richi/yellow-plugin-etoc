<?php
// Source: https://github.com/richi/
// Most lines of this code are (c) 2013-2016 Datenstrom, http://datenstrom.se
// This file may be used and distributed under the terms of the public license.

// Extended table of contents plugin
class YellowEToc
{
	const VERSION = "0.1.4";
	var $yellow;			//access to API
	
	// Handle initialisation
	function onLoad($yellow)
	{
		$this->yellow = $yellow;
	}
	
	// Handle page content parsing
	function onParseContentText($page, $text)
	{
		$self = $this;
		$callback = function($matches) use ($self, $page)
		{
			$numbers = $matches[2] == "numbers";
  			return $self->generateToc($page->getPage("main")->parserData, $numbers);
		};
		return preg_replace_callback("/<p>\[etoc(\s(.*))?\]<\/p>\n/i", $callback, $text);
	}

	function generateToc($content, $numbers)
	{
		$output .= "<ul class=\"toc\">\n";
		preg_match_all("/<h(\d) id=\"([^\"]+)\">(.*?)<\/h\d>/i", $content, $matches, PREG_SET_ORDER);
		$major = $minor = 0;
		foreach($matches as $match)
		{
			switch($match[1])
			{
				case 2:
					++$major; $minor = 0;
					$output .= $numbers ? "<li><a href=\"#$match[2]\">$major. $match[3]</a></li>\n"
						: "<li><a href=\"#$match[2]\">$match[3]</a></li>\n";
					break;
				case 3:	
					++$minor;
					$output .= $numbers ?  "<li><a href=\"#$match[2]\">$major.$minor. $match[3]</a></li>\n"
						: "<li><a href=\"#$match[2]\">$match[3]</a></li>\n";
					break;
			}
		}
		$output .= "</ul>\n";
		return $output;
	}
}

$yellow->plugins->register("etoc", "YellowEToc", YellowEToc::VERSION);
?>