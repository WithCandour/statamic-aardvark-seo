# Aardvark SEO Documentation

## Installation

Once you have a license for the addon you will be able to download the addon files from the Marketplace. The `AardvarkSeo` directory should be placed in you `site/addons` directory.

There is only one setting that will need to be configured after installation, we add image fields to pages, as a result we ask that you tell the addon which asset container to use for storing any images that get uploaded. This can be set on the `cp/addons/aardvark-seo/settings` page.

Once successfully installed a new 'SEO' item will appear in the control panel navigation - under 'Tools'. This is where you can manage most of the global SEO settings for the site.

### Tags

Getting your site's SEO data onto the page relies on a few tags being present in your theme templates:

- `{{ aardvark-seo:head }}` - Contains meta tags and other information, this tag should be inside of the `<head>` element (in place of any title or meta tags)
- `{{ aardvark-seo:body }}` - Contains scripts that need to be inside of the `<body>` element, it should be placed after the opening `<body>` tag.
- `{{ aardvark-seo:footer }}` - Contains any scripts that need to be included at the end of the page, it should be placed towards the end of page along with any other scripts you have in the footer.

## Sitemaps

XML Sitemaps will get automatically generated for your site, the default url for the sitemap is `<your-site-address>/sitemap.xml`, however, you are welcome to change this in the Aardvark settings as well as turn off sitemaps all together.

The priority and change frequency can be configured on a per-page basis under the 'SEO' section.

## Redirects

You can manage the list of redirects for your site from within the control panel, the SEO > Redirects page is the place to go for this feature.

Redirects can be added manually, we also have an experimental feature which will detect when the url segment for pages change or when pages are moved within the site tree. This is off by default but once enabled will reduce the number of 404 errors that users may encounter on the site.

## Marketing tools

Google tag manager can be enabled and managed through the Aardvark addon, additionally there is functionality to add verification codes for the major webmaster tools under the SEO > Marketing page.

## On-page SEO

A new 'SEO' section will be added to the editor screen for any Pages, Collection entries or Taxonomy terms from which the SEO and share data will be managed.

Special fields for the meta title and description will give you hints about the length of the content enabling you to optimize your metadata for search engines - a google search preview will help to visualise this.

### Disable
You can disable the SEO tab on a per-fieldset basis, simply add a `hide_aardvark_seo` key to your `[fieldset].yaml` file.

Example:
```yaml
title: Page
create_title: 'Create a new page'
taxonomies: true
hide_aardvark_seo: true
```

## Indexing

Site indexing can be controlled either at the site-level (crawlers will not index any page) or on a per-page basis. On every page there is a toggle, when enabled the page will no longer get indexed. In addition there is a separate option for controlling whether on-page links should get followed by crawlers.

## JSON-LD

The addon will generate a snippet of json-ld from the person/organization data that gets provided under the SEO > General page.

### Breadcrumbs

Another json-ld attribute that will get automatically generated is the breadcrumb trail, Google provides [documentation](https://developers.google.com/search/docs/data-types/breadcrumb) about the specifics and how it is used. Aardvark automatically adds json-ld breadcrumbs for all pages on your site.

## Social media

Aardvark provides you with full control over how your site looks when shared on social media through generating data and filling opengraph and twitter meta tags. In addition you can set links to each of your social media profiles in the SEO > Social menu.

The social media data can be accessed on the frontend through the `{{ aardvark-seo:socials }}` tag, use it to loop through the provided social media links.

### Socials tag example

```html
<ul>
    {{ aardvark-seo:socials }}
        <li><a href="{{ url }}">{{ social_icon }}</a></li>
    {{ /aardvark-seo:socials }}
</ul>
```
