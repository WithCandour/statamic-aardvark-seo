# Aardvark SEO

Designed and built from the ground up by a dedicated SEO agency, the Aadvark SEO addon gives you full control that no other SEO plugin does, from managing directs to generating schema.

View the full documentation on the [Statamic Marketplace](https://statamic.com/marketplace/addons/aardvark-seo/docs).

## Development

A Dockerfile has been included to get the AardvarkSeo plugin up and running in an Statamic Environment, It can be built
and launched with the following commands:

```
docker build -t statamic .
docker run -v $(pwd)/AardvarkSeo:/var/www/html/site/addons/AardvarkSeo -p 3000:3000 statamic
```