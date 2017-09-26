# Formstack plugin for Craft CMS

Plugin to connect to Formstack and display available forms in a dropdown field type.

![Screenshot](resources/screenshots/plugin_logo.png)

## Installation

To install Formstack, follow these steps:

1. Download & unzip the file and place the `formstack` directory into your `craft/plugins` directory

2.  -OR- do a `git clone https://github.com/trendyminds/formstack.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`

3.  -OR- install with Composer via `composer require trendyminds/formstack`

4. Install plugin in the Craft Control Panel under Settings > Plugins

5. The plugin folder should be named `formstack` for Craft to see it.  GitHub recently started appending `-master` (the branch name) to the name of the folder for zip file downloads.

Formstack has been tested on Craft 2.6.x.

## Formstack Overview

Craft Formstack plugin allows you to pull in your Formstack forms via the API. Since the Formstack Form Builder doesn't allow you to add custom elements (css classes, js, etc...) without manually embedding the form, Craft Formstack pulls the form into a `textarea` for further editing.

## Configuring Formstack

After installation, head to the `Formstack` settings panel and enter your Formstack API token.

## Using Formstack

* Create a new Field using the added Formstack Form field type.
* To template your new form, add it to your entry like so (handle being your field handle): `{{ entry.handle.edited | raw }}`.

## Formstack Form Submission

By default, your form will submit using Formstacks method (page redirect). An alternative submission method has been provided as well which will allow you to submit your Formstack Form via AJAX. You'll just need to submit a `POST` request to `formstack/submitForm/sendForm`, how you do that is up to you! Just make sure to grab your form data using `FormData()`. 

Craft will also require you to append the CSRF token to submit it through the action url. Example CSRF and Action URL in twig template:
```
{% set js %}  
  window.csrfTokenName = "{{ craft.config.csrfTokenName|e('js') }}";
  window.csrfTokenValue = "{{ craft.request.csrfToken|e('js') }}";
  window.actionUrl = "{{ actionUrl('formstack/submitForm/sendForm') }}";
{% endset %}
{% includeJs js %}
```

## Formstack Roadmap

* Provide example of AJAX submission.
* Make Formstack API calls less often. (~14k/day limit)

Brought to you by [TrendyMinds](https://trendyminds.com)
