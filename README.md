# WP Multisite 8000 port

Plugin supports multisite on a different port than :80 and :443 (e.g. :8000).

Here we assume that the 'siteurl' and 'home' options contain the :8000 port.

WARNING: Not suited for production sites!

Get around the problem with wpmu_create_blog() where sanitize_user() strips out the semicolon (:) in the $domain string. This means created sites with hostnames of e.g. example.tld8000 instead of example.tld:8000.
