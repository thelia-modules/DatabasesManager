#1.4.0
- Add possibility to choose database charset  
  Thanks to @marsender

#1.3.1
- Fix compatibility with BaseModuleInterface

# 1.3.0
- Change license

# 1.2.2
- Move databases configuration files in `THELIA_LOCAL_DIR . 'DatabasesManager'`. That allows to keep configurations over `composer` updates.

# 1.2.1
- BUGFIX: Define new route id for environment routes

# 1.2.0
- Require Thelia 2.1.0.
- Use hooks instead of admin includes for module configuration
- Add environment dependent configuration

# 1.1.2
- Add password obfuscation

# 1.1.1
- Initialize connections to databases on `TheliaEvents::BOOT` event.
- Downgrade Thelia required version to 2.0.0
- BUGFIX: Use a new connection manager for each connection

# 1.1.0
- Require Thelia 2.2.0.
- Initialize connections to databases at runtime for console.

# 1.0.0
- Initial release.
