import io

# def transcribe_file(file):
#     speech_file = '/home/lam/Desktop/vini-intern/speech_recognition/' + file
#     """Transcribe the given audio file."""
#     from google.cloud import speech
#     from google.cloud.speech import enums
#     from google.cloud.speech import types
#     client = speech.SpeechClient()
#
#     with io.open(speech_file, 'rb') as audio_file:
#         content = audio_file.read()
#
#     audio = types.RecognitionAudio(content=content)
#     config = types.RecognitionConfig(
#         encoding=enums.RecognitionConfig.AudioEncoding.LINEAR16,
#         language_code='vi-VN')
#
#     response = client.recognize(config, audio)
#     _result = response.results[0].alternatives[0].transcript
#     print(u'Transcript: {}'.format(_result))

def transcribe_file(content):
    from google.cloud import speech
    from google.cloud.speech import enums
    from google.cloud.speech import types
    client = speech.SpeechClient()

    audio = types.RecognitionAudio(content=content)
    config = types.RecognitionConfig(
        encoding=enums.RecognitionConfig.AudioEncoding.LINEAR16,
        language_code='vi-VN')

    response = client.recognize(config, audio)
    _result = response.results[0].alternatives[0].transcript
    print(u'Transcript: {}'.format(_result))
    
from emotion.predict import predict_emo
from util import mer_tran

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
    current_tran = {"text": "", "emo": ""}

    def trans(voice):
        try:
            current_tran["text"] = transcribe_file(voice)
        except:
            current_tran["text"] = ""
        return

    def pre(filename):
        if os.path.isfile(filename):
            try:
                predicted_emo = predict_emo(filename)
                # data["emotion_code"] = int(predicted_emo)
                current_tran["emo"] = emo_table[predicted_emo]
            except:
                print("no emotion detected")
                current_tran["emo"] = "no"

    if flask.request.method == "POST":
        filename = flask.request.form["fname"]
        voice = flask.request.files['data']
        trans(voice)
        full_trans = flask.request.form["trans"]
        voice.save(filename)
        if os.path.isfile(filename):

            pre(filename)
            # os.remove(filename)
            data["trans"] = mer_tran(current_tran, full_trans)
        data["success"] = True

    res = jsonify(data)
    res.headers.add('Access-Control-Allow-Headers',
                         "Origin, X-Requested-With, Content-Type, Accept, x-auth")
    return res

# if this is the main thread of execution first load the model and
# then start the server
if __name__ == "__main__":
	app.run(host = '192.168.52.179', port = '5000')