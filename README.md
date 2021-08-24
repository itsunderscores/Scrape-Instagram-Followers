## Scrape Instagram Followers from Username

Scrapes Instagram Followers from Username. Works flawlessly.

* Grabs Follower Count
* Grabs UserID
* Puts followers into list
* Select how many accounts to grab per request
* Saves follower list to file

Automatically obtains follower amount to parse the follower list appropriately. 

Upload your Instagram cookies to file ```cookies.txt``` (Sign in, go to a users profile, Inspect Element, and copy your cookies) (Look at cookies.txt for example)

Usage: ```scrape.php?username=statemnd&delay=3&grab=50```

Delay = How long to wait per scraping next list (I usually have it set to 3 seconds to prevent limitation)

Grab = How many accounts to grab per request (Instagram usually has it set to 12, I wouldn't recommend going over 1,000 due to getting blocked)
