Christopher Kielty (cjk5807)

Each PHP file contains basic error handling and input checks.

File: index.php
Description: The index file is the home page, all user interactions takes place here meaning the bolk of the html and css is used here. The user has access to account sign up and sign in, adding and deleting discounts, 
and making referrals. This one page takes in all user input through several forms and buttons. It then handles sending that information to the backend, the location decided by the what action the user wants to take.
All the reports are also displayed here specific to what state the site is in (whether the user logged in, or trying to add discount, etc), this means the bulk of the queries are done in the index (including the 5+ join).

File: account.php and deleteAccount.php
Description: These two files store the functionality for all db transaction involving user accounts. The account.php handles the retrieval and creation of an account. An account is a way for the user
to have a more interactive experience with the UI, when the user logs in they will be given coupons specific to their location as well as having a personal discount tracker and savings score. 
The deleteAccount.php as the name implies will handle the deletion of an account.

File: coupon.php and deleteCoupon.php
Description: These two files store the functionality for all db transactions involving coupons. coupon.php handles the insertion of a new coupon/discount. Since coupons interact with much of the db a 
lot needs to be updated and inserted when a coupon is added. First we need to take into account what item will have this coupon, all of which will be inputted by the user. Next we then create the coupon linked to the item, which
will then need to be linked to the store. To make the user interaction easier I made it so the stores for that specific area is in drop down for the user to select from when inputting a discount. 
Lastly need to apply the coupon to the user and update the savings. The deleteCoupon.php just handles the deletion of the coupon if lets say it turns out it is expired.

File: discount.php
Description: This file handles the application of a discount to the user. When the user is signed in and the list of coupons available displayed the user has the option to apply it. When applied the 
necessary information will be sent from the index page to the discount page where the user's used coupons will be updated as well as the savings score.

File:referral.php
Description: This file handles the referral program that is set up within the site. Here the user can choose to submit an email and if approved will receive 5 extra dollars in savings. 
A prominent commit and rollback transaction also occurs here where if the system detects that the email inputted is already in use or within the referral database it will rollback the email insertion and savings update then prompting
the user it is already in use.

File: css/style.css
Description: Contains all the styling for the website, makes it more organized and enables reusability.

CSV FILES

File: cities.csv
Description: This file contains 28339 lines of data. It is orginized by city, state_id, and state_name. It is used to link both stores and user locations together. 
IMPORTANT: This data was taken and modified from https://simplemaps.com/data/us-cities

File: stores.csv
Description: This file contains 3051 lines of data. It is orgnized by store, city, state. It is used to link the store with locations by converting the city and state into location ids. 
I only found data on walmarts, so to keep it less boring I added several extra stores for state college and some other cities for demo purposes.
IMPORTANT: This data was taken and modified from https://raw.githubusercontent.com/kosukeimai/qss/master/DISCOVERY/walmart.csv

File: item.csv
Description: This file contains 33 lines of information on store products. It has the price of the item, the type, and name. This was created by me but most of the prices were taken from https://www.walmart.com/

File: coupon.csv
Description: This is the coupon data file, with about 15 lines of coupons. Each coupon has a discount and a respective item it is applied to. Some items have multiple coupons, 
which is intentional as enforce the code to recommend only the best coupons for each item. The coupons once created were then randomly distributed to each store and stored in store2coupon table with this command:
INSERT INTO cjk5807_store2coupon (store_id, coupon_id)
SELECT s.store_id, ABS(s.store_id + c.coupon_id)%33 FROM cjk5807_stores s, cjk5807_coupon c WHERE ABS(s.store_id + c.coupon_id)%33 < 16 and ABS(s.store_id + c.coupon_id)%33 > 0;

