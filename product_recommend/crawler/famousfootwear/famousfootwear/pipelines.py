# -*- coding: utf-8 -*-
import sys
sys.path.append('/home/lam/Desktop/vini-intern/product_recommend/crawler/famousfootwear')
from famousfootwear.items import ShoesItem


class CsvItemPipeline:
    fieldnames_standard = ['product_name', 'product_image', 'product_code', 'product_price']

    def __init__(self, csv_filename):
        self.items = []
        self.csv_filename = csv_filename

    @classmethod
    def from_crawler(cls, crawler):
        return cls(
            csv_filename=crawler.settings.get('CSV_FILENAME', 'famousfootwear.csv'),
        )

    def open_spider(self, spider):
        pass

    def close_spider(self, spider):
        import csv
        with open(self.csv_filename, 'w', encoding='utf-8') as outfile:
            spamwriter = csv.DictWriter(outfile, fieldnames=self.get_fieldnames(), lineterminator='\n')
            spamwriter.writeheader()
            for item in self.items:
                spamwriter.writerow(item)

    def process_item(self, item, spider):
        if type(item) == ShoesItem:
            new_item = dict(item)
            self.items.append({**new_item})
        return item

    def get_fieldnames(self):
        field_names = set()
        for product in self.items:
            for key in product.keys():
                if key not in self.fieldnames_standard:
                    field_names.add(key)
        return self.fieldnames_standard + list(field_names)
