# Craft Vimeo Pro plugin for Craft CMS 3.x

Save video data as field from Vimeo Pro API

Adapted from [ACF Vimeo Pro Data](https://github.com/ttillberg/acf-vimeo-pro-data)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require /craft-vimeo-pro

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Craft Vimeo Pro.

## Craft Vimeo Pro Overview

-Insert text here-

## Configuring Craft Vimeo Pro

1. Add Vimeo Auth Token in Settings > Craft Vimeo Pro. (see https://developer.vimeo.com/api/start)
2. Create a new field with type `Vimeo Video`
3. Add field to Section entry type

## Using Craft Vimeo Pro

- The entire JSON object returned from Vimeo is stored as a string in the field
- In templates, use `{{ craft.craftVimeoPro.videoObj(YOUR_VIMEO_FIELDNAME) }}` to return JSON parsed as an object. 

Brought to you by [No Plans](https://no-plans.com/)
# craft-vimeo-pro-data
