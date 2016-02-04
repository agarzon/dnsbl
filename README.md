DNSBL
=========
Check IP or domain for DNS Blacklist and SenderScore

DEMO
----
http://dnsbl.venehosting.com/

SCREENSHOT
----
![Imgur](http://i.imgur.com/YHPWdfl.png)

INSTALL
----
* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

TODO
----
- [x] Move to micro-framework
- [x] API
- [x] "in process" bar while buffering
- [x] AJAX instead PHP buffer
- [x] return reverse PTR when IP is entered
- [ ] return whois if is domain
- [ ] return nameserver if is domain
- [ ] return geo-localization based in IP

License
----
MIT

(c) Alexander Garzon
