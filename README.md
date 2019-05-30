# GreenpeaceEA Planet 4 - Shortcodes

This WordPress plugin provides blocks to be used with Shortcake UI plugin.
This plugin is developed by Greenpeace Netherlands to be used in the Planet4 engagement platform.

Currently this plugin adds the following new shortcodes:
1. Donation form
2. Petition form
3. No-index (to discourage search engines from indexing)
4. Liveblog

Coming in the near-future (available on dev-branch)
1. Quote block with image
2. Statistics block in 3 columns
3. New hero image header

In the backlog are among others:
* Information request form
* interactive maps

## How to develop a new block you ask?

1. Create a new controller class that extends Controller inside directory _classes/controller/blocks_. The class name should follow naming convention: GPEA_**Blockname**_Controller and its file name should be class-**blockname**-controller.php. 

2. Implement its parent's class two abstract methods:
* In method **prepare_fields()** you need to define the blocks fields 
* In method **prepare_template()** you need to prepare them for rendering.

3. Create the template file that will be used to render your block inside directory _includes/blocks_. If the name of the file is gpea_**blockname**.twig then
you need to set the BLOCK_NAME constant as gpea_**'blockname'**

4. Add your new class name to the array that the `P4BLBKS\Loader` function takes as an argument in `planet4-gpea-blocks.php`.

5. Finally, before committing do `composer update --no-dev && composer dump-autoload --optimize` in order to add your new class to composer's autoload.

## And how do i build new assets?

Asset sources are located in:

`/includes/assets/`
  1. `/js/src/`
  2. `/css/scss/`

Gulp builds the buildfiles into the parent directories of the sourcesfiles (so `/js/` and `/css`/). Maps are placed in `/maps/`...

Using your favorite package manager `install` the required packages from package.json and simply run `gulp watch` or `gulp`.

## Known issues
Currently Firefox does not read the maps correctly. Chrome/Chromium does.
