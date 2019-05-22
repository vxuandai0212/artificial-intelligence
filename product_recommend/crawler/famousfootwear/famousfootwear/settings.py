# -*- coding: utf-8 -*-

BOT_NAME = 'famousfootwear'

SPIDER_MODULES = ['famousfootwear.spiders']
NEWSPIDER_MODULE = 'famousfootwear.spiders'

USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36' \
             ' (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36'

ROBOTSTXT_OBEY = True

CONCURRENT_REQUESTS = 1

# adds UTF-8 support for feed exports
FEED_EXPORT_ENCODING = 'utf-8'

ITEM_PIPELINES = {
    'famousfootwear.pipelines.CsvItemPipeline': 800,
}

# Optional: if you find statistic messages annoying, enable the code below to disable those nasty messages
# EXTENSIONS = {
#     'scrapy.extensions.logstats.LogStats': None
# }
