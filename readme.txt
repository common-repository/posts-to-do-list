=== Posts To-Do List ===
Contributors: Ste_95
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4Y4STCU56MUKE
Tags: multi author, post management, to-do list, posts
Tested up to: 6.6
Stable tag: 1.4.5
Requires at least: 3.0

Share post ideas with writers, suggest them writing topics and keep track of the posts ideas with a to-do list.

== Description ==
Most people who run a multi-author blog need to tell their writers what post they should write. And so administrators send emails with a URL of the post source, the keyword for the post, any notes accompanying that. And sometimes a user notices a post that is worth writing and he wants to tell the other writers, so they have to email the administrator who will tell the other users and everyone will kill themselves in the end. The Posts To-Do List plugin allows you to get rid of that rigmarole.

By a convenient box in the posts editing page, **everyone will be able to share the posts they think are worth writing. You have this little box, where you put the URL of the page where you read that great post, and the plugin will fetch the title by itself. You will then be able to change the retrieved title, suggest a keyword and add other notes, set a priority and assign the post to some user of the blog.** And if you want to leave everything blank but the title field, leaving a suggestion that anyone can catch and deepen... well, you can!

No more emails to tell "You do this and that, use this keyword and don't forget that...". Everything can be down inside Wordpress. Not only by the administrator, but by any logged in user.

You, as the administrator, want everything in the power of your hand? No problem, you can **decide what user roles can add new posts to the to-do list and what user roles can delete already added items.** You want your users to stick to the post you assigned to them? You can hide the posts you have assigned to other users from their view. From a **simple stats page** it will be immediately clear how many posts you have already assigned and how many of them are still to do, so that it will be easy to understand how much your writers have done and how many posts you have still to assign. Almost every action is powered by AJAX, so that no page reloads are needed and you do not even notice it is happening, it just works.

== Installation ==
1. Upload the directory of the Posts To-Do List in your wp-content/plugins directory; note that you need the whole folder, not only the single files.
2. Activate the plugin through the "Activate" button in the "Plugins" page of your WordPress.
3. Head to the configuration page first. The plugin already comes with a predefined set of settings, but you may want to configure it to better suit your needs.
5. That's it, done!

== Changelog ==
= 1.4.5 =
* Improv: parse shortcodes in *Notes* field.

= 1.4.4 =
* Fix: issue with widget public display option having reverse behavior.

= 1.4.3 =
* Fix: issue with public widget not showing to non logged in users.

= 1.4.2 =
* Fix: mark as done flag feature not working.
* Fix: assign/unassign features not working with page reload.

= 1.4.1 =
* Fix: *Add new post* button not working.
* Fix: style of *Add* button.

= 1.4 =
* Feature: widget with to-do list. Ability to show it to non logged-in users as well.
* Feature: localization-ready.

= 1.3 =
* Tweak: items assigned to other users show up as red instead of blue.

= 1.2 =
* Fixed: notes field not storing line breaks.

= 1.1 =
* Fixed: not working on some server setups.

= 1.0 =
* Fixed: quotes not correctly escaped in notification emails.
* Fixed: very badly processing of form data.

= 0.99 =
* Fixed: email notification showing date as timestamp and assigner as user ID.
* Fixed: bunch of static methods not declared as static, resulting in PHP notices.
* Tweak: switched to wp_mail() instead of mail().

= 0.98 =
* Fixed: post edit page not loading due to plugin fault.

= 0.97 =
* Feature: the assignments box is now available as a dashboard widget as well.

= 0.9.6 =
* New option to prevent non-admins from claiming posts already assigned to other users (works in real time: if a post is claimed and another user tries to claim one second later because it still displays as unassigned, an error occurs).
* New option to prevent non-admins from claiming a post if they have not completed their latest assignment.

= 0.9.5 =
* When assigning items, users can be filtered by user role.

= 0.94 =
* Dashboard page now visible to all users with the capability edit_posts.

= 0.9.3 =
* Fixed a bug which resulted in a fatal error due to PHP 5.4 incompatibility. Sorry!

= 0.9.2 =
* Updated the plugin URI, which pointed to a non-existent page.

= 0.9.1 =
* Bugfix: AJAX actions may have triggered PHP warning because of Wordpress change of policy with wpdb->prepare() (actions were still executed, though).
* Bugfix: new installations missed an option, which resulted in a PHP warning in the Options page for the roles able to unassing items. If you experience this, just check a role for that permission, save, then uncheck it and save again.

= 0.9 =
* A new button in the options page allows to delete all the already marked as done items.

= 0.8.5 =
* If enabled in the plugin options page, when a newly added post is assigned to a certain user, an email is automatically sent to them with the details of the new item.

= 0.8 =
* Users can now assign to themselves posts added to the list as unassigned and assigned to other users.
* Users can now unassign from them posts assigned to themselves.
* A new permission allow to define what user role can unassign posts from themselves.
* Actions that need a reload of the list because of list sorting not get that reloading (deleting, assigning and unassigning).

= 0.7 =
* First release.

== Screenshots ==
1. Posts To-Do List dashboard page. The provided datapicker allows to edit the time range and select the wished stats
2. Posts To-Do List configuration page
3. New post page/edit post page, with the Posts To-Do List box. The "Add new post" part is collapsible.
