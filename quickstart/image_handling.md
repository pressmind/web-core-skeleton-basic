# Image Handling
the pressmind sdk get's images from the pressmind application and
generated several defined image derivates.

Fst, we have to understand the concept inside the pressmind PIM application:

* each media object can contain several images lists. 
* each image can contain multiple user defined croppings.
* each image has media object specific metadata like caption, copyright, alt, title

## Images Features
* generates several images formats (width, height, quality)
* generates optimized jpegs and webp
* stateless derivate generation
* optional storage provider aws s3

## Workflow
Pressmind delivers one or more images per media object.
Each image can have multiple formats and ratios

Example:
 media object
 * image 1 original 5000x6000px 
   * user defined cropping 400x300px
   * user defined cropping 200x200px
 * image 2 original 4000x4000px
   * user defined cropping 400x300px
   * user defined cropping 200x200px
    
All image derivates are generated during import. 
The image generation processes are running in the background for a while. 
If the image is not generated yet, the pressmind sdk delivers a temporary image link
instead the local version of the image file.

### How to configure
All configuration can be done in config.json.
See [Configuration Documentation](config.md) for detailed properties.

After changing this values you have to rebuild each image:

```shell
php import.php fullimport
```
