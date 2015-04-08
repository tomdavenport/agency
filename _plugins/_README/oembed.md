# OEmbed Liquid Tag for Jekyll
[This](#file_oembed.rb) is a simple liquid tag that helps to easily embed images, videos or slides from OEmbed enabled providers. It uses Magnus Holm's great oembed gem which connects to the OEmbed endpoint of the link's provider and retrieves the HTML code to embed the content properly (i.e. an in-place YouTube player, Image tag for Flickr, in-place slideshare viewer etc.). By default it supports the following OEmbed providers (but can fallback to Embed.ly or OoEmbed for other providers):

 - Youtube
 - Flickr
 - Viddler
 - Qik
 - Revision3
 - Hulu
 - Vimeo
 - Instagram
 - Slideshare
 - Yfrog 
 - MlgTv

## How to install
1. Make sure you have the `ruby-oembed` gem ([Rubygems](http://rubygems.org/gems/ruby-oembed), [Github](https://github.com/judofyr/ruby-oembed/)) installed.
2. Copy `oembed.rb` to `<your-jekyll-project>/_plugins`
3. You're done.

If you're experiencing troubles with Ruby 2.x, please also add `require 'openssl'` to the script.

## How to use
Place a `oembed` tag in your content file. E.g.

``` html
<h1>An embedded video</h1>
{{ oembed http://www.youtube.com/watch?v=Sv5iEK-IEzw }}

<h1>An embedded presentation</h1>
{{ oembed http://www.slideshare.net/AmitRanjan/quick-tour }}
```

The oembed tag behaves almost compatible to Robert Böhnke's [Embed.ly Tag](https://github.com/robb/jekyll-embedly-client), i.e. it wraps the embed code in a `<div>` tag that has classes matching the embeds type, provider as well as the generic `embed`. In contrast to the embed.ly tag, we don't support overriding certain oembed properties.

## Author
Tammo van Lessen -- http://www.taval.de

## License
This code snippet is licensed under [Apache License 2.0](http://www.apache.org/licenses/LICENSE-2.0)
