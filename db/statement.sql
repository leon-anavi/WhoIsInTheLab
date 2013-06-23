SELECT 
IFNULL(user_id,UUID()) as user, 
user_name1, user_name2, user_twitter, 
user_facebook, device_uid, online_MAC 
FROM who_online 
LEFT JOIN who_devices ON online_MAC = device_MAC
LEFT JOIN who_users ON device_uid = user_id
WHERE online_MAC NOT IN (SELECT blacklist_MAC FROM who_blacklist) 
GROUP BY user
ORDER BY user_name1 ASC


