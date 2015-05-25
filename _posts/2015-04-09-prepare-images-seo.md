---
layout: blog
title: How to prepare and optimise images for SEO
description: Two tips for getting images right the first time around, saving you plenty of effort later and helping boost your SEO.
metaimage: prepare-images-seo.jpg
categories: []
tags: []

---

A pretty common issue that clients miss is starting image optimisation on their websites, even though it’s the easiest SEO issue to fix.

The problems I see images fall into two camps:

1. Image filenames don’t make sense to anyone but the designers who exported them
2. Images haven’t been compressed, and if they have, it’s with some terrible format that isn’t recognised by Google.

Fortunately they’re both easy to solve, and with just two tweaks you’ll have a faster website and extra traffic from Google.

## Improving image file names

Let me guess: you’ve got a directory of files named something like `IMG_7786.JPG`. I’m currently working on a online travel store, where images follow a naming convention that makes total sense to the designers, but not so much for me or Google with titles like `MT282 UK Adaptor.JPG`. Okay, so at least that one is more descriptive than the first, but we can do better.

Best practice for naming files looks uses this:

- **Descriptive words**. Don’t worry about keywords, just use relevant words. If you were to Google for this image very thing, in one or two words, how would you put it? Will your team members immediately know what the image is like without opening it?
- **Lower-case characters**. Your file will ultimately form part of a web address, and should stay lower-case for the same reasons your website domain and email address stay lower-case.
- **Use hyphens instead of spaces or underscores**. Again, this is what you want from a good URL. A simple dash between each word works best. You don’t want underscores, they’re more common in the Chinese web, and spaces end up being translated as `%20` which just looks horrible and could give your developer a headache.

Let’s apply these simple rules-of-thumb to my plug adaptor image, originally called `MT282 UK Adaptor.JPG`. 

![UK plug adaptor box](http://www.digitalmarketingspecialist.co.uk/img/blog/uk-plug-adaptor-angle.jpg)

Following our new rules, we’re going to name it `uk-plug-adaptor-angle.jpg`.

I’d use this format for the full range of images, so the front-on shot would be called `uk-plug-adaptor-front.jpg` and from the back it would be `uk-plug-adaptor-rear.jpg`. You’ll always know what image to grab from a list without needing a preview, and the pictures will be grouped together neatly in alphabetical order.

## Compressing images

Images might be pretty small files, but compared to text on the page they’re pretty big. Your mobile visitors will have a relatively slow connection too, so squashing down your file sizes will keep your site fast and on the right side of Google.

### First, check you have the right image sizes

We’re assuming you have correctly size images from your designer already — this is going to save more space than anything else we can do, so check they’ve submitted suitable sizes rather than some huge high resolution image.

If you have a huge folder of images and need to resize them all without a designer, I have a pretty handy trick for doing the whole lot in literally 10 seconds — let me know in the comments if a video would be useful and I’ll put it together.

### Get an image compressor that Google will recognise

Not all image compressors are made equal. We want one that [Google’s Page Speed Tool] will register, or our efforts will go uncredited in search.

My favourite is [ImageOptim][imageoptim], a free Mac app which works in seconds. Here’s how it works:

1. Make a copy of your target image folder, just incase these are your only copies. The designer might want an untouched original someday to make some changes, though a good designer will have made a backup already.
2. Click your target image folder from the Finder and drag it to the ImageOptim icon.

The app will shortly work away at replacing your images with a smaller file, usually 5-20% smaller, and without any noticeable difference. The original file is being discreetly dropped in your Trash, so if you need to get it back for any reason, look in there.

It won't compress an image twice either, so you can relax about accidently running pictures through the app twice.

I find that `.jpg` files work pretty quickly, while `.png` files can be slow. Leave the app running if you’re not sure, it always seems to get there in the end.

## Conclusion

Now you have faster-loading images which are easier for your team to work with, and that are almost fully SEO-optimised with very little effort. Later we’ll look at improving `alt` tags.