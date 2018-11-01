{!! $xmlDefinition !!}{!! $xslLink !!}
<sitemapindex>
@foreach( $sitemaps as $sitemap )
<sitemap>
  <loc>{{ $sitemap->url }}</loc>
  <lastmod>{{ $sitemap->getLastMod() }}</lastmod>
</sitemap>
@endforeach
</sitemapindex>
