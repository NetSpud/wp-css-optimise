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

Please visit the API repo at: https://github.com/NetSpud/css-optimiser-api



## Negatives/Drawbacks

Although I'd love to make this plugin work in every scenario, there are some limitations. Each time a page is optimised, it generates a new CSS file. This means that if you have a lot of pages you decide to optimise, you will have a lot of CSS files, which could eat away at your disk space. In the future, it'd be good to implement some form of hashing so that if a page uses the same styles, it loads the same file instead of generating a new one.
