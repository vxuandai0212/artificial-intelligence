from speech_to_text.recognize import recognize_audio
from emotion.predict import predict_emo
from util import join_sen

emo_table = [
    'female_angry',
    'female_calm',
    'female_fearful',
    'female_happy',
    'female_sad',
    'male_angry',
    'male_calm',
    'male_fearful',
    'male_happy',
    'male_sad'
]

# audio_file = '/home/lam/Desktop/vini-intern/speech_recognition/speech_to_text/Actor_01/03-01-01-01-01-01-01.wav'
#
# trans = recognize_audio(audio_file)
# print("Trans: {}".format(trans))
# predicted_emo = predict_emo(audio_file)
# print("Predicted emotion: {}".format(emo_table[predicted_emo]))

import flask
from flask import session, request, jsonify
from flask_cors import CORS
import os
from datetime import timedelta

# initialize our Flask application
app = flask.Flask(__name__)
app.secret_key = 'super secret key'
CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'

@app.route("/predict", methods=["POST"])
def analy():
    data = {"success": False}
    def trans(voice, full_trans):
        try:
            data["trans"] = recognize_audio(voice)
        except:
            data["trans"] = ''
        if full_trans != '':
            print('full_trans is available')
            try:
                data["full_trans"] = join_sen(full_trans, data["trans"])
            except:
                data["full_trans"] = full_trans + ' ' + data["trans"]
                print("can not join sentence")
        else:
            print('full_trans is empty')
            data["full_trans"] = data["trans"]

    def pre(filename):
        if os.path.isfile(filename):
            try:
                predicted_emo = predict_emo(filename)
                data["emotion_code"] = int(predicted_emo)
                data["emotion"] = emo_table[predicted_emo]
            except:
                print("no emotion detected")

    if flask.request.method == "POST":
        filename = flask.request.form["fname"]
        voice = flask.request.files['data']
        full_trans = flask.request.form["full_trans"]
        voice.save(filename)
        if os.path.isfile(filename):
            trans(filename, full_trans)
            pre(filename)
            os.remove(filename)
        data["success"] = True

    res = jsonify(data)
    res.headers.add('Access-Control-Allow-Headers',
                         "Origin, X-Requested-With, Content-Type, Accept, x-auth")
    return res

# if this is the main thread of execution first load the model and
# then start the server
if __name__ == "__main__":
	app.run(host = '192.168.52.179', port = '5000')