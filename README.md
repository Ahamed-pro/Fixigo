FIXIGO – Web Application bro
Overview

Fixigo is a service-oriented web application connecting users with workshops. This project contains both frontend and backend functionality, including user registration, login, contact forms, and password reset.

Email Configuration Instructions

Some functionality in Fixigo requires sending emails (e.g., registration confirmation, contact form messages, and password reset). If you want to work with this site, you must configure your own email credentials.

Steps to Configure Email:

Create or use a Gmail account.

Turn on 2-Step Verification.

Generate an App Password using the name “Fixigo”.

Use this app password as the password in the PHP files.

Files Requiring Email Configuration
File Path	Required Info
backend/register.php	Sender email and App Password (for sending emails)
backend/register.php	Receiver email, Sender email, and App Password
footer/submit_contact.php	Sender email and App Password
password/forgot_password.php	Sender email and App Password

⚠ Note: Do not use your normal Gmail password. Only use the App Password generated after enabling 2-Step Verification.

How to Enter the Credentials

In the PHP files mentioned above, replace the placeholders with your own information:

$sender_email = "youremail@gmail.com"; // your Gmail address
$sender_password = "your_app_password"; // App Password generated for Fixigo
$receiver_email = "receiveremail@example.com"; // email where messages should be sent (if applicable)
Important Notes

Everyone using this project must enter their own sender email, app password, and receiver email in the relevant files.

This ensures the email functionality works correctly for registration, contact forms, and password reset.

Make sure the Gmail account has 2-Step Verification turned on before generating the App Password.
