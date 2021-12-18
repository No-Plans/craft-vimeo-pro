# Craft Vimeo Pro plugin for Craft CMS 3.x

Save video data as field from Vimeo Pro API.

Adapted from [ACF Vimeo Pro Data](https://github.com/ttillberg/acf-vimeo-pro-data)

## Requirements

- Craft CMS 3.0.0 or later.
- Vimeo Pro plan or higher


## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require /craft-vimeo-pro

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Craft Vimeo Pro.

## Craft Vimeo Pro Overview

Adds a searchable field to import Vimeo videos to Craft.

<img width="865" alt="Screen Shot 2021-12-17 at 4 49 40 PM" src="https://user-images.githubusercontent.com/742229/146623194-8ba9ecd3-0f3a-4c47-a60b-e07b36cfd95f.png">

<img width="861" alt="Screen Shot 2021-12-17 at 4 50 04 PM" src="https://user-images.githubusercontent.com/742229/146623233-6db29715-ed9b-4ccc-b094-a1fba562e748.png">


## Configuring Craft Vimeo Pro

1. Add *Vimeo Auth Token* in `Settings > Craft Vimeo Pro`. (see https://developer.vimeo.com/api/start)
2. Create a new field with type `Vimeo Video`
3. Add field to Section entry type

## Using Craft Vimeo Pro

- The entire JSON object returned from Vimeo is stored as a string in the field
- In templates, use `{{ craft.craftVimeoPro.videoObj(YOUR_VIMEO_FIELDNAME) }}` to return JSON parsed as an object. 

Brought to you by [No Plans](https://no-plans.com/)
