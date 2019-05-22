# -*- coding: utf-8 -*-

from scrapy import Field, Item


class ShoesItem(Item):
    product_name = Field()
    product_image = Field()
    product_code = Field()
    product_price = Field()
