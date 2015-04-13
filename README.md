# Donation-Center
A Paypal based donation system that allows for multiple users, events, and incentives. Initially used for http://tsg.tv.

[![photo-1425036458755-dc303a6](https://cloud.githubusercontent.com/assets/7907265/7041522/eef049c0-dda7-11e4-9eff-16ee9e164a58.jpg)](https://github.com/therobbiedavis/Donation-Center/releases/tag/v1.0.0)
Barebones release. Still needs a lot of work, but it's very much functional.

Requires: PHP, MySQL.

Known issues: 
- Uses MD5 with no salt for password storage. This has been fixed in the beta branch. **Use with caution**
- Paypal can be slow sending the IPN info back, causing the thank you page to not be populated with the user info.
- Need better system to handle multiple logins with the same user.
- General ugliness.
