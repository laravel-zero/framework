# Release process

Upon releasing a new version there's some checks and updates to be made:

> Note both `laravel-zero/framework` and `laravel-zero/laravel-zero` are tagged at the same time and contain the same version.

### On the laravel-zero/framework repository:

- Clear your local repository with: `git add . && git reset --hard && git checkout master` 
- On the github website, check the contents on [github.com/laravel-zero/framework/compare/{latest_version}...master](https://github.com/laravel-zero/framework/compare/{latest_version}...master) and update the [changelog](CHANGELOG.md) file with the modifications on this release

> Note: make sure that there is no breaking changes and you may use `git tag --list` to check the latest release

- Commit the `CHANGELOG.md` with the message: `git commit -m "release: bumps version to vX.X.X"`
- Runs the tests locally using: `composer test`
- `git push`
- Check the CI, and see if tests are passing as expected: https://github.com/laravel-zero/framework/actions
- `git tag vX.X.X` - example `git tag v6.0.1`
- `git push --tags`


### On the laravel-zero/laravel-zero repository:

- Clear your local repository with: `git add . && git reset --hard && git checkout master` 
- Update the [changelog](CHANGELOG.md) with the same contents of [github.com/laravel-zero/framework/blob/master/CHANGELOG.md](https://github.com/laravel-zero/framework/blob/master/CHANGELOG.md)
- Commit the `CHANGELOG.md` with the message: `git commit -m "release: bumps version to vX.X.X"`
- `git push`
- Check the CI, and see if tests are passing as expected: https://github.com/laravel-zero/laravel-zero/actions
- `git tag vX.X.X` - example `git tag v6.0.1`
- `git push --tags`

### Make a tweet about the release attributing credits to the external collaborators
