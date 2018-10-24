# Seamless IFrames

This module makes it possible to seamlessly embed redcap pages inside other websites so that they appear as part of that page.  This includes automatic iframe height adjustment, preventing the need to scroll within the iframe.

## Set Up

Enable this modules on any projects that have pages that need to be embedded in an iframe.  Then perform the following steps on the embedding page:
1. Add the following line:
	- `<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.2/iframeResizer.min.js" integrity="sha256-aYf0FZGWqOuKNPJ4HkmnMZeODgj3DVslnYf+8dCN9/k=" crossorigin="anonymous"></script>`
1. Initialize the iframe per [the getting started section documented here](https://github.com/davidjbradshaw/iframe-resizer#getting-started).  You may have to call iFrameResizer after the "load" event fires for the iframe to prevent a target origin javascript error (as shown below).
1. Style the iframe as desired (adjust/remove borders, set the width, etc.).
1. **Optional:** Have the parent instruct the iframe to load specified css/js urls for further customization:
   ```
   var iframe = jQuery('iframe')
   iframe.on('load', function(){
     iframe.iFrameResize({
       initCallback: function(){
         iframe[0].iFrameResizer.sendMessage({
           message: 'load resources',
           resources: [
             'http://url.to/style.css?123',
             'http://url.to/script.js?456'
           ]
         })
       }
     })
   })
   ```