# Git Publishing Plugin

This README is incomplete. The plugin is in its early conceptual stage.

_Git Publishing_ is a [Grav CMS](http://github.com/getgrav/grav) Plugin for rendering books and manuals stored in Git repositores.

## Overview

## Installation

Installing the Git Book plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install git-publishing

This will install the Git Book plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/git-publishing`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `git-publishing`. You can find these files on [GitHub](https://github.com/aoloe/grav-plugin-git-publishing) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/git-publishing
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

### Admin Plugin

If you use the admin plugin, you can install directly through the admin plugin by browsing the `Plugins` tab and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/git-publishing/git-publishing.yaml` to `user/config/plugins/git-publishing.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

Note that if you use the admin plugin, a file with your configuration, and named git-publishing.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.

## Usage

### Activating

By default, the _Git Publishing_ pluging is not active. You normally will activate on a per page or module basis:

```yaml
---
git-publishing:
    active: true
---
```

### Reading the book settings

The page's headers needs to contain the address of the project file:

```yaml
---
git-publishing:
    active: true
    project: book/project-grav.yaml
---
```

### Defining the book settings

The book needs a yaml file defining:

- `content_dir`: the place where the content is stored.
- `toc`: the markdown file containing the table of contents.
- `title`: the title of the book.
- `chapters`: the list of the chapters (the file name without the language code and the markdown extension).

Optionally, it can define:

- the available languages

### Picking the languages

- The project settings define the default (or only language) of the book.
- The page can define the current language (is multiple ones are available).
- The project settings can define alternate languages that can be used if the requested file is not available in the current language.
- Each language is a Grav page.

### Config defaults

If you want to change the default values, copy the `git-publishing.yaml` file into your users/config/plugins/ folder (create it if it doesn't exist), and then modify thethere. This will override the default settings.

## Features

## Credits

**Did you incorporate third-party code? Want to thank somebody?**

## Development Notes

- [Grav Page Inject Plugin](https://github.com/getgrav/grav-plugin-page-inject)
  - Inject entire pages or page content into other pages using simple markdown syntax
  - Generally, very similar to what we want to achieve. Maybe, even, the solution to our problem.
  - Shows how to enable the plugin only on specific pages.

## Development Log

- Create the plugin
- Inspired by [Grav Page Inject Plugin](https://github.com/getgrav/grav-plugin-page-inject), activate the plugin on a single page (`mergeConfig() + get()`)
- Inspired by [Grav Directory Listing Plugin](https://github.com/OleVik/grav-plugin-directorylisting), get the current path of the page
- Inspired by [Grav Import Plugin](https://github.com/Deester4x4jr/grav-plugin-import), read the yaml project file.
- Changed to use the Grav's own yaml reading API: https://learn.getgrav.org/api#class-grav-common-file-compiledyamlfile
- According to [Error Display](https://learn.getgrav.org/advanced/debugging#error-display), errors are triggered as exceptions.
- Append the markdown content of the book's file to the page's content: Using `system/src/Grav/Common/Markdown/Parsedown.php` could allow a few more Grav specific Markdown features to be enabled. But we probably do not need them for now and can directly extend Parsedown.
- Inject a base dir for images
- Convert the internal links to links that work in Grav
- I'm stuck at catching the 404 before it is "calculated" and (if i'm on a page with the plugin, change the uri).
 - according to a tip in discord, i have to look at the login plugin
 - i did look at it (`storeReferrerPage()`), but it would not work: it sets the current url in the session and redirects: which changes the url in the address bar.
- what about the [regex based route aliases](https://learn.getgrav.org/content/routing#regex-based-aliases)?
  - you have to set it in `user/config/site.yaml`
  -

   ```yaml
     routes:
       '/learn/starting-with-scribus/(.*)': '/learn/starting-with-scribus/'
   ```
  - it does work but, can we do better?
    - setting the route dynamically from the plugin?
- For the images, I need the relative path to the markdown file "defining" the book. I can get the `$page->media()->path()`, but that's an absolute path.
- For the links, I need the latest item in the route.
- Now, the markdown files are correctly rendered, but the code is still of bad quality.

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

- [ ] Correctly define the `project-grav.yaml` file
  - [ ] What if there is only one language? (no `-en`).
  - [ ] How to join multiple files in one chaptger.
  - [ ] Render the content of the `.md` file only if no chapter is selected.
