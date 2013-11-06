Demo Builder
============

## Presentation

This package is a PHP helper to quickly construct, visualize and download some demonstration
pages in HTML5/CSS3. It is based on [jQuery](http://jquery.com/), [Bootstrap](http://twitter.github.com/bootstrap/index.html)
and [HTML5shiv](http://code.google.com/p/html5shiv/).

**This package is first a personal utility for my work ...** But you can use it in your work if you find it useful ;)

## How does it work ?

To build a demonstration website, the `DemoBuilder` package will list the content of a directory following these rules:

-   any sub-directory containing a file with its own name (*and an extension*) will be considered as a demo page,
-   only sub-directories that did NOT begin with an underscore are used as demo pages,
-   all assets media files (*any relative path in the page content*) will refer to the whole page root OR to the corresponding
    file in the sub-directory if so,
-   the content we are talking about can be a plain text file (*with a `txt` extension*), a [Markdown](http://daringfireball.net/projects/markdown/)
    file (*with a `md` extension*) or plain HTML file (*with a `html` extension*).

## Rules of configuration file

The rules below discribe the different configuration entries for a project or a package. They
are mostly chosen to be compliant with common `manifest`(s) standards, such as [the composer manifest](http://getcomposer.org/doc/04-schema.md)
or the [jQuery plugins manifest](http://plugins.jquery.com/docs/package-manifest/).

A configuration file must contain these definitions. It can be either a `json` file (*usually*),
a true PHP array defined in a `php` file or an array defined in a `ini` file.

### Name & Title

The `title` of the package is the real, pretty & human readable, string to name the project.

The `name` is a unique string to identify the package. It is mostly constructed like
`vendor/project`. By default, the package name will be the transformed value of the title.

### Version & State

The `version` entry must follow [this Composer rule](http://getcomposer.org/doc/04-schema.md#version),
it is constructed like `X.Y.Z` with, eventually, a suffix about the current release state.

The `state` is optional. Use it if your project is not yet `stable`.

### Description & Slogan

The `description` is a long plain string describing the package, what it does and what is its
goal.

The `slogan` is another string to write in demo headers to describe the package in another way.
By default, the slogan will be the description.

### Time

Optionally, a `time` field can be defined to inform about the version last build. This field must
be constructed like:

    YYYY-MM-DD or YYYY-MM-DD HH:MM:SS

### Keywords

A list of words or tags that the project is related to. The keywords may give quick informations
about the functionality or usage of the package.

### On the web

Your configuration can contain a list of entries, URLs to go or emails to contact, for each action like:
-   `homepage` or `url` : the project's website
-   `support` or `bugs`: the bug tracker's website
-   `demo` : the online demonstration of the package
-   `wiki` : a wiki website to discuss about the project
-   `forum` : a forum website about the project
-   `doc` or `docs` : the online documentation of the package

### Sources Repository

The `repository` or `sources` field must follow these rules:
-   `url` : the URL (`git`, `svn`, `http` or `ssh`) to get the sources
-   `type` : the type of sources hosting (`git`, `svn`, `hg`, `pear` ...)
-   `name` : the name of the website hosting the sources
-   `tags` : a list of tags you want to put ahead

As it is commonly used, and because this field is required, the default `repository` is
set on a [GIT](http://git-scm.com/) repository hosted by [GitHub.com](https://github.com/).
The name of the repository is built like:

    https://github.com/[name of your project]

The tags will be automatically loaded from the repository if they are named using the version
number as:

    (v)X.Y.Z-suffix // the first "v" is optional

### Authors

The `authors` entry is a list of persons. You can set a (*singular*) `author` field if
you are alone.

A `person` field is an array entry with infos like `name`, which is required (*well, nobody has no name*), 
`email`, `homepage` or `url` and a `role`. There is no default value except for `role`, which have
`Developer` by convention as default value.

    "authors": [
        {
            "name": "My Name",
            "email": "me@mail.com",
            "homepage": "http://www.website.fr",
            "role": "Developer"
        },
        {
            "name": "Another Name",
            "email": another@mail.fr",
            "url": "http://www.website.fr",
            "role": "Maintainer"
        }
    ]

### Licenses

The `licenses` entry is a list of licenses. You can set a (*singular*) `license` field if
your project is under this one only.

A `license` field is an array with a `type`, its short name, and an optional `url` to read it online.
For more infos about *open source* licenses, see <http://opensource.org/licenses/alphabetical>.

    "licenses": [
        {
            "type": "GPLv2",
            "url": "http://www.example.com/licenses/gpl.html"
        }
    ]

### Dependencies

The `dependencies` entry is a list of other packages your project depend on.

A `dependency` field is an array constructed like:

    "name": {
        "version": "1.0.*",
        "url": "git://..."
    }

### Compatibilities & Incompatibilities

The `compatibilities` entry is a list of other packages your project is compatible with.
The `incompatibilities` entry is a list of other packages your project is NOT compatible with or
generate conflicts with.

A `compatiblity` field is constructed like a `dependency`.


## Open-Source & Community

This plugin is a free software, available under [General Public License version 3.0](http://opensource.org/licenses/GPL-3.0) ; 
you can freely use it, for yourself or a commercial use, modify its source code according to your needs, 
freely distribute your work and propose it to the community, as long as you let an information about its first author.

As the sources are hosted on a [GIT](http://git-scm.com/) repository on [GitHub](https://github.com/pierowbmstr/DemoBuilder),
you can modify it, to ameliorate a feature or correct an error, by [creating your own fork](https://help.github.com/articles/fork-a-repo)
of this repository, modifying it and [asking to pull your modifications](https://github.com/pierowbmstr/DemoBuilder/pulls) on
the original branch.

Please note that the "master" branch is **always the latest stable version** of the code. 
Development is done on branch "dev" and you can create a new one for your own developments.

## Author & License

>    DemoBuilder PHP package

>    https://github.com/pierowbmstr/DemoBuilder

>    Copyleft 2013, Pierre Cassat

>    Licensed under the GPL Version 3 license.

>    http://opensource.org/licenses/GPL-3.0

