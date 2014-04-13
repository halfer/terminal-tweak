Terminal Tweak
==============

Terminal Tweak is a simple script to set terminal titles based on their current working directory. Use it from within a profile login script, and combine it with a terminal utility that supports the saving of multiple tabs/cwds.

For example, in Ubuntu, you can create your preferred tab layout in `gnome-terminal`, set a working directory for each, save the tabs using `--save-config`, and then use this script inside the backtick operator in `~/.bashrc` to reset the title for each tab.

This therefore provides a title-setting ability to console applications that do not provide it themselves. It should work on any Unix-like system that has PHP installed.

License
-------

This code is licensed under GPL2 or later.

Requirements
------------

This code should work on PHP 5.3 or later.

Installation
------------

First, install the Terminal Tweak scripts in your home folder; a dotted directory is used to hide it in normal usage:

    cd ~
    git clone https://github.com/halfer/terminal-tweak.git .terminal-tweak
    cd .terminal-tweak

Then create a configuration file from the sample:

    cp titles.ini.sample titles.ini
    nano title.ini

You can then set up your titles. So, for a match on an exact directory name:

    home.dir = /home/jon
    home.title = Home

You can also do a regular expression match:

    svn_branch.dir = "#^/home/jon/Development/work/foo/branches#"
    svn_branch.title = Foo branch
    svn_branch.regexp = Yes

That will associate this title with any cwd beginning with the specified path.

To install it, we use the backtick operator in the shell script that sets up the default value for titles. In Ubuntu, this is done in `~/.bashrc`. Change the PS1 line, which may be something like this:

    PS1="\[\e]0;${debian_chroot:+($debian_chroot)}\u@\h: \w\a\]$PS1"

and include this backtick statement:

    `php ~/.terminal-tweak/set-title.php`

For example, you could remove the user, host and working directory default title, and use just this instead:

    PS1="\[\e]0;${debian_chroot:+($debian_chroot)} `php ~/.terminal-tweak/set-title.php` \a\]$PS1"

Fault-finding
-------------

If things are not working as you expect, you can syntax-check your configuration thus:

    php set-title.php --validate

Future development
------------------

If this is useful for other people, then great. You're welcome to hack it as much as you like, since it has a F/OSS license, but if the changes might be useful to others, feel free to share!

Things I might add in the future:

* Per-tab prompt settings
* Smart directory changing (e.g. to latest subfolder within a folder)
* Placeholders for user, host and cwd to use in the `.title` settings, so these can be set for some tabs but not others
