{!! $xmlDefinition !!}{!! $xslLink !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach( $data as $entry )
<url>
  <loc>{{ $entry['url'] }}</loc>
  <lastmod>{{ $entry['lastmod'] }}</lastmod>
  <changefreq>{{ $entry['changefreq'] }}</changefreq>
  <priority>{{ $entry['priority'] }}</priority>
</url>
@endforeach
</urlset>
