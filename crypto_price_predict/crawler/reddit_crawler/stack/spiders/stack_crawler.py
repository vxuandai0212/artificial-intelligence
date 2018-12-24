# -*- coding: utf-8 -*-
import scrapy
import sys
sys.path.insert(0, 'C:/Users/Metaphor/Desktop/vini-intern/crypto_price_predict/crawler/reddit_crawler')
from scrapy.contrib.linkextractors import LinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule

from stack.items import StackItem


class StackCrawlerSpider(CrawlSpider):
    name = 'stack_crawler'
    allowed_domains = ['stackoverflow.com']
    start_urls = [
        'http://stackoverflow.com/questions?pagesize=50&sort=newest'
    ]

    rules = [
        Rule(LinkExtractor(allow=r'questions\?page=[0-9]&sort=newest'),
             callback='parse_item', follow=True)
    ]

    def parse_item(self, response):
        questions = response.xpath('//div[@class="summary"]/h3')

        for question in questions:
            item = StackItem()
            item['url'] = question.xpath(
                'a[@class="question-hyperlink"]/@href').extract()[0]
            item['title'] = question.xpath(
                'a[@class="question-hyperlink"]/text()').extract()[0]
            yield item
