# -*- coding: utf-8 -*-
import scrapy
import re
import sys
sys.path.append('/home/lam/Desktop/vini-intern/product_recommend/crawler/famousfootwear')
from famousfootwear.items import ShoesItem


class BasicSpider(scrapy.Spider):
    name = 'famousfootwear'
    allowed_domains = ['www.famousfootwear.com']

    def start_requests(self):
        url = 'https://www.famousfootwear.com/en-US/Womens/_/_/Athletic+Shoes/NumA+288/Products.aspx' # 4
            # 'https://www.famousfootwear.com/womens-athletic-shoes'  # 1
            # 'https://www.famousfootwear.com/en-US/Womens/_/_/Athletic+Shoes/NumA+96/Products.aspx' # 2
            # 'https://www.famousfootwear.com/en-US/Womens/_/_/Athletic+Shoes/NumA+192/Products.aspx' # 3

        yield scrapy.Request(url=url, callback=self.parse)

    def parse(self, response):
        urls = response.xpath('//div[@class="productImage"]/a/@href')
        for url in urls:
            yield response.follow(url, callback=self.parse_product_detail)

        # next_page = response.xpath(
        #     '//div[@id="ctl00_cphPageMain_ResultsZoneSearchDetail_divPagerBottom"]//a[@class="pageBtn"]/@href').extract()[-1]
        # if next_page is not None:
        #     print(next_page)
        #     yield response.follow(next_page, callback=self.parse)
        # else:
        #     print('No next page')

    def parse_product_detail(self, response):
        item = ShoesItem()
        item['product_name'] = response.xpath('//h1[@class="PD_BrandStyle"]/span[@class="PD_Style"]/text()').extract()[0].strip()
        item['product_image'] = response.urljoin(
            response.xpath('//img[@id="ctl00_cphPageMain_ImageMultiView1_imgLargeDisplay"]/@src').extract()[0])
        product_price = response.xpath('//span[@id="ctl00_cphPageMain_BrandAndPrice1_ProductPrice"]/text()').extract()[1]
        item['product_price'] = product_price.replace('\r\n            $', '').replace('\r\n            \xa0\r\n        ', '')
        product_code = response.xpath('//span[@class="SKUtxt"]/text()').extract()[0].strip()
        item['product_code'] = product_code.replace('Style # ', '')

        yield item
