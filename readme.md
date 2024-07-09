# WP CSS Optimisation

## What

This plugin is designed to optimise the CSS of a WordPress site. It does this by:

- Combining all CSS files into one file
- Minifying the CSS
- Removing any unused CSS
- loading only that stylesheet on the front end, blocking **all** other stylesheets (unless permitted in the settings)

## Why

You have to pay for plugins such as WP Rocket, and don't necessarily need all the other features. This plugin is designed to be a more lightweight, free alternative. It might not be as convienient, but it will do the job.

## How

The plugin is broken into two parts:

1. The wordpress plugin (this repo)
2. [The self-hostable API](https://github.com/NetSpud/css-optimiser-api)

### The WordPress Plugin

The WordPress plugin is the main part of the plugin. It is responsible for configurations such as:

- Which stylesheets to block
- Which stylesheets to allow
- API Endpoint URL
- It also handles which pages are optimised, as well as the metabox added to the post editor which displays the selected configuration and its respective optimised CSS file.

### The API

The API is a self-hostable API that is responsible for the actual optimisation of the CSS. It is a simple API that takes a URL and returns the optimised CSS. It is designed to be self-hosted so that you can have full control over the optimisation process, and if you choose to, alter it yourself. The API is written in Node.js, and uses the `postcss` and `PurgeCSS` libraries to optimise the CSS, and `express` to host the API.

## Installation

### Plugin

Download the latest release from the releases page and upload it to your site.

### API

Please visit the API repo at: https://github.com/NetSpud/css-optimiser-api and follow the steps there.

## Configuration
<a href="https://ibb.co/d0VKDn6"><img src="https://i.ibb.co/Lzcp146/wp-2.png" alt="wp-2" border="0"></a>

The settings page has three options, and can be accessed from "Settings > CSS Optmisation".

* API Endpoint - this must be set, and allows the plugin to communicate with the API (If it's running on the same server, you can use localhost)
* Excluded URLs - if there are any stylesheets that you do not wish to be optimised, enter their path or filename here.
* Permitted, loadable stylesheets - by default, when the "optimised" mode is enabled, it blocks the loading of any other stylesheets on the page, in an effort to increase the page performance. However, if you have a stylesheet that loads of every page, you can allow it to be loaded here. This is especially useful when used in combination with the "Excluded URLs", as you can specify which files can still be loaded and excluded them from your optimised stylesheet.

## Usage

<a href="https://imgbb.com/"><img src="https://i.ibb.co/2dPV0d6/wp-1.png" alt="wp-1" border="0"></a>

All posts and pages will now have a box at the top right, with options to specific if each page should be configured. There are two modes:
* Default - does nothing, useful for pages that do not require optimising
* Performance Mode - optimises pages

To use, hit the generate stylesheet button. For new pages or posts that have not been optimised, this will run on the first update of the page.
Every time you need to regenerate the stylesheet, you just click that button.

<a href="https://imgbb.com/"><img src="https://i.ibb.co/NyQ3tCT/wp-3.png" alt="wp-3" border="0"></a>

(Notice the missing button, it will appear once the page/post has been updated)

## Negatives/Drawbacks

Although I'd love to make this plugin work in every scenario, there are some limitations. Each time a page is optimised, it generates a new CSS file. This means that if you have a lot of pages you decide to optimise, you will have a lot of CSS files, which could eat away at your disk space. In the future, it'd be good to implement some form of hashing so that if a page uses the same styles, it loads the same file instead of generating a new one.
