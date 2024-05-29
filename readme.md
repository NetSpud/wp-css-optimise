# CSS Optimisation

## What

This plugin is designed to optimise the CSS of a WordPress site. It does this by:

- Combining all CSS files into one file
- Minifying the CSS
- Removing any unused CSS
- loading only that stylesheet on the front end, blocking **all** other stylesheets (unless permitted in the settings)

## Why

You have to pay for plugins such as WP Rocket, and don't neccessarily need all the other features. This plugin is designed to be a more lightweight, free alternative. It might not be as convienient, but it will do the job.

## How

The plugin is broken into two parts:

1. The wordpress plugin (this repo)
2. The self-hostable API

### The WordPress Plugin

The WordPress plugin is the main part of the plugin. It is responsible for configurations such as:

- Which stylesheets to block
- Which stylesheets to allow
- API Endpoint URL
  It also handles which pages are optimised, as well as the metabox added to the post editor.

### The API

The API is a self-hostable API that is responsible for the actual optimisation of the CSS. It is a simple API that takes a URL and returns the optimised CSS. It is designed to be self-hosted so that you can have full control over the optimisation process, and if you choose to, alter it yourself. The API is written in Node.js, and uses the `postcss` and `PurgeCSS` libraries to optimise the CSS, and `express` to host the API.

## Installation

### Plugin

Install plugin from wordpress.org, or by downloading the latest release from the releases page and uploading it to your site.

### API

run

```bash
npm install wp-css-optimise-api (to be published)
```

then run

```bash
npm start
```

Which will start the server, ready for requests.
