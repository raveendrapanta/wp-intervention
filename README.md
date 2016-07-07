# WP Intervention

WP Intervention provides on-demand image manipulation for WordPress via the [Intervention Library](http://image.intervention.io/).

All image manipulation is handeled via the [Intervention Library](http://image.intervention.io/). This Plugin merely provides a wrapper around Intervention and a convenient global function for use with WordPress.

This Plugin is aimed at __developers only__ and aims to be fully customisable via liberal usage of [WordPress filters](https://developer.wordpress.org/reference/functions/apply_filters/).

## Requirements

- PHP >=5.4
- Fileinfo Extension

## Supported Image Libraries

- GD Library (>=2.0)
- Imagick PHP extension (>=6.5.7)

Please refer to the [Intervention Library](http://image.intervention.io/) for full details.


## Usage

### Getting Started

- Install the Plugin and Activate
- Use the Plugin in your Theme or Plugin

### Basic Usage

WP Intervention exposes a global helper `wp_intervention` for use in your Theme. The API for this is

`wp_intervention( string $src, array $intervention_args [, array $options] );`

WP Intervention supports the [same API](http://image.intervention.io/) as the underlying Intervention Library. Indeed, under the hood the Plugin simply proxies all arguments as method calls to Intervention. An example is probably easier to understand...

```php
// Define a source image
$original_img = "..."; // some image url or path to file here

// An array of args to be *invoked* on Intervention
$intervention_args = array( 
	'fit' => array(
		'100',
		'600'
	),
	'blur' => 20,
	'rotate' => -45
);

// Call the global helper function
$img_src = wp_intervention( $original_image, $intervention_args ); 
```

Methods are invoked on the underlying Intervention class in the order defined in the arguements array.

Refer to the [documentation](http://image.intervention.io/) to learn more about the capabilities of Intervention (hint: it's pretty fully featured!).

### Options

There are also various options which can be modified by passing a 3rd argument to the global function on a per use case basis.

```php
wp_intervention( $original_image, $intervention_args, array(
	'quality' => 100,
	'cache'   => false // be careful about modifying this (see below)
) ); 
```

#### Args

##### quality

Type: Integer 

Default: 80 

Description: A image quality setting ranging from 0-100. 

##### cache

Type: Boolean 

Default: true 

Description: Whether or not to cache this particular image. If false the image will be re-generated by Intervention on each request which may place undue load on your server. Use wisely...

#### Overriding the Default Options

The default settings for the options (see above) can be overidden globally by filtering on the hook `wpi_default_options`. For example

```php
function modify_default_options( $options ) {	
	$options['cache'] = false;
    return $options;
}
add_filter( 'wpi_default_options', 'modify_default_options' );
```

### Image Driver Configuration

Intervention allows you to configure either GD or Imagick as the primary image driver. By default this is GD but you can overide this by filtering on the `wpi_driver` hook.

```php
function modify_wpi_driver( $driver ) {	
	$driver = 'imagick'; 
    return $driver;
}
add_filter( 'wpi_driver', 'modify_wpi_driver' );
```

Note if you pass an unsupported driver then this is your problem...

## Caching

WP Intervention will cache generated images on disk to avoid repeatdly processing images. By default the cache resides at

`/wp-content/uploads/intervention/cache/`

Note: the exact path may vary depending on the settings of your individual WordPress installation.

### Altering the default cache location

If for any reason you need to change the default location of the cache you can filter on the `wpi_cache_directory` hook.

```php
function modify_wpi_cache_dir( $dir ) {	
	$dir = '/some/random/location/you/need/to/set/'; 
    return $dir;
}
add_filter( 'wpi_cache_directory', 'modify_wpi_cache_dir' );
```

You are responsible for ensuring this path is writable by PHP and publically accessible.

### Cache Garbage Collection

WP Intervention automatically removes cached images that have not been accessed for 24hrs. 

To do this each time a cached image is accessed by WP Intevention it's timestamp is updated via PHP's `touch()` method. A WP Cron job then runs every hour checking for any "stale" images that are older than 24hrs. Any matching images are deleted.

If for any reason you need to alter the duration cached files are allowed to reside on disk before they become stale, then you can filter on the `wpi_clean_outdate_cache_files_period` hook.

```php
function modify_wpi_cache_files_period( $period ) {	
	$period = 604800; // 1 week
    return $period;
}
add_filter( 'wpi_clean_outdate_cache_files_period', 'modify_wpi_cache_files_period' );
```

Similarly if you need to alter how often the cron job reoccurs then you can filter on `wpi_clean_outdate_cache_files_cron_recurrance`.

```php
function modify_wpi_clean_outdate_cache_files_cron_recurrance( $recurrance ) {	
	$recurrance = 'twicedaily'; 
    return $recurrance;
}
add_filter( 'wpi_clean_outdate_cache_files_cron_recurrance', 'modify_wpi_clean_outdate_cache_files_cron_recurrance' );
```

## Contributing

Contributions to the WP Intervention Plugin are welcome. Please make a pull request!

## License

WP Intervention Plugin is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Intervention Image is licensed under the [MIT License](http://opensource.org/licenses/MIT).
Copyright 2014 [Oliver Vogel](http://olivervogel.net/)