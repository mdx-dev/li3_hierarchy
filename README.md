# [Lithium PHP](http://lithify.me) Plugin to allow for savvy template inheritance
***
> Don't get too excited, this project has only just started and it's currently fairly rough
***

## Installation
1. Clone/Download the plugin into your app's ``libraries`` directory.
2. Tell your app to load the plugin by adding the following to your app's ``config/bootstrap/libraries.php``:

        Libraries::add('li3_hierarchy');

## Usage
With this plugin you no longer assign views to layouts the same way as with core lithium templates.

#### Originial Method

__Your Layout__

~~~ php
<html>
	<head>...</head>
	<body>
		...
		<section><?php echo $this->content(); ?></section>
		...
	</body>
</html>
~~~

> This method blocks a single section in your layout, all content that reside within views will end up here.
>> While I dont think this is technically _bad_ it does seem a bit limiting

#### Hierarchical method

__Your Layout__

`layouts/default.html.php`

~~~ php
<html>
	<head>...</head>
	<body>
		...
		<section>{:block "content"}Default Content{block:}</section>
		<div id="sidebar">{:block "sidebar"}Default Sidebar{block:}</div>
		...
	</body>
</html>
~~~

> So here we have set 2 sections, `content` and `sidebar`. We can change these sections anytime from any view.

Here's how

__Home View__

`pages/home.html.php`

~~~ php
{:parent "layout/default.html.php":}

{:block "content"}
	<h2>Welcome to My site!</h2>
	This is my home page, please wipe your feet.
{block:}

{:block "sidebar"}
	<ul>
		<li><a href="#about">About Me</a></li>
		<li><a href="#github">My Github Profile</a></li>
	</ul>
{block:}
~~~

So you read this and think to yourself ... "ok, what's so special about that?"

__quit heckling, heckler!__

Now you want to create that "About Me" page. Because time is short and you're eager to go eat a sandwich you've decided that, for now, the only difference between it and the home page is the sidebar. You don't want it to show the link to the "About Me" page, but rather show the link to the "Home" page. ("wow, baffling example Josey!")

Well, rather than rewriting that page you simply assign it's parent to the home page and modify the sidebar block:

__About Me__

`pages/about.html.php`

~~~ php
{:parent "pages/home.html.php":}

{:block "sidebar"}
	<ul>
		<li><a href="#home">Home</a></li>
		<li><a href="#github">My Github Profile</a></li>
	</ul>
{block:}
~~~

> Ok, I realize this is a painfully simple example but I hope it gets the point across.

This becomes especially useful when dealing with large, complicated project, when certain pages only modify a section or two.

## Some Notes
1. This project currently uses like formatting of many "PHP Template Engines", but is not, in fact, a template engine itself. You would go about every other lithium layout/view effort exactly as before. This project only attempts to meet a need to offer more power to the rendering method of views.
2. The current formatting isn't set in stone, as this project is in its infancy it is subject to change dramatically.