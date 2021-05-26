# grunt-git-authors

A [grunt](https://github.com/gruntjs/grunt) plugin for generating a list of
authors from the git history.

Support this project by [donating on Gratipay](https://gratipay.com/scottgonzalez/).

This project supports both a [Node API](#node-api) and a [Grunt API](#grunt-api).

## Grunt compatibility

v1.1.0+ is compatible with Grunt 0.4. If you're using Grunt 0.3, use v1.0.0.

## Grunt API

### Tasks

#### authors

Generates a list of authors in the form `Name <email>` in order of first
contribution.

This task writes its output to the console, not to a file.

You can optionally run this task against a subdirectory:

```sh
grunt authors:path/to/directory
```

#### update-authors

Creates or updates the file `AUTHORS.txt` with the list of authors in order
of first contribution.

You can optionally run this task against a subdirectory (the `AUTHORS.txt`
file will be placed inside that directory):

```sh
grunt update-authors:path/to/directory
```

### Config

#### authors.order

Define the order of the list of authors. The default ordering is by first
contribution `{order: "date"}`. An alternative ordering is by number of
contributions `{order: "count"}`.

*NOTE: This config value is used for the `update-authors` task as well.*

```js
grunt.initConfig({
	authors: {
		order: "count"
	}
});
```

#### authors.prior

Define a list of authors that contributed prior to the first commit in the repo.
This is useful if you've moved from another version control system.

*NOTE: This config value is used for the `update-authors` task as well.*

```js
grunt.initConfig({
	authors: {
		prior: [
			"Jane Smith <jane.smith@example.com>",
			"John Doe <john.doe@example.com>"
		]
	}
});
```

## Node API

This module can also be used directly via `require( "grunt-git-authors" )`.

### getAuthors( options, callback )

Gets the list of authors in order of first contribution.

* `options` (Object)
  * `dir` (String): Which directory to inspect for authors (defaults to `"."`).
  * `priorAuthors` (Array): An array of authors that contributed prior to the first commit in the repo.
* `callback` (`function( error, authors )`): A callback to invoke with the list of authors.
  * `authors`: An array of authors in the form of `Name <email>`.

### updateAuthors( options, callback )

Creates or updates an authors file with all authors in order of first contribution.

* `options` (Object)
  * `dir` (String): Which directory to inspect for authors (defaults to `"."`).
  * `priorAuthors` (Array): An array of authors that contributed prior to the first commit in the repo.
  * `filename` (String): Which file to create (defaults to `"AUTHORS.txt"`).
  * `banner` (String): Text to place at the top of the file (defaults to `"Authors ordered by first contribution"`).
* `callback (`function( error, filename )`): A callback to invoke after writing the file.
  * `filename`: The path of the file that was written.

## Mailmap

This task respects mailmap, so if you have messy author info in your commits,
you can correct the data in your mailmap and this task with output the cleaned
up information. For more information, about using a mailmap, see the docs for
`git-shortlog` or read Shane da Silva's blog post about
[Git Shortlog and Mailmap](http://shane.io/2011/10/07/git-shortlog-and-mailmap.html).

## License

Copyright Scott González. Released under the terms of the MIT license.

---

Support this project by [donating on Gratipay](https://gratipay.com/scottgonzalez/).
