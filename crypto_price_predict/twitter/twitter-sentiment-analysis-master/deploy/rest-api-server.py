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

@app.route("/predict", methods=["POST"])
def predict():
	# initialize the data dictionary that will be returned from the
	# view
	data = {"success": False}

	if flask.request.method == "POST":
		if flask.request.data:
			req_data = flask.request.data
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

# if this is the main thread of execution first load the model and
# then start the server
if __name__ == "__main__":
	global model
	print(("* Loading Keras model and Flask starting server..."
		"please wait until server has fully started"))
	model = load_model(model_path)
	global graph
	graph = tf.get_default_graph()
	app.run(host = '192.168.52.7', port = '5000')