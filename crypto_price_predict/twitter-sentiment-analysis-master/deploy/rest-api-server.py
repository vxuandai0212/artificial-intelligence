# USAGE
# Start the server:
# 	python rest-api-server.py
# Submit a request via Python:
#	python simple-request.py

# import the necessary packages
from keras.models import Sequential, load_model
from keras.preprocessing.sequence import pad_sequences
import tensorflow as tf
import flask
from flask_cors import CORS
import numpy as np
import utils
import json
from apscheduler.schedulers.background import BackgroundScheduler
import time
import datetime

# cài đặt môi trường mongodb
# dbname: tweet_data_ml
# collection for bitcoin tweet: tweet_btc
# collection for setiment analysis tweet: tweet_sa

from pymongo import MongoClient
client = MongoClient('localhost', 27017)
db = client.tweet_data_ml
tweet_btc_collection = db.tweet_btc
tweet_sa_collection = db.tweet_sa

# initialize our Flask application and the Keras model
app = flask.Flask(__name__)
CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'
model_path = '../code/models/4cnn-08-0.007-0.998-0.089-0.980.hdf5'
max_length = 40
vocab_size = 90000
FREQ_DIST_FILE = '../code/processed_train_data-freqdist.pkl'
vocab = utils.top_n_words(FREQ_DIST_FILE, vocab_size, shift=1)

def process_tweet(tweet):
    return get_feature_vector(tweet)

def get_feature_vector(tweet):
    """
    Generates a feature vector for each tweet where each word is
    represented by integer index based on rank in vocabulary.
    """
    words = tweet.split()
    feature_vector = []
    for i in range(len(words) - 1):
        word = words[i]
        if vocab.get(word) is not None:
            feature_vector.append(vocab.get(word))
    if len(words) >= 1:
        if vocab.get(words[-1]) is not None:
            feature_vector.append(vocab.get(words[-1]))
    return feature_vector

def job():
    # Lấy tất cả các tweet.
	tweets = get_tweets()
	# Dán nhãn, đưa ra dự đoán.
	global pos_count, neg_count, emo
	pos_count, neg_count, emo = predict_emo(tweets)

# sched = BackgroundScheduler(daemon=True)
# sched.add_job(job,'interval',hours=2)
# sched.start()

def get_tweets():
	# today = datetime.datetime.today().strftime('%Y-%m-%d')
	# today_ts = time.mktime(datetime.datetime.strptime(today,'%Y-%m-%d').timetuple())
	# tweets = tweet_btc_collection.find({"created_at": {"$gt": today_ts}})
	tweets = tweet_btc_collection.find({})
	return tweets

def predict_emo(tweets):
	pos_tweets = []
	neg_tweets = []
	for tweet in tweets:
		processed_tweet = process_tweet(tweet['full_text'])
		processed_tweet = pad_sequences([processed_tweet], maxlen=max_length, padding='post')
		with graph.as_default():
			predictions = model.predict(processed_tweet, batch_size=128, verbose=1)
			tweet['predict'] = np.round(predictions[:, 0]).astype(int)
		if tweet['predict'] == 1:
			pos_tweets.append(tweet)
		else:
			neg_tweets.append(tweet)

		sa_tweet = {}
		sa_tweet["_id"] = tweet['_id']
		sa_tweet["created_at"] = tweet['created_at']
		sa_tweet["label"] = int(tweet['predict'][0])
		print(sa_tweet)
		tweet_sa_collection.insert_one(sa_tweet)


	emo = 0 if len(pos_tweets) < len(neg_tweets) else 1
	# Xóa tất cả record từ những ngày trước.
	today = datetime.datetime.today().strftime('%Y-%m-%d')
	today_ts = time.mktime(datetime.datetime.strptime(today, '%Y-%m-%d').timetuple())
	return len(pos_tweets), len(neg_tweets), emo

@app.route("/image", methods=["GET"])
def image():
	image = "/home/lam/Desktop/xxx.png"
	import random
	import cv2
	h, w, c = image.shape
	x, y = random.randint(0, w - 30), random.randint(0, h - 30)
	image = cv2.rectangle(image, (0, 0), (x, y), (0, 255, 0), 3)
	data = {"image": image}
	return flask.jsonify(data)

@app.route("/predict", methods=["POST"])
def predict():
	# initialize the data dictionary that will be returned from the
	# view
	data = {"success": False}

	if flask.request.method == "POST":
		if flask.request.data:
			req_data = flask.request.data.decode('utf8')
			dataDict = json.loads(req_data)
			tweet = dataDict["tweet"]

			processed_tweet = process_tweet(tweet)
			processed_tweet = pad_sequences([processed_tweet], maxlen=max_length, padding='post')
			with graph.as_default():
				predictions = model.predict(processed_tweet, batch_size=128, verbose=1)
				predict_text = "Positive" if np.round(predictions[:, 0]).astype(int) == 1 else "Negative"
				probability = predictions[0][0]
				data["predictions"] = []
				r = {"label": predict_text, "probability": float(probability)}
				data["predictions"].append(r)

				# indicate that the request was a success
				data["success"] = True

	# return the data dictionary as a JSON response
	return flask.jsonify(data)

@app.route("/predict/day", methods=["GET"])
def get_day():
	data = {"success": False}
	if neg_count and pos_count and emo:
		data["neg_tweets"] = neg_count
		data["pos_tweets"] = pos_count
		data["prediction"] = emo
		data["success"] = True

	return flask.jsonify(data)

@app.route("/predict/week", methods=["GET"])
def get_week():
	data = {"success": False}
	# data from coindesk
	chartData = [[1547006399000, 4004.4586377436], [1547010000000, 4014.3202211139],
								  [1547013599000, 4002.6540179937], [1547017199000, 4002.8852309665],
								  [1547020799000, 4004.5840580325], [1547024400000, 4000.2922755874],
								  [1547028000000, 3999.7843801274], [1547031599000, 4003.0567518614],
								  [1547035199000, 4008.6829145892], [1547038799000, 3998.9681306957],
								  [1547042399000, 3994.3981657401], [1547046000000, 3974.6590637474],
								  [1547049600000, 3981.6060285094], [1547053199000, 3988.221064509],
								  [1547056799000, 3989.9338263453], [1547060400000, 3995.1728215276],
								  [1547063999000, 4005.2872523584], [1547067599000, 3998.6216018668],
								  [1547071200000, 3995.5585271785], [1547074800000, 3995.3251931641],
								  [1547078400000, 3990.5593234975], [1547081999000, 4020.0206739894],
								  [1547085599000, 4013.6303424397], [1547089199000, 4009.5086796746],
								  [1547092799000, 4008.3364351523], [1547096400000, 4018.792953033],
								  [1547100000000, 4018.4187987743], [1547103599000, 3812.2803069288],
								  [1547107199000, 3793.6063255292], [1547110800000, 3781.4969073584],
								  [1547114399000, 3773.7833165192], [1547118000000, 3767.3553721288],
								  [1547121599000, 3772.9157852672], [1547125199000, 3764.5474421402],
								  [1547128800000, 3759.2570090711], [1547132399000, 3755.0553564853],
								  [1547135999000, 3760.9222003603], [1547139599000, 3613.8505362955],
								  [1547143200000, 3611.6345199362], [1547146800000, 3596.6315996183],
								  [1547150400000, 3623.7315351203], [1547153999000, 3618.4201340196],
								  [1547157599000, 3618.4192217892], [1547161199000, 3617.3462911588],
								  [1547164800000, 3619.4082217026], [1547168400000, 3634.7186828525],
								  [1547171999000, 3620.6395595031], [1547175599000, 3609.9400217642],
								  [1547179200000, 3613.1881559996], [1547182799000, 3591.7727832131],
								  [1547186400000, 3603.015381987], [1547189999000, 3613.9686455199],
								  [1547193599000, 3623.582954274], [1547197200000, 3635.22078845],
								  [1547200799000, 3621.7687530278], [1547204399000, 3615.7755436933],
								  [1547207999000, 3619.3320904862], [1547211599000, 3613.6977858085],
								  [1547215199000, 3599.0320310447], [1547218799000, 3626.0219777923],
								  [1547222399000, 3615.3344957201], [1547225999000, 3629.8979345682],
								  [1547229599000, 3637.1437270298], [1547233200000, 3634.7541346423],
								  [1547236800000, 3636.8711064459], [1547240399000, 3641.4240409332],
								  [1547243999000, 3629.723766428], [1547247599000, 3606.1648903844],
								  [1547251199000, 3629.7373546214], [1547254799000, 3631.2349849944],
								  [1547258399000, 3617.1185561254], [1547261999000, 3622.2810536501],
								  [1547265600000, 3632.7274844766], [1547269199000, 3621.082137088],
								  [1547272799000, 3619.5847844043], [1547276399000, 3603.5641887836],
								  [1547280000000, 3611.3502836705], [1547283600000, 3607.2565211839],
								  [1547287199000, 3612.0573455259], [1547290799000, 3608.009203345],
								  [1547294400000, 3603.5291790106], [1547297999000, 3621.144181712],
								  [1547301599000, 3616.7075764585], [1547305199000, 3624.7337772417],
								  [1547308800000, 3622.701693522], [1547312400000, 3618.8151470949],
								  [1547315999000, 3623.7598034604], [1547319599000, 3620.4203264981],
								  [1547323199000, 3620.9253650547], [1547326799000, 3625.7275725883],
								  [1547330400000, 3620.6598148884], [1547334000000, 3611.5282378524],
								  [1547337599000, 3614.4210419887], [1547341199000, 3598.6587927813],
								  [1547344799000, 3597.9616032547], [1547348399000, 3615.9332270839],
								  [1547351998000, 3620.8904010166], [1547355599000, 3613.5027245852],
								  [1547359200000, 3611.9987780346], [1547366399000, 3611.2780551307],
								  [1547370000000, 3605.0902730139], [1547373599000, 3614.4927181627],
								  [1547377199000, 3613.2789789852], [1547380799000, 3618.6622453065],
								  [1547384399000, 3613.731295518], [1547388000000, 3616.7041800254],
								  [1547391600000, 3602.7140391784], [1547395199000, 3608.7975121504],
								  [1547398799000, 3499.6874463448], [1547402399000, 3512.3396799056],
								  [1547405999000, 3496.6754500055], [1547409599000, 3499.14898044],
								  [1547413199000, 3491.9250646025], [1547416799000, 3511.1382674525],
								  [1547420400000, 3497.6693915005], [1547423999000, 3503.8996768286],
								  [1547427599000, 3510.1140315583], [1547431199000, 3517.7391064626],
								  [1547434799000, 3522.905227422], [1547438399000, 3514.7349634416],
								  [1547441999000, 3525.0715426572], [1547445599000, 3528.3732780176],
								  [1547449199000, 3538.9059664722], [1547452799000, 3537.3521273839],
								  [1547456399000, 3530.7374695635], [1547459999000, 3532.9963271935],
								  [1547463599000, 3527.2880156209], [1547467199000, 3517.5426362091],
								  [1547470799000, 3532.9849461128], [1547474400000, 3524.8508034988],
								  [1547478000000, 3525.3178618232], [1547481600000, 3662.2832968884],
								  [1547485199000, 3647.7017540944], [1547488799000, 3659.3149310117],
								  [1547492400000, 3661.0702930888], [1547495999000, 3669.8834800187],
								  [1547499600000, 3660.0886687259], [1547503200000, 3655.4988648333],
								  [1547506799000, 3654.3470673605], [1547510399000, 3655.8230486667],
								  [1547513999000, 3663.5864223012], [1547517600000, 3663.6651591254],
								  [1547521200000, 3653.7439663528], [1547524799000, 3657.1798639286],
								  [1547528399000, 3658.4495870969], [1547531999000, 3651.5215811555],
								  [1547535600000, 3648.6282677227], [1547539200000, 3659.7349176033],
								  [1547542800000, 3655.9921904921], [1547546398000, 3632.1544006856],
								  [1547549998000, 3638.8246758283], [1547553599000, 3639.4059454741],
								  [1547557200000, 3630.8189700319], [1547560799000, 3649.8498974055],
								  [1547564399000, 3646.245285931], [1547567999000, 3622.7227553026],
								  [1547571599000, 3622.3330781422], [1547575199000, 3626.0170033256],
								  [1547578800000, 3622.4208348521], [1547582400000, 3626.3927304338],
								  [1547585999000, 3557.994386901], [1547589599000, 3562.7070611823],
								  [1547593199000, 3568.7512587366], [1547596800000, 3576.0124133363],
								  [1547600399000, 3584.2303821243], [1547603999000, 3589.5723475239],
								  [1547607598000, 3583.999254604]]

	sta = []

	for record in chartData:
		_new = {}
		_new["date"] = record[0]
		_new["price"] = record[1]
		tweets = tweet_sa_collection.find({'created_at': {'$gte': (_new["date"] / 1000) - 3600, '$lt': _new["date"] / 1000}})
		_new["pos"] = 0
		_new["neg"] = 0
		for tweet in tweets:
			if tweet["label"] == 1:
				_new["pos"] += 1
			else:
				_new["neg"] += 1
		sta.append(_new)

	if len(sta) > 0:
		data["success"] = True
		data["statistics"] = sta

	return flask.jsonify(data)



# if this is the main thread of execution first load the model and
# then start the server
if __name__ == "__main__":
	global model
	print(("* Loading Keras model and Flask starting server..."
		"please wait until server has fully started"))
	model = load_model(model_path)
	global graph
	graph = tf.get_default_graph()

	# job()

	app.run(host = '192.168.52.179', port = '5000')



