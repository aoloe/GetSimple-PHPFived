# Further ideas for the future

## Allowing the plugins to be individually revision controlled

Currently, the main plugin's file is a PHP file in the `plugins/` directory. The problem is that a developer will typically have several plugins from different repositories among his plugins.

Personally, I've sovled the issue, by putting the repository a different place, where I keep all my repositories, and symlink the the plugin's PHP file and directory into the `plugins/` directory.

GS itself is put in this way into the local webserver's directory.

The problem that arises, is that GS uses `__FILE__` for defining `GSROOTPATH` (and then to load all its files) and `__FILE__` (by -- in my eyes bad -- design) deferences the symlink and returns the path of the repository and not of the symlink in the directory served by Apache.

In order to solve this, I've added a `unreal__FILE__()` method in my `admin/inc/common.php` file and (as a reference) also in `PHP_future.php`.

What could be changed:

- One way to solve this is to get GS to check for `plugins/PLUGIN_NAME.php` and `plugins/PLUGIN_NAME/PLUGIN_NAME.php`.
- The other way would be to use `unreal__FILE__()` instead of `__FILE__` (or get the PHP crew to implement a version of `__FILE__` that can be used in this setup.

