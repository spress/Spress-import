Import plugin for Spress
========================
[![Build Status](https://travis-ci.org/spress/Spress-import.svg?branch=master)](https://travis-ci.org/spress/Spress-import)

This plugin let you import posts and pages from others platforms to a Spress site.

## Platforms supported
* [CSV files](#csv-files).
* [Wordpress WXR files](#wordpress-wxr-files).

# Requirements
* php >= 5.5.
* [Spress](http://spress.yosymfony.com) >= 2.1.3.
* [Composer](https://getcomposer.org/).

## How to install?
1. Go to `your-spress-site/` folder.
2. Run `composer require spress/spress-import`.
3. When you run `spress` command, import commands will be displayed under `import` namespace.

## How to use?
See the concrete provider.

## Source permalinks
Import plugin tries to preserve the source permalink of each item. To reach that
goal, this plugin adds the attributes: `permalink` and `no_html_extension`.

e.g: for an item with the following permalink at source: `http://acme.com/what-is-new-this-time`
the front matter block generated will be:

```yaml
---
permalink: '/what-is-new-this-time'
no_html_extension: true
---
```

### CSV files
This command imports posts from a CSV file.

The sign of `import:csv` command is the following:

```bash
import:csv [--dry-run] [--post-layout POST-LAYOUT] [--not-replace-urls]
[--not-header] [--delimiter-character DELIMITER-CHARACTER]
[--enclosure-character ENCLOSURE-CHARACTER]
[--terms_delimiter_character TERMS-DELIMITER-CHARACTER] [--] <file>
```

Example of use:
```bash
$ spress import:csv /path-to/post.csv --post-layout=post
```
#### Options
* `--dry-run`: This option displays the items imported without actually modifying your site.
* `--post-layout`: Layout applied to posts. e.g: `--post-layout=post`.
* `--not-replace-urls`: Avoids to replace URLs in posts by local Spress URLs.
* `--not-header`: First row won't be treated as header.
* `--delimiter-character`: Sets the delimiter character. character `,` by default.
* `--enclosure-character`: Sets the enclousure character. character `"` by default.
* `--terms_delimiter_character`: Sets the delimiter character applied to terms in categories and tags columns.

#### CSV structure

Your CSV file will be read in with the following columns:

1. **title**
2. **permalink**
3. **content**
4. **published_at**
5. **categories** (optional): a list of terms separated by semicolon. e.g:
"news;events".
6. **tags** (optional): a list of terms separated by semicolon.
7. **markup** (optional) markup language used in content. e.g: "md", "html".
"md" by default. This value will be used as filename's extension of the imported item.

#### Item attributes
List of attributes added by this provider to each item:

* `categories`: list of terms that represents the categories.
* `tags`: lists of terms that represents the tags.

### WXR files from Wordpress
This command imports posts from a WXR file generated by Wordpress (community and dot com).

The sign of `import:wordpress` command is the following:

```bash
import:wordpress [--dry-run] [--post-layout POST-LAYOUT]
[--fetch-images] [--not-replace-urls] [--assets-dir ASSETS-DIR] [--] <file>
```
Example of use:
```bash
$ spress import:wordpress /path-to/my-wxr-file.xml --post-layout=post
```
#### Options
* `--dry-run`: This option displays the items imported without actually modifying your site.
* `--post-layout`: Layout applied to posts. e.g: `--post-layout=post`.
* `--fetch-images`: Fetch images used in the Wordpress blog.
* `--not-replace-urls`: Avoids to replace Wordpress URLs in posts by local Spress URLs.
* `--assets-dir`: Relative folder to `src` directory. `content/assets` by default.

#### Item attributes
List of attributes added by this provider to each item:

* `author`: The author of the post.
* `excerpt`: The snippet of the post.
* `categories`: list of terms that represents the categories.
* `tags`: lists of terms that represents the tags.
