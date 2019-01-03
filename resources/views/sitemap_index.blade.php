{!! $xmlDefinition !!}{!! $xslLink !!}
<sitemapindex>
@foreach( $sitemaps as $sitemap )
  @if( count($sitemap->getSitemapItems()) >= 1 )
    <sitemap>
      <loc>{{ $sitemap->url }}</loc>
      <lastmod>{{ $sitemap->getLastMod() }}</lastmod>
    </sitemap>
  @endif
@endforeach
</sitemapindex>
