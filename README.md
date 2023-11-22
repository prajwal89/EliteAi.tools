meilisearch --http-addr 0.0.0.0:1234


sample queries
i want to create images of my product for social media

## cron jobs

```bash
# aitolsrepo

# every minute
* * * * * curl "http://clgnotes.esy.es/cron/per-minute/run-all-jobs"

#other
0 14,20,24 * * * curl "http://clgnotes.esy.es/cron/send-telegram-tool-promotional-message"
```