# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [3.8.0] - 2017-10-20
### Added
- Moves Collision from composer `require-dev` to `require`.
- Adds option `with-migrations` to database config.
- Core: Migrations feature.

## [3.7.0] - 2017-10-12
### Added
- Adds Collision to composer `require-dev`.
- Adds Collision listener to `phpunit.xml`.
- Core: Auto registers collision.

## [3.6.11] - 2017-10-01
### Added
- Core: Adds `config_path` helper.

## [3.6.6] - 2017-09-29
### Added
- Core: Removes output global helpers.

## [3.6.5] - 2017-09-24
### Added
- Core: Adds output global helpers.

## [3.6] - 2017-09-21
### Added
- Core: Adds Scheduler.
- Core: Adds Facades.
- Core: Adds `illuminate/filesystem` component

### Changed
- Renamed base command `AbstractCommand` to `Command`.
- Cache config added to `config.php`.

### Removed
- Core: Removes `illuminate/cache` component. It's now by default.

## [3.5.0] - 2017-09-12
### Added
- Core: Adds `illuminate/filesystem` component.
- Core: Adds `illuminate/cache` component.
- Renames default command to `Hello command`.

## [3.4.0] - 2017-09-04
### Added
- Core: Bumps Laravel components version to 5.5.

## [3.3.0] - 2017-08-27
### Added
- Core: Core commands are not available in production by production. [#29](https://github.com/nunomaduro/laravel-zero/pull/29)
- Core: Adds component:install command.
- Core: Adds illuminate/database component.

## [3.2.0] - 2017-08-22
### Added
- Core: Adds the executable bit on the compiled standalone phar on the builder command.
- Core: Add shebang on the builder command.

## [3.1.0] - 2017-07-21
### Changed
- Core: Fixes bootstrap of service providers.

## [3.0.5] - 2017-07-19
### Changed
- Fixes bind of app container.

## [3.0.0] - 2017-07-16
### Added
- Splits core framework from the project.

### Changed
- Removes performance analyser.
