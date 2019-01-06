# Git Book Plugin

This README is incomplete. The plugin is in its early conceptual stage.

_Git Book_ is a [Grav CMS](http://github.com/getgrav/grav) Plugin for rendering books stored in Git repositores.

We will probably need to change the name of this plugin, because of <https://gitbook.com>

- git-publish ?
- git-publishing ?

## Overview

## Installation

Installing the Git Book plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install git-book

This will install the Git Book plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/git-book`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `git-book`. You can find these files on [GitHub](https://github.com/aoloe/grav-plugin-git-book) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/git-book
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

### Admin Plugin

If you use the admin plugin, you can install directly through the admin plugin by browsing the `Plugins` tab and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/git-book/git-book.yaml` to `user/config/plugins/git-book.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

Note that if you use the admin plugin, a file with your configuration, and named git-book.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.

## Usage

**Describe how to use the plugin.**

## Features

## Credits

**Did you incorporate third-party code? Want to thank somebody?**

## Development Notes

- [Grav Page Inject Plugin](https://github.com/getgrav/grav-plugin-page-inject)
  - Inject entire pages or page content into other pages using simple markdown syntax
  - Generally, very similar to what we want to achieve. Maybe, even, the solution to our problem.
  - Shows how to enable the plugin only on specific pages.

### Things to be explored

- Show each page of the book with its own (sub) url.
- Convert the links to `.md` file to a valid Grav link / How to manage the book's internal links.
  - The links should be clickable in the Github web interface.
- Rewrite the image links (if needed).
  - The images should correct render in the Github web interface.
- Add comments to each page
  - Without any non-free comments engine.
  - <https://github.com/sommerregen/grav-plugin-jscomments> ?
  - <https://github.com/getgrav/grav-plugin-comments>
- Cache the html files.
    - Refresh the cache on git pull.
    - The webhook can trigger a grav command, if needed.

## To Do

- [ ] Future plans, if any
