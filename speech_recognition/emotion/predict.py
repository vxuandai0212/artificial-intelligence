import os
import pandas as pd
import librosa
import librosa.display
import glob
import matplotlib.pyplot as plt
import numpy as np
import tensorflow as tf

from keras.models import model_from_json
json_file = open('/home/lam/Desktop/vini-intern/speech_recognition/emotion/model.json', 'r')
loaded_model_json = json_file.read()
json_file.close()
loaded_model = model_from_json(loaded_model_json)
# load weights into new model
loaded_model.load_weights("/home/lam/Desktop/vini-intern/speech_recognition/emotion/saved_models/Emotion_Voice_Detection_Model.h5")
print("Loaded model from disk")

from sklearn.preprocessing import LabelEncoder
lb = LabelEncoder()

def predict_emo(file):

    # data, sampling_rate = librosa.load(file)
    #
    # plt.figure(figsize=(15, 5))
    #
    # librosa.display.waveplot(data, sr=sampling_rate)

    filename = '/home/lam/Desktop/vini-intern/speech_recognition/' + file
    # print(filename)

    X, sample_rate = librosa.load(filename, res_type='kaiser_fast',duration=2.5,sr=22050*2,offset=0.5)
    sample_rate = np.array(sample_rate)
    mfccs = np.mean(librosa.feature.mfcc(y=X, sr=sample_rate, n_mfcc=13),axis=0)
    featurelive = mfccs
    livedf2 = featurelive

    livedf2= pd.DataFrame(data=livedf2)

    livedf2 = livedf2.stack().to_frame().T

    twodim = np.expand_dims(livedf2, axis=2)

    with graph.as_default():
        livepreds = loaded_model.predict(twodim, batch_size=32, verbose=1)
        livepreds1 = livepreds.argmax(axis=1)

        liveabc = livepreds1.astype(int).flatten()

        return liveabc[0]

global graph
graph = tf.get_default_graph()
# print(predict_emo('23.wav'))

# livepredictions = (lb.inverse_transform((liveabc)))
