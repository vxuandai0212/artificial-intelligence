import cloudinary.uploader
import uuid

import os

# Set environment variables

api_key = '933392669637745'
api_secret = '1zoGKEuecwuoB1MTdo-9UYjzf80'
cloud_name = 'fit1501040028'

image = '/home/lam/Desktop/vini-intern/product_recommend/download/62.jpg'
image_id = str(uuid.uuid4())
cloudinary.uploader.upload(image, public_id = image_id, api_key=api_key, api_secret=api_secret, cloud_name=cloud_name)