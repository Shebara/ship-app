# Ship Management App

This is a test assignment.

## Installation

- Set up Apache + PHP 7.2 + MySQL (for testing locally, XAMPP or WAMP can be used).

- Set up SMTP on your server.

- Extract ship-app folder with all its contents into your Apache server's root directory.
The site will also work if you set up the ship-app folder as the document root.

- Replace the values in config.php to adjust the parameters to your own server settings.

- Run {Server root}/setup ({Server root}/ship-app/setup) in your browser to set up the database.

## Usage

Run {Server root}/ ({Server root}/ship-app) in your browser to visit the site.

## Assignment requirements

You need to build a notification system, which will be used for sending notifications to crew members on ships.

There is a user which has ability to perform most of the actions in the system. We will call him **administrator**.
Administrator can maintain a list of ships. **Ship** has a **name**, unique **serial number** (consists of 8 characters) and **image**.
**Crew member** is a user working on a dedicated ship. 
He can login into the system. Every crew member must have **one** rank.
**Rank** has **name**. Only administrator has ability to maintain ranks in the system. One rank can be dedicated to multiple crew members.

Administrator can add crew member to a ship. He needs to give crew member’s **name**, **surname** and **email address**. Beside this, he also needs to dedicate him one rank. After adding, email is sent to a given email address, with registration link.

When crew member receives the email, he can click on the link. When he opens link from the email, he should see form where he needs to provide **password** which he will use for logging in.
After he successfully provided his password, he will be redirected to the page where his notifications are listed (described bellow).
He can read his notifications. Crew member can mark notification as **seen**. System should log date and time of this operation.

Administrator can send notifications to crew members. When administrator wants to create new notification, he needs to provide the following: **notification content** and **target ranks**.
**Notification content** is content of that notification. It should be WYSIWYG.
**Target ranks** presents to which crew members that notification should be sent. It means that notification should be sent only to crew members who have one of defined **target ranks**.

