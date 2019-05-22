# import requests
# headers = {
#     'user-agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36' \
#              ' (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36'
# }
# # url = 'https://www.famousfootwear.com/ProductImages/shoes_id77889.jpg?trim.threshold=105&paddingWidth=60&height=550&anchor=bottomcenter&width=1200'
# # page = requests.get(url, headers=headers)
# # open('shoes.jpg', 'wb').write(page.content)
#
# import pandas as pd
#
# products_path = '/home/lam/Desktop/vini-intern/product_recommend/web/vnfootwear/database/seeds/data/products_sample_data.csv'
# products = pd.read_csv(products_path)
# for index, row in products.iterrows():
#     print(row['product_image'], row['c2'])

import asyncio
import websockets

async def hello():
    async with websockets.connect(
            'ws://192.168.52.209:8765') as websocket:
        name = input("What's your name? ")

        await websocket.send(name)

        greeting = await websocket.recv()
        # print(f"< {greeting}")

asyncio.get_event_loop().run_until_complete(hello())