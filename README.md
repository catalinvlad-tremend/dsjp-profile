# Digital Skills and Jobs profile

Basic installation profile, all it does is:

1. Enable the bare minimum amount of core modules.
2. Setup `seven` as administrative theme and edsjp as front-end theme.

## Rationale

Opting for a minimalistic installation profile will make it easier to deal with a fully distributed approach: the
installation profile is seen as an empty shell that will never pose any compatibility issue to any of the site's modules
and themes. Most importantly this will allow modules and themes maintainers to properly version their work semantically.

This profile will also be used to build a basic site using the multiple components of OpenEuropa.

## Installation

The recommended way of installing the OpenEuropa Profile is via a [Composer-based workflow][2].

In the root of the project, run

```
$ composer install
```

Before setting up and installing the site make sure to customize default configuration values by copying `./runner.yml.dist`
to `./runner.yml` and override relevant properties.

To set up the project run:

```
$ ./vendor/bin/run drupal:site-setup
```

This will:

- Symlink the profile in `./build/profiles/custom/oe_profile` so that it's available to the target site
- Setup Drush and Drupal's settings using values from `./runner.yml.dist`
- Setup Behat configuration file using values from `./runner.yml.dist`

**Please note:** project files and directories are symlinked within the target site by using the
[OpenEuropa Task Runner's Drupal project symlink](https://github.com/openeuropa/task-runner-drupal-project-symlink)
command.

If you add a new file or directory in the root of the project, you need to re-run `drupal:site-setup` in order to make
sure they are correctly symlinked.

If you don't want to re-run a full site setup for that, you can simply run:

```
$ ./vendor/bin/run drupal:symlink-project
```

After a successful setup install the site by running:

```
$ ./vendor/bin/run drupal:site-install
```

This will:

- Install the target site
- Set the OpenEuropa Theme as the default theme
- Enable development modules

### Using Docker Compose

The setup procedure described above can be sensitively simplified by using Docker Compose.

Requirements:

- [Docker][3]
- [Docker-compose][4]

Copy docker-compose.yml.dist into docker-compose.yml.

You can make any alterations you need for your local Docker setup. However, the defaults should be enough to set the project up.

Run:

```
$ docker-compose up -d
```

Then:

```
$ docker-compose exec web composer install
$ docker-compose exec web ./vendor/bin/run drupal:site-install
```

Your test site will be available at [http://localhost:8080/build](http://localhost:8080/build).

Run tests as follows:

```
$ docker-compose exec web ./vendor/bin/behat
```

#### Step debugging

To enable step debugging from the command line, pass the `XDEBUG_SESSION` environment variable with any value to
the container:

```bash
docker-compose exec -e XDEBUG_SESSION=1 web <your command>
```

Please note that, starting from XDebug 3, a connection error message will be outputted in the console if the variable is
set but your client is not listening for debugging connections. The error message will cause false negatives for PHPUnit
tests.

To initiate step debugging from the browser, set the correct cookie using a browser extension or a bookmarklet
like the ones generated at https://www.jetbrains.com/phpstorm/marklets/.

[1]: https://github.com/openeuropa/oe_theme/releases
[2]: https://www.drupal.org/docs/develop/using-composer/using-composer-to-manage-drupal-site-dependencies#managing-contributed
[3]: https://www.docker.com/get-docker
[4]: https://docs.docker.com/compose
